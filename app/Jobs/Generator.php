<?php

namespace App\Jobs;

use Log;
use Exception;
use App\Jobs\Job;
use mikehaertl\wkhtmlto\Pdf;
use mikehaertl\pdftk\Pdf as Pdftk;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;

class Generator extends Job implements SelfHandling
{
    protected $document;

    /**
     * Create a new job instance.
     */
    public function __construct(Array $document)
    {
        $this->document = $document;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // this is where we will initiate the generation process
        // which we'll eventually do by calling an appropriate class to
        // integrate the data received into a PDF and then passing it back to the manager
        // or even more likely, by firing an event and then having a series of listeners respond to it

        // for now ...

        $view = view('documents.chase-letter')->with('document', $this->document)->render();

        $dataPDF = storage_path('app/tmp-'.$this->document['reference'].'.pdf');
        $finalPDF = storage_path('app/'.$this->document['reference'].'.pdf');

        // create PDF
        try {
            $pdf = new Pdf(
                [
                    'binary' => '/usr/bin/wkhtmltopdf',

                    'margin-top'    => 0,
                    'margin-right'  => 0,
                    'margin-bottom' => 0,
                    'margin-left'   => 0,

                    'commandOptions' => [
                        'enableXvfb' => true,
                    ],
                ]
            );
            $pdf->addPage($view);
            $pdf->saveAs($dataPDF);

        } catch (Exception $e) {
            Log::error('Could not create PDF: '.$pdf->getError());
        }

        // stamp it
        try {
            $pdftk = new Pdftk(base_path('resources/assets/docs/cluster-template.pdf'));
            $pdftk->stamp($dataPDF);
            $pdftk->saveAs($finalPDF);

        } catch (Exception $e) {
            Log::error('Could not stamp or save PDF: '.$pdf->getError());
        }

        $data = [
            'multipart' => [
                [
                    'name'     => $this->document['reference'].'.pdf',
                    'contents' => fopen($finalPDF, 'r')
                ],
            ]
        ];

        $httpClient = new HttpClient;
        $httpClient->post(env('CLUSTER_MANAGER').'/receive-pdf', $data);

        // @todo, use proper Laravel helpers for removing finished-with files
        unlink($dataPDF);
        unlink($finalPDF);
    }
}

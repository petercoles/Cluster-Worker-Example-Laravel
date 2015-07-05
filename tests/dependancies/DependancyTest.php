<?php

class DependancyTest extends TestCase
{
    /**
     * Test that wkhtmltopdf is available
     *
     * @return void
     */
    public function testPresenceOfWkhtmltopdf()
    {
        exec('wkhtmltopdf --version', $result);

        $this->assertContains('wkhtmltopdf 0', $result[0]);
    }
}
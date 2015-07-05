## Cluster Worker Example

### Introduction ###

This application demonstrates how to use the PHP Cluster Manager package. It's designed to be paired with a Cluster Manager Example.

The underlying idea is that a cluster manager application monitors a queue. When it spots jobs appearing there, it powers up worker servers to deal with them. The work servers are created from a server image. When the job queue is empty the cluster manager powers the workers down.

This package forms part, but not all of the software needed by the worker, which in this will receive an array of information to be inserted into a document template, with the final document being returned to the cluster manager.

This means that the worker is self-contained, needing only the data that it receives from the queue to perform its job, and that it is stateless, i.e. need retain no information after the finished document has been passed to the cluster manager.

This example is written in Laravel.

### Requirements ###

For this specific example, it will be assumed that the following have been installed on the worker image:

+ wkhtmltopdf

+ xvfb

+ pdftk

+ supervisor

If you're working with Ubuntu, all can be installed using apt-get install.

Wkhtmltopdf is used to create a pdf from the data received and appears to need xvfb to work effectively.

Pdftk is used to stamp this new pdf over the generic template.

Supervisor initiates and monitors the process which listens for jobs appearing on the queue.

For this example, I've used Iron MQ as the queue provider, the credentials for which are tucked away in my Laravel .env file, so get your own (they have a "lite" option which is free for up to a million api calls, which should keep you going for a while).

I've used Digital Ocean as the server provider. They're free, but they're at the least exepnsive end, and their API is easy to work with (that that's all taken care of by the PHP Cluster Manager package).

### Installation ###

Create a server - this example sort of expects a 1GB, Ubuntu server in Digital Ocean's LON1 location, and I'm rather partial to Nginx, so these instructions will assume that too. This will get a bit more flexible later on, especially as that centre doesn't seem to be their most stable.

Decide where you're going to put your application and clone it there from <aha, I need to make this public>. Then edit the nginx default config file to point there. Well, not exactly there, actually the index.php in the Laravel public folder.

Set up supervisor to initiate queue listening. To do this create a file in the /etc/supervisor/conf.d folder. I call mine worker.conf and give it the following contents - you will need to tweak this if you're put the application somewhere else.

```
TBD
```

Create an image of the server and call it "worker" - the code looks for this when creating, but more importantly destroying servers and you'll not be wanting the destroy the manager server.

Delete the original server and all should be ready to go (I will certainly have missed something, but will keep this readme up-to-date with all my omissions and errors as I find them).
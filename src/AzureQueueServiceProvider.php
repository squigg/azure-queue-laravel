<?php

namespace Squigg\AzureQueueLaravel;

use Illuminate\Queue\QueueManager;

class AzureQueueServiceProvider extends \Illuminate\Support\ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->booted(function () {

            /** @var QueueManager $manager */
            $manager = $this->app['queue'];

            $manager->addConnector('azure', function () {
                return new AzureConnector;
            });
        });
    }
}

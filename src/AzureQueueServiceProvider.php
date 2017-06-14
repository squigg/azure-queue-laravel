<?php

namespace Squigg\AzureQueueLaravel;

use Illuminate\Queue\QueueManager;
use Illuminate\Support\ServiceProvider;

class AzureQueueServiceProvider extends ServiceProvider
{

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /** @var QueueManager $manager */
        $manager = $this->app['queue'];

        $manager->addConnector('azure', function () {
            return new AzureConnector;
        });
    }

}

<?php

use Illuminate\Foundation\Application;
use Illuminate\Queue\QueueManager;
use Squigg\AzureQueueLaravel\AzureConnector;
use Squigg\AzureQueueLaravel\AzureQueueServiceProvider;

class AzureQueueServiceProviderTest extends TestCase
{


    /** @test */
    public function it_can_boot_and_setup_queue_manager()
    {
        $mockApp = Mockery::mock(Application::class);
        $mockQueueManager = Mockery::mock(QueueManager::class);

        $mockQueueManager->shouldReceive('addConnector')->withArgs(function ($driver, $closure) {
            return $driver == 'azure' && ($closure() instanceof AzureConnector);
        });

        $mockApp->shouldReceive('offsetGet')->with('queue')->andReturn($mockQueueManager);

        $serviceProvider = new AzureQueueServiceProvider($mockApp);

        $serviceProvider->boot();

    }

}

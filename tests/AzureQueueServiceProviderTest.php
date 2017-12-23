<?php

class AzureQueueServiceProviderTest extends TestCase
{


    /** @test */
    public function it_can_boot_and_setup_queue_manager()
    {
        $mockApp = Mockery::mock(\Illuminate\Foundation\Application::class);
        $mockQueueManager = Mockery::mock(\Illuminate\Queue\QueueManager::class);

        $mockQueueManager->shouldReceive('addConnector')->withArgs(function ($driver, $closure) {
            return $driver == 'azure' && ($closure() instanceof \Squigg\AzureQueueLaravel\AzureConnector);
        });

        $mockApp->shouldReceive('offsetGet')->with('queue')->andReturn($mockQueueManager);

        $serviceProvider = new \Squigg\AzureQueueLaravel\AzureQueueServiceProvider($mockApp);

        $serviceProvider->boot();

    }

}

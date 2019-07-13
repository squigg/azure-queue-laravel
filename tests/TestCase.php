<?php

abstract class TestCase extends Orchestra\Testbench\TestCase
{

    protected function getPackageProviders($app)
    {
        return [
            Squigg\AzureQueueLaravel\AzureQueueServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [];
    }

    protected function tearDown(): void
    {
        $this->addToAssertionCount(Mockery::getContainer()->mockery_getExpectationCount());
        Mockery::close();
    }

}

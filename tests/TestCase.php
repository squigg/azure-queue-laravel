<?php
namespace Squigg\AzureQueueLaravel\Tests;

use Mockery;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Squigg\AzureQueueLaravel\AzureQueueServiceProvider;

abstract class TestCase extends OrchestraTestCase
{

    protected function getPackageProviders($app): array
    {
        return [
            AzureQueueServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [];
    }

    protected function tearDown(): void
    {
        $this->addToAssertionCount(Mockery::getContainer()->mockery_getExpectationCount());
        Mockery::close();
    }

}

<?php
namespace Squigg\AzureQueueLaravel\Tests;

use MicrosoftAzure\Storage\Queue\Internal\IQueue;
use MicrosoftAzure\Storage\Queue\QueueRestProxy;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;
use Squigg\AzureQueueLaravel\AzureConnector;
use Squigg\AzureQueueLaravel\AzureQueue;

final class AzureConnectorTest extends TestCase
{

    protected AzureConnector $connector;
    protected MockInterface $queueRestProxy;
    protected array $config;

    protected function setUp(): void
    {
        parent::setUp();

        $this->config = [
            'protocol' => 'https',
            'accountname' => 'foo',
            'key' => 'bar',
            'queue' => 'baz',
            'timeout' => 25,
        ];

        $this->connector = new AzureConnector();
        $this->queueRestProxy = Mockery::mock('alias:' . QueueRestProxy::class);
    }

    #[Test]
    public function it_can_create_azure_queue(): void
    {
        $connectionString = 'DefaultEndpointsProtocol=https;AccountName=foo;AccountKey=bar';
        $queueProxy = Mockery::mock(IQueue::class);

        $this->queueRestProxy->shouldReceive('createQueueService')->once()->with($connectionString)->andReturn($queueProxy);

        $azureQueue = $this->connector->connect($this->config);
        $this->assertEquals('baz', $azureQueue->getQueue(null));
        $this->assertEquals(25, $azureQueue->getVisibilityTimeout());
    }

    #[Test]
    public function it_can_create_azure_queue_with_endpoint(): void
    {
        $this->config['endpoint'] = 'mysuffix';

        $connectionString = 'DefaultEndpointsProtocol=https;AccountName=foo;AccountKey=bar;EndpointSuffix=mysuffix';
        $queueProxy = Mockery::mock(IQueue::class);
        $this->queueRestProxy->shouldReceive('createQueueService')->once()->with($connectionString)->andReturn($queueProxy);

        /** @var AzureQueue $azureQueue */
        $this->connector->connect($this->config);
    }

    #[Test]
    public function it_can_create_azure_queue_with_queue_endpoint(): void
    {
        $this->config['queue_endpoint'] = 'http://localhost:10001/test';

        $connectionString = 'DefaultEndpointsProtocol=https;AccountName=foo;AccountKey=bar;QueueEndpoint=http://localhost:10001/test';
        $queueProxy = Mockery::mock(IQueue::class);
        $this->queueRestProxy->shouldReceive('createQueueService')->once()->with($connectionString)->andReturn($queueProxy);

        /** @var AzureQueue $azureQueue */
        $this->connector->connect($this->config);
    }
}

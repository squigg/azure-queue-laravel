<?php

use MicrosoftAzure\Storage\Queue\Internal\IQueue;
use MicrosoftAzure\Storage\Queue\QueueRestProxy;
use Squigg\AzureQueueLaravel\AzureConnector;
use Squigg\AzureQueueLaravel\AzureQueue;

class AzureConnectorTest extends TestCase
{

    /**
     * @var AzureConnector
     */
    protected $connector;

    /**
     * @var \Mockery\Mock
     */
    protected $queueRestProxy;
    protected $config;

    public function setUp()
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

    /** @test */
    public function it_can_create_azure_queue()
    {
        $connectionString = 'DefaultEndpointsProtocol=https;AccountName=foo;AccountKey=bar';
        $queueProxy = Mockery::mock(IQueue::class);

        $this->queueRestProxy->shouldReceive('createQueueService')->once()->with($connectionString)->andReturn($queueProxy);

        /** @var AzureQueue $azureQueue */
        $azureQueue = $this->connector->connect($this->config);
        $this->assertEquals('baz', $azureQueue->getQueue(null));
        $this->assertEquals(25, $azureQueue->getVisibilityTimeout());
    }

    /** @test */
    public function it_can_create_azure_queue_with_endpoint()
    {
        $this->config['endpoint'] = 'mysuffix';

        $connectionString = 'DefaultEndpointsProtocol=https;AccountName=foo;AccountKey=bar;EndpointSuffix=mysuffix';
        $queueProxy = Mockery::mock(IQueue::class);
        $this->queueRestProxy->shouldReceive('createQueueService')->once()->with($connectionString)->andReturn($queueProxy);

        /** @var AzureQueue $azureQueue */
        $this->connector->connect($this->config);
    }

}

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

    public function setUp()
    {
        parent::setUp();

        $this->connector = new AzureConnector();
        $this->queueRestProxy = Mockery::mock('alias:' . QueueRestProxy::class);
    }

    /** @test */
    public function it_can_create_azure_queue()
    {
        $config = [
            'protocol' => 'https',
            'accountname' => 'foo',
            'key' => 'bar',
            'queue' => 'baz',
            'timeout' => 25,
        ];

        $connectionString = 'DefaultEndpointsProtocol=' . $config['protocol'] . ';AccountName=' . $config['accountname'] . ';AccountKey=' . $config['key'];
        $queueProxy = Mockery::mock(IQueue::class);

        $this->queueRestProxy->shouldReceive('createQueueService')->once()->with($connectionString)->andReturn($queueProxy);

        /** @var AzureQueue $azureQueue */
        $azureQueue = $this->connector->connect($config);
        $this->assertEquals('baz', $azureQueue->getQueue(null));
        $this->assertEquals(25, $azureQueue->getVisibilityTimeout());
    }

}

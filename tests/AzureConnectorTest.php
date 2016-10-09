<?php

use MicrosoftAzure\Storage\Common\ServicesBuilder;
use MicrosoftAzure\Storage\Queue\Internal\IQueue;
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
    protected $servicesBuilder;

    public function setUp()
    {
        parent::setUp();

        $this->connector = new AzureConnector();
        $this->servicesBuilder = Mockery::mock('alias:' . ServicesBuilder::class);
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

        $this->servicesBuilder->shouldReceive('getInstance->createQueueService')->once()->with($connectionString)->andReturn($queueProxy);

        /** @var AzureQueue $azureQueue */
        $azureQueue = $this->connector->connect($config);
        $this->assertEquals('baz', $azureQueue->getQueue(null));
        $this->assertEquals(25, $azureQueue->getVisibilityTimeout());
    }

}

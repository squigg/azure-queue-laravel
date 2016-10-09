<?php

use MicrosoftAzure\Storage\Queue\Internal\IQueue;
use MicrosoftAzure\Storage\Queue\Models\MicrosoftAzureQueueMessage;
use Squigg\AzureQueueLaravel\AzureJob;
use Squigg\AzureQueueLaravel\AzureQueue;

class AzureJobTest extends TestCase
{

    /**
     * @var \Mockery\Mock
     */
    protected $azure;

    /**
     * @var AzureQueue
     */
    protected $queue;

    /**
     * @var MicrosoftAzureQueueMessage
     */
    protected $message;

    /**
     * @var AzureJob
     */
    protected $job;

    public function setUp()
    {
        parent::setUp();

        $this->azure = Mockery::mock(\MicrosoftAzure\Storage\Queue\Internal\IQueue::class);
        $this->queue = new AzureQueue($this->azure, 'myqueue', 5);
        $this->queue->setContainer($this->app);

        $this->message = new MicrosoftAzureQueueMessage();
        $this->message->setMessageId('1234');
        $this->message->setPopReceipt('9876');
        $this->message->setMessageText('{"abcd":"efgh"}');
        $this->message->setDequeueCount(2);

        $this->job = new AzureJob($this->app, $this->azure, $this->message, 'myqueue');
    }

    /** @test */
    public function it_can_get_job_id()
    {
        $this->assertEquals('1234', $this->job->getJobId());
    }

    /** @test */
    public function it_can_delete_job_from_queue()
    {
        $this->azure->shouldReceive('deleteMessage')->once()->withArgs(['myqueue', '1234', '9876']);
        $this->job->delete();
    }

    /** @test */
    public function it_can_release_job_back_to_queue()
    {
        $this->azure->shouldReceive('updateMessage')->once()->withArgs(['myqueue', '1234', '9876', null, 10]);
        $this->job->release(10);
    }

    /** @test */
    public function it_can_get_azure_job()
    {
        $this->assertEquals($this->message, $this->job->getAzureJob());
    }

    /** @test */
    public function it_can_get_raw_body()
    {
        $this->assertEquals('{"abcd":"efgh"}', $this->job->getRawBody());
    }

    /** @test */
    public function it_can_get_azure_proxy()
    {
        $this->assertInstanceOf(IQueue::class, $this->job->getAzure());
    }

    /** @test */
    public function it_can_get_number_of_attempts()
    {
        $this->assertEquals(2, $this->job->attempts());
    }

    /** @test */
    public function it_can_get_app_container()
    {
        $this->assertEquals($this->app, $this->job->getContainer());
    }

}

<?php

use MicrosoftAzure\Storage\Queue\Internal\IQueue;
use MicrosoftAzure\Storage\Queue\Models\CreateMessageOptions;
use MicrosoftAzure\Storage\Queue\Models\GetQueueMetadataResult;
use MicrosoftAzure\Storage\Queue\Models\ListMessagesOptions;
use Squigg\AzureQueueLaravel\AzureJob;
use Squigg\AzureQueueLaravel\AzureQueue;

class AzureQueueTest extends TestCase
{

    /**
     * @var \Mockery\Mock
     */
    protected $azure;
    /**
     * @var AzureQueue
     */
    protected $queue;

    public function setUp()
    {
        parent::setUp();
        $this->azure = Mockery::mock(\MicrosoftAzure\Storage\Queue\Internal\IQueue::class);
        $this->queue = new AzureQueue($this->azure, 'myqueue', 5);
        $this->queue->setContainer($this->app);
    }

    /**
     * @param Mockery\CompositeExpectation $mock
     * @param int $count
     * @return \Mockery\CompositeExpectation
     */
    protected function setListMessagesReturnExpectation($mock, $count = 1)
    {
        return $mock->andReturn(new ListMessagesResult($count));
    }

    /** @test */
    public function it_can_push_message_to_queue()
    {
        $this->azure->shouldReceive('createMessage')->once()->withArgs(["myqueue", '{"job":"job","data":"data"}']);
        $this->queue->push('job', 'data');
    }

    /** @test */
    public function it_can_pop_message_from_queue()
    {
        $this->setListMessagesReturnExpectation($this->azure->shouldReceive('listMessages')->once());

        $message = $this->queue->pop('myqueue');
        $this->assertInstanceOf(AzureJob::class, $message);
    }

    /** @test */
    public function it_can_pop_message_from_queue_using_default()
    {
        $this->setListMessagesReturnExpectation($this->azure->shouldReceive('listMessages')->once());

        $message = $this->queue->pop();
        $this->assertInstanceOf(AzureJob::class, $message);
        $this->assertEquals('myqueue', $message->getQueue());
    }

    /** @test */
    public function it_returns_null_if_no_messages_to_pop()
    {
        $this->setListMessagesReturnExpectation($this->azure->shouldReceive('listMessages')->once(), 0);

        $message = $this->queue->pop('myqueue');
        $this->assertNull($message);
    }

    /** @test */
    public function it_passes_visibility_timeout_set_in_config()
    {
        $this->setListMessagesReturnExpectation(
            $this->azure->shouldReceive('listMessages')->once()->withArgs(
                function ($queue, ListMessagesOptions $options) {
                    return $queue == 'myqueue' && $options->getVisibilityTimeoutInSeconds() == 5;
                }
            )
        );

        $this->queue->pop('myqueue');
    }

    /** @test */
    public function it_only_fetches_first_message()
    {
        $this->setListMessagesReturnExpectation(
            $this->azure->shouldReceive('listMessages')->once()->withArgs(
                function ($queue, ListMessagesOptions $options) {
                    return $queue == 'myqueue' && $options->getNumberOfMessages() == 1;
                }
            )
        );

        $this->queue->pop('myqueue');
    }

    /** @test */
    public function it_can_get_visibility_timeout()
    {
        $this->assertEquals(5, $this->queue->getVisibilityTimeout());
    }

    /** @test */
    public function it_can_queue_a_job_for_later()
    {
        $this->azure->shouldReceive('createMessage')->once()->withArgs(
            function ($queue, $payload, CreateMessageOptions $options) {
                return $queue == 'myqueue' && $payload == '{"job":"job","data":"data"}' && $options->getVisibilityTimeoutInSeconds() == 10;
            }
        );

        $this->queue->later(10, 'job', 'data', 'myqueue');
    }

    /** @test */
    public function it_can_get_queue_size()
    {
        $this->azure->shouldReceive('getQueueMetadata')->with('myqueue')->andReturn(new GetQueueMetadataResult(5, []));

        $this->assertEquals(5, $this->queue->size('myqueue'));
    }

    /** @test */
    public function it_can_get_azure_instance()
    {
        $this->assertInstanceOf(IQueue::class, $this->queue->getAzure());
    }
}

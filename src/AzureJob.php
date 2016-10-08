<?php

namespace Squigg\AzureQueueLaravel;

use Illuminate\Container\Container;
use Illuminate\Contracts\Queue\Job as JobContract;
use Illuminate\Queue\Jobs\Job;
use MicrosoftAzure\Storage\Queue\Internal\IQueue;
use MicrosoftAzure\Storage\Queue\Models\MicrosoftAzureQueueMessage;
use MicrosoftAzure\Storage\Queue\Models\QueueMessage;
use MicrosoftAzure\Storage\Queue\QueueRestProxy;

class AzureJob extends Job implements JobContract
{

    /**
     * The Azure QueueRestProxy instance.
     *
     * @var QueueRestProxy
     */
    protected $azure;

    /**
     * The Azure QueueMessage instance.
     *
     * @var QueueMessage
     */
    protected $job;

    /**
     * The queue that the job belongs to.
     *
     * @var string
     */
    protected $queue;

    /**
     * Create a new job instance.
     *
     * @param \Illuminate\Container\Container $container
     * @param IQueue $azure
     * @param MicrosoftAzureQueueMessage $job
     * @param  string $queue
     *
     * @return \Squigg\AzureQueueLaravel\AzureJob
     */
    public function __construct(Container $container, IQueue $azure, MicrosoftAzureQueueMessage $job, $queue)
    {
        $this->azure = $azure;
        $this->job = $job;
        $this->queue = $queue;
        $this->container = $container;
    }

    /**
     * Fire the job.
     *
     * @return void
     */
    public function fire()
    {
        $this->resolveAndFire(json_decode($this->getRawBody(), true));
    }

    /**
     * Delete the job from the queue.
     *
     * @return void
     */
    public function delete()
    {
        parent::delete();
        $this->azure->deleteMessage($this->queue, $this->job->getMessageId(), $this->job->getPopReceipt());
    }

    /**
     * Release the job back into the queue.
     *
     * @param  int $delay
     *
     * @return void
     */
    public function release($delay = 0)
    {
        parent::release($delay);
        $this->azure->updateMessage($this->queue, $this->job->getMessageId(), $this->job->getPopReceipt(), null,
            $delay);
    }

    /**
     * Get the number of times the job has been attempted.
     *
     * @return int
     */
    public function attempts()
    {
        return $this->job->getDequeueCount();
    }

    /**
     * Get the IoC container instance.
     *
     * @return \Illuminate\Container\Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Get the underlying Azure client instance.
     *
     * @return QueueRestProxy
     */
    public function getAzure()
    {
        return $this->azure;
    }

    /**
     * Get the underlying raw Azure job.
     *
     * @return QueueMessage
     */
    public function getAzureJob()
    {
        return $this->job;
    }

    /**
     * @return int
     */
    public function getJobId()
    {
        return $this->job->getMessageId();
    }

    /**
     * Get the raw body string for the job.
     *
     * @return string
     */
    public function getRawBody()
    {
        return $this->job->getMessageText();
    }
}

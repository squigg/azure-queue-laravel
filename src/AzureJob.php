<?php

namespace Squigg\AzureQueueLaravel;

use Illuminate\Container\Container;
use Illuminate\Contracts\Queue\Job as JobContract;
use Illuminate\Queue\Jobs\Job;
use MicrosoftAzure\Storage\Queue\Internal\IQueue;
use MicrosoftAzure\Storage\Queue\Models\QueueMessage;

class AzureJob extends Job implements JobContract
{

    /**
     * The Azure QueueRestProxy instance.
     */
    protected IQueue $azure;

    /**
     * The Azure QueueMessage instance.
     */
    protected QueueMessage $job;

    /**
     * The queue that the job belongs to.
     */
    protected $queue;

    /**
     * Create a new job instance.
     *
     * @param Container $container
     * @param IQueue $azure
     * @param QueueMessage $job
     * @param string $connectionName
     * @param string $queue
     *
     */
    public function __construct(Container    $container,
                                IQueue       $azure,
                                QueueMessage $job,
                                string       $connectionName,
                                string       $queue)
    {
        $this->azure = $azure;
        $this->job = $job;
        $this->queue = $queue;
        $this->container = $container;
        $this->connectionName = $connectionName;
    }

    /**
     * Delete the job from the queue.
     */
    public function delete(): void
    {
        parent::delete();
        $this->azure->deleteMessage($this->queue, $this->job->getMessageId(), $this->job->getPopReceipt());
    }

    /**
     * Release the job back into the queue.
     *
     * @param int $delay
     */
    public function release($delay = 0): void
    {
        parent::release($delay);
        $this->azure->updateMessage($this->queue, $this->job->getMessageId(), $this->job->getPopReceipt(), null,
            $delay);
    }

    /**
     * Get the number of times the job has been attempted.
     */
    public function attempts(): int
    {
        return $this->job->getDequeueCount();
    }

    /**
     * Get the IoC container instance.
     */
    public function getContainer(): Container
    {
        return $this->container;
    }

    /**
     * Get the underlying Azure client instance.
     */
    public function getAzure(): IQueue
    {
        return $this->azure;
    }

    /**
     * Get the underlying raw Azure job.
     */
    public function getAzureJob(): QueueMessage
    {
        return $this->job;
    }

    /**
     * Get the job ID
     */
    public function getJobId(): int
    {
        return $this->job->getMessageId();
    }

    /**
     * Get the raw body string for the job.
     */
    public function getRawBody(): string
    {
        return $this->job->getMessageText();
    }
}

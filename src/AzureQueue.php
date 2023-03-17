<?php

namespace Squigg\AzureQueueLaravel;

use DateTime;
use Illuminate\Contracts\Queue\Queue as QueueInterface;
use Illuminate\Queue\Queue;
use MicrosoftAzure\Storage\Queue\Internal\IQueue;
use MicrosoftAzure\Storage\Queue\Models\CreateMessageOptions;
use MicrosoftAzure\Storage\Queue\Models\ListMessagesOptions;

class AzureQueue extends Queue implements QueueInterface
{

    /**
     * The Azure IQueue instance.
     */
    protected IQueue $azure;

    /**
     * The name of the default queue.
     */
    protected string $default;

    /**
     * The value in seconds that the queue item is invisible to other requesters
     */
    protected int $visibilityTimeout;

    /**
     * Create a new Azure IQueue queue instance.
     *
     * @param IQueue $azure
     * @param string $default
     * @param int $visibilityTimeout
     */
    public function __construct(IQueue $azure, string $default, int $visibilityTimeout)
    {
        $this->azure = $azure;
        $this->default = $default;
        $this->visibilityTimeout = $visibilityTimeout ?: 5;
    }

    /**
     * Push a new job onto the queue.
     *
     * @param string $job
     * @param mixed $data
     * @param string $queue
     *
     * @return void
     */
    public function push($job, $data = '', $queue = null): void
    {
        $this->pushRaw($this->createPayload($job, $queue, $data), $queue);
    }

    /**
     * Push a raw payload onto the queue.
     *
     * @param  string $payload
     * @param  string $queue
     * @param  array $options
     *
     * @return void
     */
    public function pushRaw($payload, $queue = null, array $options = []): void
    {
        $this->azure->createMessage($this->getQueue($queue), $payload);
    }

    /**
     * Push a new job onto the queue after (n) seconds.
     *
     * @param  DateTime|int $delay
     * @param  string $job
     * @param  mixed $data
     * @param  string $queue
     *
     * @return void
     */
    public function later($delay, $job, $data = '', $queue = null): void
    {
        $payload = $this->createPayload($job, $queue, $data);

        $options = new CreateMessageOptions();
        $options->setVisibilityTimeoutInSeconds($this->secondsUntil($delay));

        $this->azure->createMessage($this->getQueue($queue), $payload, $options);
    }

    /**
     * Pop the next job off of the queue.
     *
     * @param string|null $queue
     *
     * @return AzureJob|null
     */
    public function pop($queue = null): AzureJob|null
    {
        $queue = $this->getQueue($queue);

        // As recommended in the API docs, first call listMessages to hide message from other code
        $listMessagesOptions = new ListMessagesOptions();
        $listMessagesOptions->setVisibilityTimeoutInSeconds($this->visibilityTimeout);
        $listMessagesOptions->setNumberOfMessages(1);

        $listMessages = $this->azure->listMessages($queue, $listMessagesOptions);
        $messages = $listMessages->getQueueMessages();

        if (count($messages) > 0) {
            return new AzureJob($this->container, $this->azure, $messages[0], $this->connectionName, $queue);
        }

        return null;
    }

    /**
     * Get the queue or return the default.
     *
     * @param string|null $queue
     *
     * @return string
     */
    public function getQueue(?string $queue): string
    {
        return $queue ?: $this->default;
    }

    /**
     * Get the visibility timeout for queue messages.
     *
     * @return int
     */
    public function getVisibilityTimeout(): int
    {
        return $this->visibilityTimeout;
    }

    /**
     * Get the underlying Azure IQueue instance.
     *
     * @return IQueue
     */
    public function getAzure(): IQueue
    {
        return $this->azure;
    }

    /**
     * Get the approximate size of the queue.
     *
     * @param  string $queue
     * @return int
     */
    public function size($queue = null): int
    {
        $queue = $this->getQueue($queue);

        $metaData = $this->azure->getQueueMetadata($queue);

        return $metaData->getApproximateMessageCount();
    }
}

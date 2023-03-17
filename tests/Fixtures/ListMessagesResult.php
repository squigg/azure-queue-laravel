<?php
namespace Squigg\AzureQueueLaravel\Tests\Fixtures;

use MicrosoftAzure\Storage\Queue\Models\QueueMessage;

class ListMessagesResult
{

    protected int $count;

    public function __construct(int $count = 1)
    {
        $this->count = $count;
    }

    public function getQueueMessages()
    {
        if ($this->count == 0) return [];
        return array_fill(0, $this->count, new QueueMessage());
    }
}

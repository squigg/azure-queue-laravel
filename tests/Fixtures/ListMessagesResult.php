<?php
use MicrosoftAzure\Storage\Queue\Models\QueueMessage;

/**
 * Created by PhpStorm.
 * User: squigg
 * Date: 09/10/16
 * Time: 14:23
 */
class ListMessagesResult
{

    /**
     * @var int
     */
    protected $count;

    /**
     * ListMessagesResult constructor.
     * @param int $count
     */
    public function __construct($count = 1)
    {
        $this->count = $count;
    }

    public function getQueueMessages()
    {
        if ($this->count == 0) return [];
        return array_fill(0, $this->count, new QueueMessage());
    }
}

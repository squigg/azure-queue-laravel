<?php
use MicrosoftAzure\Storage\Queue\Models\MicrosoftAzureQueueMessage;

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
     */
    public function __construct($count = 1)
    {

        $this->count = $count;
    }

    public function getQueueMessages()
    {
        return array_fill(0, $this->count, new MicrosoftAzureQueueMessage());
    }
}

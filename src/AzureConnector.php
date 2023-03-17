<?php

namespace Squigg\AzureQueueLaravel;

use Illuminate\Queue\Connectors\ConnectorInterface;
use MicrosoftAzure\Storage\Queue\QueueRestProxy;

class AzureConnector implements ConnectorInterface
{

    /**
     * Establish a queue connection.
     */
    public function connect(array $config): AzureQueue
    {
        $connectionString = 'DefaultEndpointsProtocol=' . $config['protocol'] . ';AccountName=' . $config['accountname'] . ';AccountKey=' . $config['key'];

        if (isset($config['endpoint']) && $config['endpoint'] !== "") {
            $connectionString .= ";EndpointSuffix=" . $config['endpoint'];
        }

        if (isset($config['queue_endpoint']) && $config['queue_endpoint'] !== "") {
            $connectionString .= ";QueueEndpoint=" . $config['queue_endpoint'];
        }

        $queueRestProxy = QueueRestProxy::createQueueService($connectionString);

        return new AzureQueue($queueRestProxy, $config['queue'], $config['timeout']);
    }
}

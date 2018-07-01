<?php

namespace Squigg\AzureQueueLaravel;

use Illuminate\Queue\Connectors\ConnectorInterface;
use MicrosoftAzure\Storage\Queue\QueueRestProxy;

class AzureConnector implements ConnectorInterface
{

    /**
     * Establish a queue connection.
     *
     * @param  array $config
     *
     * @return \Illuminate\Contracts\Queue\Queue
     */
    public function connect(array $config)
    {
        if (isset($config['enpoint']) && $config['enpoint'] != "") { // Check if endpoint is set
            
            $endpoint = ";EndpointSuffix=".$config['enpoint'];
            
        } else {
            
            $endpoint = "";
        }
        $connectionString = 'DefaultEndpointsProtocol=' . $config['protocol'] . ';AccountName=' . $config['accountname'] . ';AccountKey=' . $config['key'] . $endpoint;
        $queueRestProxy = QueueRestProxy::createQueueService($connectionString);

        return new AzureQueue($queueRestProxy, $config['queue'], $config['timeout']);
    }
}

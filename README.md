azure-queue-laravel
=============

[![Build Status](https://travis-ci.org/squigg/azure-queue-laravel.png?branch=master)](https://travis-ci.org/squigg/azure-queue-laravel)
[![Coverage Status](https://coveralls.io/repos/github/squigg/azure-queue-laravel/badge.svg?branch=master)](https://coveralls.io/github/squigg/azure-queue-laravel?branch=master)

[![Latest Stable Version](https://poser.pugx.org/squigg/azure-queue-laravel/v/stable.png)](https://packagist.org/packages/squigg/azure-queue-laravel)
[![Total Downloads](https://poser.pugx.org/squigg/azure-queue-laravel/downloads.png)](https://packagist.org/packages/squigg/azure-queue-laravel)

PHP Laravel 5 Queue Driver package to support Microsoft Azure Storage Queues

## Prerequisites

- PHP 5.5+ (5.6 required for v5.3+)
- Laravel 5.2, 5.3 and 5.4 (not tested on previous versions)
- Microsoft Azure Storage account and API key
- Queue container created through Azure Portal or via Azure CLI / PowerShell

## Installation

### Install by composer
You can find this library on [Packagist](https://packagist.org/packages/squigg/azure-queue-laravel).

Require this package in your `composer.json`. The version numbers will follow Laravel.
#### Laravel 5.2.x
	"squigg/azure-queue-laravel": "5.2.*"
	composer require squigg/azure-queue-laravel:5.2.* 
#### Laravel 5.3.x
    "squigg/azure-queue-laravel": "5.3.*"
    composer require squigg/azure-queue-laravel:5.3.*
#### Laravel 5.4.x
    "squigg/azure-queue-laravel": "5.4.*"
    composer require squigg/azure-queue-laravel:5.4.*
    
Add the following pear repository in your `composer.json` file required for the Microsoft Azure SDK
(not required for 5.4.x which uses the new `microsoft/windowsazure` package):
  
    "repositories": [
        {
            "type": "pear",
            "url": "http://pear.php.net"
        }
    ],
    
Update Composer dependencies

```sh
composer update
```

## Configuration
Add the ServiceProvider to your `providers` array in `app/config/app.php`:

	'Squigg\AzureQueueLaravel\AzureQueueServiceProvider',

add the following to the `connection` array in `app/config/queue.php`, set your `default` connection to `azure` and
fill out your own connection data from the Azure Management portal:

	'azure' => [
        'driver'        => 'azure',         // Leave this as-is
        'protocol'      => 'https',         // https or http
        'accountname'   => '',              // Azure storage account name
        'key'           => '',              // Access key for storage account
        'queue'         => '',              // Queue container name
        'timeout'       => 60               // Timeout (seconds) before a job is released back to the queue
    ],

You can add environment variables into your `.env` file to set the above configuration parameters if you desire:
    
    AZURE_QUEUE_STORAGE_NAME=xxx
    AZURE_QUEUE_KEY=xxx
    AZURE_QUEUE_NAME=xxx

And then update the config file like below:
    
    'accountname'   => env('AZURE_QUEUE_STORAGE_NAME'),   
    'key'           => env('AZURE_QUEUE_KEY'),   
    'queue'         => env('AZURE_QUEUE_NAME'),   

## Usage
Use the normal Laravel Queue functionality as per the [documentation](http://laravel.com/docs/queues).

Remember to update the default queue by setting the `QUEUE_DRIVER` value in your `.env` file to `azure`.

## Changelog
Will be added once stuff starts changing.

## License
Released under the MIT License. Based on [Alex Bouma's Laravel 4 package](https://github.com/stayallive/laravel-azure-blob-queue), updated for Laravel 5.

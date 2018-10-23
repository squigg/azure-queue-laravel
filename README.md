azure-queue-laravel
=============

[![Build Status](https://travis-ci.org/squigg/azure-queue-laravel.png?branch=master)](https://travis-ci.org/squigg/azure-queue-laravel)
[![Coverage Status](https://coveralls.io/repos/github/squigg/azure-queue-laravel/badge.svg?branch=master)](https://coveralls.io/github/squigg/azure-queue-laravel?branch=master)

[![Latest Stable Version](https://poser.pugx.org/squigg/azure-queue-laravel/v/stable.png)](https://packagist.org/packages/squigg/azure-queue-laravel)
[![Total Downloads](https://poser.pugx.org/squigg/azure-queue-laravel/downloads.png)](https://packagist.org/packages/squigg/azure-queue-laravel)

PHP Laravel 5 Queue Driver package to support Microsoft Azure Storage Queues

## Prerequisites

- PHP 5.6+, PHP 7+ for Laravel 5.5+, PHP 7.1+ for Laravel 5.6 and 5.7
- Laravel 5.2 - 5.7 (not tested on previous versions)
- Microsoft Azure Storage account and API key
- Queue container created through Azure Portal or via Azure CLI / PowerShell

## Installation

### Install using composer
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
#### Laravel 5.5.x
    "squigg/azure-queue-laravel": "5.5.*"
    composer require squigg/azure-queue-laravel:5.5.*
#### Laravel 5.6.x
    "squigg/azure-queue-laravel": "5.6.*"
    composer require squigg/azure-queue-laravel:5.6.*
#### Laravel 5.7.x
    "squigg/azure-queue-laravel": "5.7.*"
    composer require squigg/azure-queue-laravel:5.7.*
    
##### For versions 5.3 and older only
Add the following pear repository in your `composer.json` file required for the Microsoft Azure SDK
(v5.4+ uses the `microsoft/azure-storage` package instead, and v5.6+ uses `microsoft/azure-storage-queue`):
  
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
#### Add Provider
If you are not using Laravel auto package discovery, add the ServiceProvider to your `providers` array in `config/app.php`:

	'Squigg\AzureQueueLaravel\AzureQueueServiceProvider',

For Lumen (5.x) you will need to add the provider to `bootstrap/app.php`:

        $app->register(Squigg\AzureQueueLaravel\AzureQueueServiceProvider::class);

#### Add Azure queue configuration
Add the following to the `connections` array in `config/queue.php`, and
fill out your own connection data from the Azure Management portal:

	'azure' => [
        'driver'        => 'azure',                             // Leave this as-is
        'protocol'      => 'https',                             // https or http
        'accountname'   => env('AZURE_QUEUE_STORAGE_NAME'),     // Azure storage account name
        'key'           => env('AZURE_QUEUE_KEY'),              // Access key for storage account
        'queue'         => env('AZURE_QUEUE_NAME'),             // Queue container name
        'timeout'       => 60                                   // Seconds before a job is released back to the queue
        'endpoint'      => env('AZURE_QUEUE_ENDPOINTSUFFIX'),   // Optional endpoint suffix if different from core.windows.net
    ],

Add environment variables into your `.env` file to set the above configuration parameters if you prefer:
    
    AZURE_QUEUE_STORAGE_NAME=xxx
    AZURE_QUEUE_KEY=xxx
    AZURE_QUEUE_NAME=xxx
    AZURE_QUEUE_ENDPOINTSUFFIX=xxx
    
#### Set the default Laravel queue
Update the default queue used by Laravel by setting the `QUEUE_DRIVER` value in your `.env` file to `azure`.

    QUEUE_DRIVER=azure

## Usage
Use the normal Laravel Queue functionality as per the [documentation](http://laravel.com/docs/queues).

Remember to update the default queue by setting the `QUEUE_DRIVER` value in your `.env` file to `azure`.

## Changelog

2018-09-04 - V5.7 - Support for Laravel 5.7 (composer dependency changes only)

2018-02-07 - V5.6 - Switch to GA version of Microsoft Azure Storage PHP API. Support Laravel 5.6 (composer.json changes
only). Update dev dependencies to latest versions.

2017-09-11 - V5.5 - Support Laravel 5.5 and PHP7+ only. Update Azure Storage API to 0.18

2017-09-11 - V5.4 - Update Azure Storage API to 0.15 (no breaking changes)

## License
Released under the MIT License. Based on [Alex Bouma's Laravel 4 package](https://github.com/stayallive/laravel-azure-blob-queue), updated for Laravel 5.

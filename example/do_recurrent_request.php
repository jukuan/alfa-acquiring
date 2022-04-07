<?php

use AlfaAcquiring\Api\RecurrentPaymentRequest;
use AlfaAcquiring\RbsClient;

require __DIR__ . '/../vendor/autoload.php';

$apiClient = (new RbsClient('test-api', 'test'))
    ->enableTestMode(); // for debug only

$response = (new RecurrentPaymentRequest($apiClient))
    ->run();

print '<pre>';
var_dump('Query:', $apiClient->getLastQuery());
var_dump('HttpResponseCode:', $apiClient->getHttpResponseCode());

var_dump($response);
var_dump($apiClient->getLastQuery());
var_dump($apiClient->getHttpResponseCode());
print '</pre>';

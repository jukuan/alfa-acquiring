<?php

use AlfaAcquiring\Api\LastOrdersForMerchantsMethod;
use AlfaAcquiring\RbsClient;

require __DIR__ . '/../vendor/autoload.php';

/** @var RbsClient $rbsClient */
$rbsClient = require '_rbs_client.php';
$apiClient = $rbsClient
    ->enableTestMode() // for debug only
;

$response = (new LastOrdersForMerchantsMethod($apiClient))
    ->setFrom(new \DateTime('-1 day'))
    ->run();

print '<pre>';
var_dump('Last Query:', $apiClient->getLastQuery());
var_dump('Last Method Params:', $apiClient->getLastMethodParams());
var_dump('HttpResponseCode:', $apiClient->getHttpResponseCode());

var_dump($response->getErrorMessage());
//var_dump($response->getAllFields());
print '</pre>';

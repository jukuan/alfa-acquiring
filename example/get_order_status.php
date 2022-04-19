<?php

use AlfaAcquiring\Api\OrderStatusMethod;
use AlfaAcquiring\RbsClient;

require __DIR__ . '/../vendor/autoload.php';

/** @var RbsClient $rbsClient */
$rbsClient = require '_rbs_client.php';
$apiClient = $rbsClient
    ->enableTestMode() // for debug only
;

$orderIdExample = '34fe0e85-3bb5-7218-a726-a83c00caf12b';

// the old way
//$response = $apiClient->getOrderStatus($orderIdExample);

// the new way
$response = (new OrderStatusMethod($apiClient))
    ->setOrderId($orderIdExample)
    ->run();

print '<pre>';
var_dump('Query:', $apiClient->getLastQuery());
var_dump('HttpResponseCode:', $apiClient->getHttpResponseCode());

var_dump('OrderNumber', $response->getOrderNumber());
var_dump('OrderStatus', $response->getOrderStatus());

var_dump($response);
print '</pre>';

<?php

use AlfaAcquiring\Api\OrderStatusMethod;
use AlfaAcquiring\RbsClient;

require __DIR__ . '/../vendor/autoload.php';

$apiClient = (new RbsClient('test-api', 'test'))
    ->enableTestMode(); // for debug only

$orderIdExample = '570116f7-2588-768a-93a4-8b300007a120';

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

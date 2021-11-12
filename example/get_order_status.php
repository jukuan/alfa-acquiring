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
var_dump($response->getOrderNumber());
var_dump($response->getOrderStatus());
var_dump($response);
print '</pre>';

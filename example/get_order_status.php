<?php

use AlfaAcquiring\Api\OrderStatusMethod;
use AlfaAcquiring\RbsClient;

require __DIR__ . '/../vendor/autoload.php';

$orderIdExample = '570116f7-2588-768a-93a4-8b300007a120';

$apiClient = (new RbsClient('test-api', 'test'))
    ->enableTestMode(); // for debug only

// the old way
$orderStatusResponse = $apiClient->getOrderStatus($orderIdExample);

// the new way
$response = (new OrderStatusMethod($apiClient))->setOrderId($orderIdExample)->run();

print '<pre>';
var_dump($orderStatusResponse->getOrderNumber());
var_dump($orderStatusResponse->getOrderStatus());
var_dump($orderStatusResponse);
print '</pre>';

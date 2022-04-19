<?php

use AlfaAcquiring\Api\RegisterOrderMethod;
use AlfaAcquiring\Model\Order;
use AlfaAcquiring\RbsClient;

require __DIR__ . '/../vendor/autoload.php';

/** @var RbsClient $rbsClient */
$rbsClient = require '_rbs_client.php';
$apiClient = $rbsClient
    ->enableTestMode() // for debug only
;

//$order = new Order(1863); // simple order, no client data
$order = Order::forCustomer(1863, 'Kastus@Kalinowsky.by', '+375290186300')
    ->generateOrderNumberAsDate()
    ->setReturnUrl('https://google.by');

// the old way
//$response = $apiClient->registerOrder($order);

// the new way
$response = (new RegisterOrderMethod($apiClient))
    ->enableOneStagePayment()
    ->setClientId('cl123')
    ->setOrder($order)
    ->run();

print '<pre>';
var_dump($response->getOrderId());
var_dump($response->getFormUrl());
var_dump($response);
print '</pre>';

<?php

use AlfaAcquiring\Model\Order;
use AlfaAcquiring\RbsClient;

require __DIR__ . '/../vendor/autoload.php';

$apiClient = new RbsClient('test-api', 'test');
$apiClient->enableTestMode(); // for debug only

//$order = new Order(1863); // simple order, no client data
$order = Order::forCustomer(1863, 'Kastus@Kalinowsky.by', '+375290186300');
$order->setReturnUrl('https://google.by');
$response = $apiClient->registerOrder($order);

print '<pre>';
var_dump($response->getOrderId());
var_dump($response->getFormUrl());
var_dump($response);
print '</pre>';

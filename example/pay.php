<?php

require __DIR__ . '/../vendor/autoload.php';

$order = new \AlfaAcquiring\Model\Order(1863);
$handle = new \AlfaAcquiring\RbsClient('demo-user', 'dummy-password');
$response = $handle->registerOrder($order);

print '<pre>';
var_dump($response);
print '</pre>';

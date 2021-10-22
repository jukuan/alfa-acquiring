<?php

require __DIR__ . '/../vendor/autoload.php';

$order = new \AlfaAcquiring\Model\Order(1863);
$handle = new \AlfaAcquiring\RbsClient('test-api', 'test');
$response = $handle->registerOrder($order);

print '<pre>';
var_dump($response);
print '</pre>';

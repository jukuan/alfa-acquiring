<?php

use AlfaAcquiring\Api\GetBindingIdMethod;
use AlfaAcquiring\RbsClient;

require __DIR__ . '/../vendor/autoload.php';

/** @var RbsClient $rbsClient */
$rbsClient = require '_rbs_client.php';
$apiClient = $rbsClient
    ->enableTestMode() // for debug only
;

$response = (new GetBindingIdMethod($apiClient))
    ->setClientId('cl123')
    ->run();

print '<pre>';
var_dump($response->getErrorMessage());
var_dump($response->getAllFields());
echo '<hr/>';
var_dump($response->getBindingId());
var_dump($response->getExpiryDate());
print '</pre>';

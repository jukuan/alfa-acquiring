<?php

use AlfaAcquiring\Api\LastOrdersForMerchantsMethod;
use AlfaAcquiring\RbsClient;

require __DIR__ . '/../vendor/autoload.php';

$apiClient = (new RbsClient('test-api', 'test'))
    ->enableTestMode(); // for debug only

$response = (new LastOrdersForMerchantsMethod($apiClient))
    ->setFrom(new \DateTime('-1 day'))
    ->run();

print '<pre>';
var_dump($response->getAllFields());
print '</pre>';

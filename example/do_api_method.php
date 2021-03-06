<?php

use AlfaAcquiring\RbsClient;

require __DIR__ . '/../vendor/autoload.php';

/** @var RbsClient $rbsClient */
$rbsClient = require '_rbs_client.php';
$apiClient = $rbsClient
    ->enableTestMode() // for debug only
;

// execute custom API method
$result = $apiClient->doMethod('', $params = []);

if ($result) {
    $responseFields = $apiClient->getResponseFields();
    $responseAsObj = $apiClient->getResponse();

    print '<pre>';
    var_dump($responseFields);
    var_dump($responseAsObj);
    print '</pre>';
} else {
    $errorMsg = $apiClient->getErrorMessage();

    print '<pre>';
    var_dump($errorMsg);
    print '</pre>';
}

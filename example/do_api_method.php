<?php

use AlfaAcquiring\RbsClient;

require __DIR__ . '/../vendor/autoload.php';

$apiClient = new RbsClient('test-api', 'test');
$apiClient->enableTestMode(); // for debug only

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

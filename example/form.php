<?php

use AlfaAcquiring\RbsClient;
use AlfaAcquiring\RequestHandler\OrderRegisterFormHandler;

require __DIR__ . '/../vendor/autoload.php';

$formHandler = new OrderRegisterFormHandler(
    new RbsClient('demo-user', 'dummy-password')
);
//$formHandler->configureInputNames([//...]);

if ($formHandler->processPostRequest()) {
    $orderId = $formHandler->getResponseOrderId();

    // save the $orderId

    $formHandler->doRedirect(); // redirect to the payment gateway url
}

$formHandler->getErrorMessage(); // handle errors

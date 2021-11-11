<?php

use AlfaAcquiring\RbsClient;
use AlfaAcquiring\RequestHandler\OrderRegisterFormHandler;

require __DIR__ . '/../vendor/autoload.php';

$formHandler = (new OrderRegisterFormHandler(
    (new RbsClient('test-api', 'test'))
        ->enableTestMode() // for debug only
))->setReturnUrl('https://google.by');

if ($formHandler->processPostRequest()) {
    $orderId = $formHandler->getResponseOrderId();

    // save the $orderId

    //$formHandler->doRedirect(); // redirect to the payment gateway url
}

if ($formHandler->isPostRequest()) {
    $formHandler->getErrorMessage(); // handle errors
}
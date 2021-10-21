<?php

declare(strict_types=1);

namespace AlfaAcquiring\RequestHandler;

use AlfaAcquiring\HttpRequest\OrderRegisterRequest;
use AlfaAcquiring\Model\Order;
use AlfaAcquiring\RbsClient;
use AlfaAcquiring\Response\OrderRegistration;
use DomainException;
use Exception;

class OrderRegisterFormHandler
{
    private RbsClient $rbsClient;

    private ?Exception $error = null;

    private ?OrderRegistration $response = null;

    public function __construct(RbsClient $rbsClient)
    {
        $this->rbsClient = $rbsClient;
    }

    public function processPostRequest(): bool
    {
        if (!$this->isPostRequest()) {
            return false;
        }

        $request = new OrderRegisterRequest();

        return $this->processRequest($request) &&
            $this->hasValidResponse();
    }

    public function getErrorMessage(): string
    {
        if (null === $this->error) {
            return '';
        }

        return $this->error->getMessage();
    }

    private function isPostRequest(): bool
    {
        return 'POST' === strtoupper(($_SERVER['REQUEST_METHOD'] ?? ''));
    }

    private function processRequest(OrderRegisterRequest $request): bool
    {
        $amount = $request->getAmount();
        $email = $request->getEmail();
        $phone = $request->getPhone();

        $order = Order::forCustomer($amount, $email, $phone);

        if (!$order->isValid()) {
            // TODO:
            $this->error = new Exception('Order is invalid');

            return false;
        }

        $this->response = $this->rbsClient->registerOrder($order);

        return $this->hasValidResponse();
    }

    private function hasValidResponse(): bool
    {
        return null !== $this->response && $this->response->isValid();
    }

    public function getResponseOrderId(): string
    {
        if (null === $this->response) {
            return '';
        }

        return $this->response->getOrderId();
    }

    public function getResponseRedirectUrl(): string
    {
        if (null === $this->response) {
            return '';
        }

        return $this->response->getFormUrl();
    }

    public function doRedirect(): void
    {
        $url = $this->getResponseRedirectUrl();

        if (0 === strlen($url)) {
            throw new DomainException('Undefined error around redirect URL from bank.');
        }

        header('Location: ' . $url);
        die();
    }
}

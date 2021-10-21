<?php

declare(strict_types=1);

namespace AlfaAcquiring\RequestHandler;

use AlfaAcquiring\Model\Order;
use AlfaAcquiring\RbsClient;
use AlfaAcquiring\Response\OrderRegistration;
use DomainException;
use Exception;

class OrderRegisterFormHandler
{
    private const DEFAULT_FIELD_AMOUNT = 'amount';
    private const DEFAULT_FIELD_EMAIL = 'email';
    private const DEFAULT_FIELD_PHONE = 'phone';

    private RbsClient $rbsClient;

    private ?Exception $error = null;

    private ?OrderRegistration $response = null;

    /**
     * @var string[]
     */
    private array $inputFields = [
        'amount' => self::DEFAULT_FIELD_AMOUNT,
        'email' => self::DEFAULT_FIELD_EMAIL,
        'phone' => self::DEFAULT_FIELD_PHONE,
    ];

    public function __construct(RbsClient $rbsClient)
    {
        $this->rbsClient = $rbsClient;
    }

    public function processPostRequest(?array $request = null): bool
    {
        if (!$this->isPostRequest()) {
            return false;
        }

        return $this->processRequest($request ?? $_REQUEST) &&
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

    public function configureInputNames(array $fields): OrderRegisterFormHandler
    {
        $this->inputFields = array_merge(
            $this->inputFields,
            $fields
        );

        return $this;
    }

    private function getInputName(string $key): string
    {
        return $this->inputFields[$key] ?? $key;
    }

    private function processRequest(array $fields): bool
    {
        $amount = $fields[$this->getInputName('amount')] ?? 0;
        $email = $fields[$this->getInputName('email')] ?? null;
        $phone = $fields[$this->getInputName('phone')] ?? null;

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

        return $this->response->getOrderId();
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

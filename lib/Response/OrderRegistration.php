<?php

declare(strict_types=1);

namespace AlfaAcquiring\Response;

class OrderRegistration extends BaseResponse
{
    protected string $orderId = '';

    protected string $formUrl = '';

    /**
     * @param array $fields
     */
    public function __construct(array $fields)
    {
        parent::__construct($fields);

        $this->orderId = (string) ($this->response['orderId'] ?? '');
        $this->formUrl = (string) ($this->response['formUrl'] ?? '');
    }

    public static function initialiseFailed(string $errorMsg, int $errorCode = 0): OrderRegistration
    {
        return (new static([]))
            ->setErrorFields([
                'errorMessage' => $errorMsg
            ]);
    }

    public function hasError(): bool
    {
        return null !== $this->error;
    }

    protected function hasOrderId(): bool
    {
        return strlen($this->orderId) > 0;
    }

    protected function hasFormUrl(): bool
    {
        return strlen($this->formUrl) > 0;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function getFormUrl(): string
    {
        return $this->formUrl;
    }

    public function isValid(): bool
    {
        return !$this->hasError() && $this->hasOrderId() && $this->hasFormUrl();
    }
}

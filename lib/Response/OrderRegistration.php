<?php

declare(strict_types=1);

namespace AlfaAcquiring\Response;

class OrderRegistration extends BaseResponse
{
    private string $orderId = '';

    private string $formUrl = '';

    /**
     * @param array<array-key, mixed> $fields
     */
    public function __construct(array $fields)
    {
        parent::__construct($fields);

        $this->orderId = (string) ($fields['orderId'] ?? '');
        $this->formUrl = (string) ($fields['formUrl'] ?? '');
    }

    public static function initialiseFailed(string $errorMsg): OrderRegistration
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

    private function hasOrderId(): bool
    {
        return strlen($this->orderId) > 0;
    }

    private function hasFormUrl(): bool
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

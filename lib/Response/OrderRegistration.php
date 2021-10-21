<?php

declare(strict_types=1);

namespace AlfaAcquiring\Response;

use Exception;

class OrderRegistration
{
    /**
     * @var string[]
     */
    private array $fields;

    private ?Exception $error = null;

    private string $orderId = '';

    private string $formUrl = '';

    /**
     * @param array<array-key, mixed> $fields
     */
    public function __construct(array $fields)
    {
        $this->fields = $fields;

        if (isset($fields['errorCode']) || isset($fields['errorMessage'])) {
            $this->setErrorFields($fields);
        } else {
            $this->orderId = (string) ($fields['orderId'] ?? '');
            $this->formUrl = (string) ($fields['formUrl'] ?? '');
        }
    }

    public static function initialiseFailed(string $errorMsg): OrderRegistration
    {
        return (new OrderRegistration([]))
            ->setErrorFields([
                'errorMessage' => $errorMsg
            ]);
    }

    private function setErrorFields(array $fields): OrderRegistration
    {
        $this->error = new Exception(
            (string) ($fields['errorMessage'] ?? ''),
            (int) ($fields['errorCode'] ?? 0)
        );

        return $this;
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

    /**
     * @deprecated
     * TODO: check if we really need that
     *
     * @return string[]
     */
    public function getResponseFields(): array
    {
        return $this->fields;
    }
}

<?php

declare(strict_types=1);

namespace AlfaAcquiring\Response;

class OrderRegistration
{
    /**
     * @var string[]
     */
    private array $fields;

    private string $error;

    public function __construct(array $fields)
    {
        $this->fields = $fields;

        $code = $fields['errorCode'] ?? '';
        $message = $fields['errorMessage'] ?? '';
    }

    public static function initialiseFailed(string $error): OrderRegistration
    {
        return (new OrderRegistration([]))->setError($error);
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }

    private function setError(string $error): OrderRegistration
    {
        $this->error = $error;

        return $this;
    }
}

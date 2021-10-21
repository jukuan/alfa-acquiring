<?php

declare(strict_types=1);

namespace AlfaAcquiring\HttpRequest;

class OrderRegisterRequest extends BaseRequest
{
    private const DEFAULT_FIELD_AMOUNT = 'amount';
    private const DEFAULT_FIELD_EMAIL = 'email';
    private const DEFAULT_FIELD_PHONE = 'phone';

    /**
     * @var string[]
     */
    protected array $inputNames = [
        'amount' => self::DEFAULT_FIELD_AMOUNT,
        'email' => self::DEFAULT_FIELD_EMAIL,
        'phone' => self::DEFAULT_FIELD_PHONE,
    ];

    public function getEmail(): string
    {
        return $this->getInputName('email');
    }

    public function getPhone(): string
    {
        return $this->getInputName('phone');
    }

    public function getAmount(): int
    {
        return (int) $this->getInputName('amount');
    }
}

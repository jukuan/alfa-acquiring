<?php

declare(strict_types=1);

namespace AlfaAcquiring\HttpRequest;

class OrderRegisterRequest extends BaseRequest
{
    private const DEFAULT_FIELD_AMOUNT = 'amount';
    private const DEFAULT_FIELD_EMAIL = 'email';
    private const DEFAULT_FIELD_NAME = 'name';
    private const DEFAULT_FIELD_PHONE = 'phone';

    private const DEFAULT_FIELD_PRODUCT_NAME = 'product_name';

    /**
     * @var string[]
     */
    protected array $inputNames = [
        'amount' => self::DEFAULT_FIELD_AMOUNT,
        'email' => self::DEFAULT_FIELD_EMAIL,
        'name' => self::DEFAULT_FIELD_NAME,
        'phone' => self::DEFAULT_FIELD_PHONE,
        'product_name' => self::DEFAULT_FIELD_PRODUCT_NAME,
    ];

    public function getAmount(): int
    {
        return (int) $this->getInputValue('amount');
    }

    public function getEmail(): string
    {
        return $this->getInputValue('email');
    }

    public function getName(): string
    {
        return $this->getInputValue('name');
    }

    public function getPhone(): string
    {
        return $this->getInputValue('phone');
    }

    public function getProductName(): string
    {
        return $this->getInputName('product_name');
    }
}

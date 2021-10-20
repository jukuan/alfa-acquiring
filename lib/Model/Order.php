<?php

declare(strict_types=1);

namespace AlfaAcquiring\Model;

class Order
{
    private string $orderNumber = '';

    private int $amount;

    private string $returnUrl = '';

    public function __construct(int $amount)
    {
        $this->amount = $amount > 0 ? $amount : 0;
    }

    public function isValid(): bool
    {
        return $this->amount > 0 && mb_strlen($this->orderNumber) > 0;
    }

    public function getOrderNumber(): string
    {
        return $this->orderNumber;
    }

    public function setUniqueOrder(): Order
    {
        $this->orderNumber = uniqid();

        return $this;
    }

    public function addOrderTimeSuffix(): Order
    {
        $this->orderNumber .= '_' . time();

        return $this;
    }

    /**
     * @param string $orderNumber
     *
     * @return Order
     */
    public function setOrderNumber(string $orderNumber): Order
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     * @return Order
     */
    public function setAmount(int $amount): Order
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return string
     */
    public function getReturnUrl(): string
    {
        return $this->returnUrl;
    }

    /**
     * @param string $returnUrl
     *
     * @return Order
     */
    public function setReturnUrl(string $returnUrl): Order
    {
        $this->returnUrl = $returnUrl;

        return $this;
    }
}

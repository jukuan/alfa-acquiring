<?php

declare(strict_types=1);

namespace AlfaAcquiring\Model;

use AlfaAcquiring\Logger\LoggerTrait;

class Order
{
    use LoggerTrait;

    protected string $orderNumber = '';

    protected int $amount = 0;

    protected string $returnUrl = '';

    protected ?Customer $customer = null;

    public function __construct(int $amount)
    {
        $this->amount = max($amount, 0);
    }

    public static function forCustomer(int $amount, ?string $email, ?string $phone = null): Order
    {
        $order = new Order($amount);

        if (null !== $email || null !== $phone) {
            $customer = new Customer($email, $phone);

            if ($customer->isValid()) {
                $order->setCustomer($customer);
            } else {
                $order->logError('Customer is not valid');
            }
        }

        return $order;
    }

    public function isValid(): bool
    {
        return $this->amount > 0 && strlen($this->returnUrl) > 0;
    }

    public function getOrderNumber(): string
    {
        return $this->orderNumber;
    }

    public function generateUniqueOrderNumber(): Order
    {
        $this->orderNumber = uniqid();

        return $this;
    }

    public function generateOrderNumberAsDate(): Order
    {
        $this->orderNumber = date('Y-m-d H:i:s');

        return $this;
    }

    public function addOrderNumberTimeSuffix(): Order
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

    public function setAmount(int $amount): Order
    {
        $this->amount = $amount;

        return $this;
    }

    public function getReturnUrl(): string
    {
        return $this->returnUrl;
    }

    public function setReturnUrl(string $returnUrl): Order
    {
        $this->returnUrl = $returnUrl;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function hasCustomer(): bool
    {
        return null !== $this->customer;
    }

    public function setCustomer(?Customer $customer): Order
    {
        $this->customer = $customer;

        return $this;
    }

    public function getEmail(): ?string
    {
        if (null === $this->customer) {
            return null;
        }

        return $this->customer->getEmail();
    }

    public function getPhone(): ?string
    {
        if (null === $this->customer) {
            return null;
        }

        return $this->customer->getPhone();
    }
}

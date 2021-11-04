<?php

declare(strict_types=1);

namespace AlfaAcquiring\Model;

use AlfaAcquiring\Logger\LoggerTrait;

class Order
{
    use LoggerTrait;

    private string $orderNumber = '';

    private int $amount;

    private string $returnUrl = '';

    private ?Customer $customer = null;

    public function __construct(int $amount)
    {
        $this->amount = $amount > 0 ? $amount : 0;
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

    public function addOrderNumberTimeSuffix(): Order
    {
        if (0 === mb_strlen($this->orderNumber)) {
            $this->orderNumber = date('Y-m-d H:i');
        }

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

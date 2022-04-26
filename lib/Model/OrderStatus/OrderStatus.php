<?php

declare(strict_types=1);

namespace AlfaAcquiring\Model\OrderStatus;

class OrderStatus extends BaseOrderStatus
{
    protected string $orderLabel = '';

    public function getOrderLabel(): string
    {
        return $this->orderLabel;
    }
}

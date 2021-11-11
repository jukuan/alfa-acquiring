<?php

declare(strict_types=1);

namespace AlfaAcquiring\Api;

use AlfaAcquiring\Response\OrderStatus;

class OrderStatusMethod extends BaseApiMethod
{
    private const METHOD = 'getOrderStatusExtended';
    private const ORDER_FIELD = 'orderId';

    public function setOrderId(string $orderId): self
    {
        $this->params[self::ORDER_FIELD] = $orderId;

        return $this;
    }

    public function hasValidParams(): bool
    {
        return isset($this->params[self::ORDER_FIELD]);
    }

    public function run(): OrderStatus
    {
        if (!$this->hasValidParams()) {
            return OrderStatus::initialiseFailed($this->client->getErrorMessage());
        }

        $result = $this->client->doMethod(self::METHOD, $this->params);

        if (false === $result) {
            return OrderStatus::initialiseFailed($this->client->getErrorMessage());
        }

        return new OrderStatus((array) $this->client->getResponse());
    }
}

<?php

declare(strict_types=1);

namespace AlfaAcquiring\Api;

use AlfaAcquiring\Response\OrderStatus;

class OrderStatusMethod extends BaseApiMethod
{
    private const METHOD = 'getOrderStatusExtended.do';
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
        $error = null;

        if ($this->hasValidParams()) {
            $result = $this->client->doMethod(self::METHOD, $this->params);

            if (!$result) {
                $error = $this->client->getErrorMessage();
            }
        } else {
            $error = 'Order id is not set';
        }

        if (null !== $error) {
            return OrderStatus::initialiseFailed($error);
        }

        return new OrderStatus(
            (array) $this->client->getResponse()
        );
    }
}

<?php

declare(strict_types=1);

namespace AlfaAcquiring\Api;

use AlfaAcquiring\Response\OrderStatusResponse;

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

    public function run(): OrderStatusResponse
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
            return OrderStatusResponse::initialiseFailed($error);
        }

        return new OrderStatusResponse(
            (array) $this->client->getResponse()
        );
    }
}

<?php

declare(strict_types=1);

namespace AlfaAcquiring\Api;

use AlfaAcquiring\Api\Interfaces\PayableInterface;
use AlfaAcquiring\Api\Interfaces\TranslatableInterface;
use AlfaAcquiring\Model\Order;
use AlfaAcquiring\Response\OrderRegistration;

class RegisterOrderMethod extends BaseApiMethod implements PayableInterface, TranslatableInterface
{
    private const PAYMENT_STAGE_ONE = 'one';
    private const PAYMENT_STAGE_TWO = 'two';

    private string $paymentStage = self::PAYMENT_STAGE_ONE;
    private string $language = self::DEFAULT_LANGUAGE;
    private int $currency = self::BYN_CURRENCY;
    private ?Order $order = null;

    private function getMethodName(): string
    {
        return self::PAYMENT_STAGE_TWO == $this->paymentStage ? 'registerPreAuth.do' : 'register.do';
    }

    public function enableTwoStagePayment(): self
    {
        $this->paymentStage = self::PAYMENT_STAGE_TWO;

        return $this;
    }

    public function enableOneStagePayment(): self
    {
        $this->paymentStage = self::PAYMENT_STAGE_TWO;

        return $this;
    }

    public function setOrder(Order $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function hasValidParams(): bool
    {
        return null !== $this->order;
    }

    private function getParamFields(): array
    {
        $fields = [];

        if (null !== $this->order) {
            $fields = [
                'orderNumber' => $this->order->getOrderNumber(),
                'amount' => $this->order->getAmount(),
                'returnUrl' => $this->order->getReturnUrl(),
                'email' => $this->order->getEmail(),
                'phone' => $this->order->getPhone(),
            ];
        }

        $fields['currency'] = $this->getCurrency();

        return array_filter($fields, static function ($value) {
            return 0 !== $value && null !== $value && '' !== $value;
        });
    }

    public function getCurrency(): int
    {
        return $this->currency ?: self::BYN_CURRENCY;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function run(): OrderRegistration
    {
        $error = null;

        if ($this->hasValidParams()) {
            $result = $this->client->doMethod(
                $this->getMethodName(),
                $this->getParamFields()
            );

            if (!$result) {
                $error = $this->client->getErrorMessage();
            }
        } else {
            $error = 'Order object is not set';
        }

        if (null !== $error) {
            return OrderRegistration::initialiseFailed($error);
        }

        return new OrderRegistration(
            (array) $this->client->getResponse()
        );
    }
}

<?php

declare(strict_types=1);

namespace AlfaAcquiring\Model\OrderStatus;

class OrderStatusBel extends OrderStatus
{
    public function __construct(int $orderStatus)
    {
        parent::__construct($orderStatus);
        $this->detectOrderLabel();
    }

    protected function detectOrderLabel(): string
    {
        $this->orderLabel = match ($this->orderStatus) {
            self::STATUS_REGISTERED => 'Зарэгістраваны',
            self::STATUS_ON_HOLD => 'У чаканні',
            self::STATUS_WHOLE_AUTHORISED => 'Праведзена поўная аўтарызацыя',
            self::STATUS_CANCELED => 'Аўтарызацыя адменена',
            self::STATUS_RETURNED => 'Вернуты',
            self::STATUS_ACS_INITIALISED => 'Ініцыявана ACS аўтарызацыя',
            self::STATUS_DECLINED => 'Аўтарызацыя адхілена',
        };
    }
}

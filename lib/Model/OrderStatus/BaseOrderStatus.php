<?php

declare(strict_types=1);

namespace AlfaAcquiring\Model\OrderStatus;

abstract class BaseOrderStatus
{
    protected const STATUS_REGISTERED = 0;            // Заказ зарегистрирован, но не оплачен;
    protected const STATUS_ON_HOLD = 1;               // Предавторизованная сумма захолдирована (для двухстадийных платежей);
    protected const STATUS_WHOLE_AUTHORISED = 2;      // Проведена полная авторизация суммы заказа;
    protected const STATUS_CANCELED = 3;              // Авторизация отменена;
    protected const STATUS_RETURNED = 4;              // По транзакции была проведена операция возврата;
    protected const STATUS_ACS_INITIALISED = 5;       // Инициирована авторизация через ACS банка-эмитента;
    protected const STATUS_DECLINED = 6;              // Авторизация отклонена.

    protected int $orderStatus;

    public function __construct(int $orderStatus)
    {
        $this->orderStatus = $orderStatus;
    }

    public function isSuccess(): bool
    {
        return self::STATUS_WHOLE_AUTHORISED === $this->orderStatus;
    }

    public function isProcessed(): bool
    {
        return in_array($this->orderStatus, [self::STATUS_ON_HOLD, self::STATUS_WHOLE_AUTHORISED], true);
    }

    public function isInProgress(): bool
    {
        return in_array($this->orderStatus, [
            self::STATUS_REGISTERED,
            self::STATUS_ON_HOLD,
            self::STATUS_ACS_INITIALISED,
        ], true);
    }
}

<?php

declare(strict_types=1);

namespace AlfaAcquiring\Response;

class OrderStatus extends BaseResponse
{
    private string $orderNumber;
    private int $orderStatus;
    private int $actionCode;
    private int $amount; // TODO: check if is that really always integer?
    private int $date;

//    private string $orderDescription = '';
//    private array $merchantOrderParams = [];
//    private array $transactionAttributes = [];

    /**
     * @param array<array-key, mixed> $fields
     */
    public function __construct(array $fields)
    {
        parent::__construct($fields);

        $this->orderNumber = (string) ($fields['orderNumber'] ?? '');
        $this->orderStatus = (int) ($fields['orderStatus'] ?? 0);
        $this->actionCode = (int) ($fields['actionCode'] ?? 0);
        $this->amount = (int) ($fields['amount'] ?? 0);
        $this->date = (int) ($fields['date'] ?? 0);
    }

    public static function initialiseFailed(string $errorMsg): OrderStatus
    {
        return (new static([]))
            ->setErrorFields([
                'errorMessage' => $errorMsg
            ]);
    }

    public function getOrderNumber(): string
    {
        return $this->orderNumber;
    }

    public function getOrderStatus(): int
    {
        return $this->orderStatus;
    }

    public function getActionCode(): int
    {
        return $this->actionCode;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getDate(): int
    {
        return $this->date;
    }

    public function isValid(): bool
    {
        if (!parent::isValid()) {
            return false;
        }

        return mb_strlen($this->orderNumber) > 0;
    }
}
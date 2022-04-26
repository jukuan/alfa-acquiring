<?php

declare(strict_types=1);

namespace AlfaAcquiring\Response;

class OrderStatusResponse extends BaseResponse
{
    protected string $orderNumber;
    protected int $orderStatus;
    protected int $actionCode;
    protected int $amount; // TODO: check if is that really always integer?
    protected int $date;

//    private string $orderDescription = '';
//    private array $merchantOrderParams = [];
//    private array $transactionAttributes = [];

    /**
     * @param array $fields
     */
    public function __construct(array $fields)
    {
        parent::__construct($fields);

        $this->orderNumber = (string) ($this->response['orderNumber'] ?? '');
        $this->orderStatus = (int) ($this->response['orderStatus'] ?? 0);
        $this->actionCode = (int) ($this->response['actionCode'] ?? 0);
        $this->amount = (int) ($this->response['amount'] ?? 0);
        $this->date = (int) ($this->response['date'] ?? 0);
    }

    public static function initialiseFailed(string $errorMsg, int $errorCode = 0): OrderStatusResponse
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

    public function getCartAuthInfo(): array
    {
        return $this->response['cardAuthInfo'] ?? [];
    }

    public function getBindingInfo(): array
    {
        return $this->response['bindingInfo'] ?? [];
    }

    public function getPaymentAmountInfo(): array
    {
        return $this->response['paymentAmountInfo'] ?? [];
    }
}

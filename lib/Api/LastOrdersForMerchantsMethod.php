<?php

declare(strict_types=1);

namespace AlfaAcquiring\Api;

use AlfaAcquiring\Response\LastOrdersForMerchants;
use AlfaAcquiring\Response\BaseResponse;
use DateTimeInterface;

class LastOrdersForMerchantsMethod extends BaseApiMethod
{
    private const METHOD = 'getLastOrdersForMerchants.do';

    private const SIZE_FIELD = 'size';
    private const FROM_FIELD = 'from';
    private const TO_FIELD = 'to';
    private const TRANSACTIONS_FIELD = 'transactionStates';
    private const MERCHANTS_FIELD = 'merchants';

    private const DEFAULT_PAGE = 0;
    private const MAX_SIZE = 200;

    private int $page = self::DEFAULT_PAGE;
    private int $size = self::MAX_SIZE;
    private ?DateTimeInterface $from = null;
    private ?DateTimeInterface $to = null;

    /**
     * @var string[]
     */
    private array $transactionStates = [];

    /**
     * @var string[]
     */
    private array $merchants = [];

    private const TRANSACTION_APPROVED = 'APPROVED';
    private const TRANSACTION_CREATED = 'CREATED';
    private const TRANSACTION_DECLINED = 'DECLINED';
    private const TRANSACTION_DEPOSITED = 'DEPOSITED';
    private const TRANSACTION_REVERSED = 'REVERSED';
    private const TRANSACTION_REFUNDED = 'REFUNDED';
    private const TRANSACTION_LIST = [
        self::TRANSACTION_APPROVED,
        self::TRANSACTION_CREATED,
        self::TRANSACTION_DECLINED,
        self::TRANSACTION_DEPOSITED,
        self::TRANSACTION_REVERSED,
        self::TRANSACTION_REFUNDED,
    ];

    public function hasValidParams(): bool
    {
        return
            isset($this->params[self::FROM_FIELD]) &&
            isset($this->params[self::TO_FIELD]);
    }

    /**
     * @param int $page
     *
     * @return LastOrdersForMerchantsMethod
     */
    public function setPage(int $page): LastOrdersForMerchantsMethod
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @param int $size
     *
     * @return LastOrdersForMerchantsMethod
     */
    public function setSize(int $size): LastOrdersForMerchantsMethod
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @param DateTimeInterface|null $from
     *
     * @return LastOrdersForMerchantsMethod
     */
    public function setFrom(?DateTimeInterface $from): LastOrdersForMerchantsMethod
    {
        if (null !== $from) {
            $this->from = clone $from;
        }

        return $this;
    }

    /**
     * @param DateTimeInterface|null $to
     *
     * @return LastOrdersForMerchantsMethod
     */
    public function setTo(?DateTimeInterface $to): LastOrdersForMerchantsMethod
    {
        if (null !== $to) {
            $this->to = clone $to;
        }

        return $this;
    }

    public function addTransactionState(string $transactionState): LastOrdersForMerchantsMethod
    {
        if (isset(self::TRANSACTION_LIST[$transactionState])) {
            $this->transactionStates[] = $transactionState;
        }

        return $this;
    }

    public function addMerchant(string $merchantLogin): LastOrdersForMerchantsMethod
    {
        $this->merchants[] = $merchantLogin;

        return $this;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    private function getSize(): int
    {
        return $this->size > 0 ? $this->size : self::MAX_SIZE;
    }

    private function getFrom(): DateTimeInterface
    {
        if ($this->from === null) {
            return new \DateTime('-7 days ago');
        }

        return $this->from;
    }

    /**
     * @return DateTimeInterface
     */
    private function getTo(): DateTimeInterface
    {
        if ($this->to === null) {
            return new \DateTime();
        }

        return $this->to;
    }

    /**
     * @return string[]
     */
    private function getTransactionStates(): array
    {
        if (count($this->transactionStates) === 0) {
            return [self::TRANSACTION_APPROVED];
        }

        return array_unique($this->transactionStates);
    }

    /**
     * @return string[]
     */
    private function getMerchants(): array
    {
        if (count($this->merchants) === 0) {
            return [];
        }

        return array_unique($this->merchants);
    }

    public function calculateParams(): array
    {
        $params = [
            'size' => $this->getSize(),
            'from' => $this->getFrom()->format('c'),
            'to' => $this->getTo()->format('c'),
            'transactionStates' => $this->getTransactionStates(),
            'merchants' => $this->getMerchants(),
        ];

        if ($this->page > 0) {
            $params['page'] = $this->page;
        }

        return $params;
    }

    public function run(): BaseResponse
    {
        $this->params = $this->calculateParams();

        $error = null;

        if ($this->hasValidParams()) {
            $result = $this->client->doMethod(self::METHOD, $this->params);

            if (!$result) {
                $error = $this->client->getErrorMessage();
            }
        } else {
            $error = 'Parameters are invalid';
        }

        if (null !== $error) {
            return LastOrdersForMerchants::initialiseFailed($error);
        }

        return new LastOrdersForMerchants(
            (array) $this->client->getResponse()
        );
    }
}

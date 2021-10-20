<?php

declare(strict_types=1);

namespace AlfaAcquiring;

use AlfaAcquiring\HttpClient\CurlClient;

class RbsClient
{
    private const LIB_VERSION = '1.0.1';
    private const HTTP_HEADERS = [
        'CMS: AlfaAcquiring',
        'Module-Version: ' . self::LIB_VERSION
    ];

    private const ENDPOINT_PROD = 'https://ecom.alfabank.by/payment/rest/';
    private const ENDPOINT_TEST = 'https://web.rbsuat.com/ab_by/rest/';

    private const BYN_CURRENCY = 933;
    private const DEFAULT_MEASUREMENT = 'шт';

    private const LANGUAGE_BE = 'by';
    private const LANGUAGE_EN = 'en';
    private const LANGUAGE_RU = 'ru';
    private const LANGUAGES = [
        self::LANGUAGE_BE,
        self::LANGUAGE_EN,
        self::LANGUAGE_RU,
    ];
    private const DEFAULT_LANGUAGE = self::LANGUAGE_RU;

    private const PAYMENT_STAGE_ONE = 'one';
    private const PAYMENT_STAGE_TWO = 'two';
    private const PAYMENT_STAGES = [
        self::PAYMENT_STAGE_ONE,
        self::PAYMENT_STAGE_TWO,
    ];

    private bool $isTestMode = false;
    private string $paymentStage = self::PAYMENT_STAGE_ONE;
    private string $language = self::DEFAULT_LANGUAGE;
    private int $currency = self::BYN_CURRENCY;
    private CurlClient $client;
    private string $errorMessage = '';
    private string $login;
    private string $password;

    public function __construct(string $login, string $password)
    {
        $this->login = $login;
        $this->password = $password;
        $this->client = (new CurlClient())->setHttpHeaders(self::HTTP_HEADERS);
    }

    public function setLanguage(string $lang): RbsClient
    {
        if (in_array($lang, self::LANGUAGES, true)) {
            $this->language = $lang;
        }

        return $this;
    }

    public function setTimeout(int $timeout): RbsClient
    {
        $this->client->setTimeout($timeout);

        return $this;
    }

    private function reset(): void
    {
        $this->errorMessage = '';
    }

    public function getError(): string
    {
        return $this->errorMessage;
    }

    public function doMethod(string $method, array $params = []): bool
    {
        $this->reset();

        $url = $this->isTestMode ? self::ENDPOINT_TEST : self::ENDPOINT_PROD;
        $method = $url . $method;

        $params['userName'] = $this->login;
        $params['password'] = $this->password;
        $params['language'] = $this->language ?: self::DEFAULT_LANGUAGE;

        $this->client->execute($method, $params);

        if ($this->client->hasError()) {
            $this->errorMessage = $this->client->getErrorDetails();
            return false;
        }

        return true;
    }

    public function getOrderStatus(string $orderId): array
    {
        if (!$this->doMethod('getOrderStatusExtended.do', ['orderId' => $orderId])) {
            return [];
        }

        return (array) $this->client->getResponse();
    }

    public function registerOrder(string $order_number, int $amount, string $return_url = ''): array
    {
        $fields = [
            'orderNumber' => $order_number . '_'. time(),
            'amount' => $amount,
            'returnUrl' => $return_url,
            'jsonParams' => json_encode(self::HTTP_HEADERS),
        ];

        if ($this->currency > 0) {
            $fields['currency'] = $this->currency;
        }

        $method = self::PAYMENT_STAGE_TWO == $this->paymentStage ? 'registerPreAuth.do' : 'register.do';

        if (!$this->doMethod($method, $fields)) {
            return [];
        }

        return (array) $this->client->getResponse();
    }
}

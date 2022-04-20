<?php

declare(strict_types=1);

namespace AlfaAcquiring;

use AlfaAcquiring\HttpClient\CurlClient;
use AlfaAcquiring\HttpClient\HttpRequestInterface;
use AlfaAcquiring\Model\Order;
use AlfaAcquiring\Response\BaseResponse;
use AlfaAcquiring\Response\OrderRegistration;
use AlfaAcquiring\Response\OrderStatusResponse;

class RbsClient
{
    private const LIB_VERSION = '1.0.2';
    private const HTTP_HEADERS = [
        'CMS: AlfaAcquiring',
        'Module-Version: ' . self::LIB_VERSION
    ];

    private const ENDPOINT_PROD = 'https://ecom.alfabank.by/payment/rest/';
    private const ENDPOINT_TEST = 'https://web.rbsuat.com/ab_by/rest/';

    public const BYN_CURRENCY = 933;
    public const EUR_CURRENCY = 978;
    public const USD_CURRENCY = 840;
    public const RUR_CURRENCY = 643;

    public const CURRENCIES = [
        'BYN' => self::BYN_CURRENCY,
        'EUR' => self::EUR_CURRENCY,
        'USD' => self::USD_CURRENCY,
        'RUR' => self::RUR_CURRENCY
    ];

    /**
     * Language codes in "ISO 639-1" format.
     * https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
     */
    private const LANGUAGE_BE = 'be';
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
    private HttpRequestInterface $client;
    private string $errorMessage = '';
    private string $login;
    private string $password;

    public function __construct(
        string $login,
        string $password,
        ?HttpRequestInterface $client = null
    ) {
        $this->login = $login;
        $this->password = $password;

        if (null === $client) {
            $client = new CurlClient();
        }

        $this->client = $client->setHttpHeaders(self::HTTP_HEADERS);
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

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    private function prepareMethodUrl(string $method): string
    {
        $url = $this->isTestMode ? self::ENDPOINT_TEST : self::ENDPOINT_PROD;

        return $url . $method;
    }

    private function prepareRequestUrl(string $method): string
    {
        return str_replace('/rest/', '/', $this->prepareMethodUrl($method));
    }

    public function doMethod(string $method, array $params = [], bool $isRest = true): bool
    {
        $this->reset();

        $params['userName'] = $this->login;
        $params['password'] = $this->password;

        if (!isset($params['language'])) {
            $params['language'] = strlen($this->language) > 0 ? $this->language : self::DEFAULT_LANGUAGE;
        }

        $url = $isRest ? $this->prepareMethodUrl($method) : $this->prepareRequestUrl($method);

        $this->client->execute($url, $params);

        if ($this->client->hasError()) {
            $this->errorMessage = $this->client->getErrorDetails();

            return false;
        }

        return true;
    }

    public function doRequest(string $method, array $params = []): bool
    {
        return $this->doMethod($method, $params, false);
    }

    public function doRest(string $method, array $params = []): bool
    {
        return $this->doMethod($method, $params, true);
    }

    public function getResponseFields(): array
    {
        return (array) $this->client->getDecodedResponse();
    }

    public function getResponse(): BaseResponse
    {
        return (new BaseResponse($this->getResponseFields()));
    }

    /**
     * @deprecated
     * Use OrderStatusMethod{} instead
     *
     * @param string $orderId
     * @return OrderStatusResponse
     */
    public function getOrderStatus(string $orderId): OrderStatusResponse
    {
        if (!$this->doMethod('getOrderStatusExtended.do', ['orderId' => $orderId])) {
            return OrderStatusResponse::initialiseFailed($this->errorMessage);
        }

        return new OrderStatusResponse((array) $this->client->getDecodedResponse());
    }

    /**
     * @deprecated
     * Use RegisterOrderMethod{} instead
     *
     * @return string
     */
    private function getOrderRegisterMethod(): string
    {
        return self::PAYMENT_STAGE_TWO == $this->paymentStage ? 'registerPreAuth.do' : 'register.do';
    }

    /**
     * @deprecated
     * Use RegisterOrderMethod instead
     *
     * @param Order $order
     * @return OrderRegistration
     */
    public function registerOrder(Order $order): OrderRegistration
    {
        $fields = [
            'orderNumber' => $order->getOrderNumber(),
            'amount' => $order->getAmount(),
            'returnUrl' => $order->getReturnUrl(),
            'email' => $order->getEmail(),
            'phone' => $order->getPhone(),
            'currency' => $this->currency,
        ];

        $fields = array_filter($fields, static function ($value) {
            return 0 !== $value && null !== $value && '' !== $value;
        });

        if (!$this->doMethod($this->getOrderRegisterMethod(), $fields)) {
            return OrderRegistration::initialiseFailed($this->errorMessage);
        }

        return new OrderRegistration((array) $this->client->getDecodedResponse());
    }

    public function enableTestMode(): RbsClient
    {
        $this->isTestMode = true;

        return $this;
    }

    public function getLastQuery(): string
    {
        return $this->client->getLastQuery();
    }

    public function getHttpResponseCode(): int
    {
        return $this->client->getHttpResponseCode();
    }
}

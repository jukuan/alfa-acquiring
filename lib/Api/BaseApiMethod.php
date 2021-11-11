<?php

declare(strict_types=1);

namespace AlfaAcquiring\Api;

use AlfaAcquiring\RbsClient;
use AlfaAcquiring\Response\BaseResponse;

abstract class BaseApiMethod
{
    protected RbsClient $client;

    protected array $params = [];

    public function __construct(RbsClient $client)
    {
        $this->client = $client;
    }

    public function enableTestMode(): void
    {
        $this->client->enableTestMode();
    }

    public static function create(string $rbsLogin, string $rbsPassword): BaseApiMethod
    {
        $client = new RbsClient($rbsLogin, $rbsPassword);

        return new static($client);
    }

    public function setParams(array $params): BaseApiMethod
    {
        $this->params = $params;

        return $this;
    }

    public function addParam(string $name, $value): BaseApiMethod
    {
        $this->params[$name] = $value;

        return $this;
    }

    abstract public function hasValidParams(): bool;

    abstract public function run(): BaseResponse;
}

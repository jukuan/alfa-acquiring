<?php

declare(strict_types=1);

namespace AlfaAcquiring\Api;

use AlfaAcquiring\Response\GetBindingsIdResponse;

class GetBindingIdMethod extends BaseApiMethod
{
    private const METHOD = 'getBindings.do';
    private const CLIENT_FIELD = 'clientId';

    public function setClientId(string $clientId): self
    {
        $this->params[self::CLIENT_FIELD] = $clientId;

        return $this;
    }

    public function hasValidParams(): bool
    {
        return isset($this->params[self::CLIENT_FIELD]);
    }

    public function run(): GetBindingsIdResponse
    {
        $error = null;

        if ($this->hasValidParams()) {
            $result = $this->client->doMethod(self::METHOD, $this->params);

            if (!$result) {
                $error = $this->client->getErrorMessage();
            }
        } else {
            $error = 'Client id is not set';
        }

        if (null !== $error) {
            return GetBindingsIdResponse::initialiseFailed($error);
        }

        return new GetBindingsIdResponse(
            (array) $this->client->getResponse()
        );
    }
}

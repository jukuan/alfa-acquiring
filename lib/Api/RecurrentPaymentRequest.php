<?php

declare(strict_types=1);

namespace AlfaAcquiring\Api;

use AlfaAcquiring\Response\BaseResponse;

class RecurrentPaymentRequest extends BaseApiMethod
{
    private const METHOD = 'recurrentPayment.do';

    public function hasValidParams(): bool
    {
        return true;
    }

    public function run(): BaseResponse
    {
        $error = null;

        if ($this->hasValidParams()) {
            $result = $this->client->doRequest(self::METHOD, $this->params);

            if (!$result) {
                $error = $this->client->getErrorMessage();
            }
        } else {
            $error = 'Invalid params';
        }

        if (null !== $error) {
            return BaseResponse::initialiseFailed($error);
        }

        return new BaseResponse(
            (array) $this->client->getResponse()
        );
    }
}

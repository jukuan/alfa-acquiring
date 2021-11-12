<?php

declare(strict_types=1);

namespace AlfaAcquiring\HttpClient;

use Exception;
use Throwable;

class CurlException extends Exception
{
    public static function create(Throwable $throwable): CurlException
    {
        return new self(
            $throwable->getMessage(),
            $throwable->getCode(),
            $throwable
        );
    }

    /**
     * @param string $message
     * @param resource|null $curlHandle
     *
     * @return CurlException
     */
    public static function generate(string $message, $curlHandle): CurlException
    {
        $previous = new Exception($message);

        if (null === $curlHandle) {
            return new CurlException('CurlHandle is not defined', 0, $previous);
        }

        return new CurlException(curl_error($curlHandle), curl_errno($curlHandle), $previous);
    }

    public function getDetails(): string
    {
        $msg = sprintf('CurlError with code %d: %s', (int) $this->getCode(), $this->getMessage());

        if (null !== $this->getPrevious()) {
            $msg .= sprintf('. %s.', $this->getPrevious()->getMessage());
        }

        return $msg;
    }
}

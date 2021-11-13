<?php

declare(strict_types=1);

namespace AlfaAcquiring\HttpClient;

use Exception;

class CurlClient implements HttpRequestInterface
{
    private int $timeout = 0;

    private ?CurlException $exception = null;

    /**
     * @param null|resource
     */
    private $ch = null;

    /**
     * @var mixed
     */
    private $decodedResponse = null;

    /**
     * @var string[]
     */
    private array $httpHeaders = [];

    private function buildCurlOptions(string $url, array $postFields = []): array
    {
        $options = [
            CURLOPT_VERBOSE => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
        ];

        if ($this->timeout > 0) {
            $options[CURLOPT_TIMEOUT] = $this->timeout;
        }

        if (count($postFields) > 0) {
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = http_build_query($postFields, '', '&');
        }

        if (count($this->httpHeaders) > 0) {
            $options[CURLOPT_HTTPHEADER] = $this->httpHeaders;
        }

        return $options;
    }

    private function decodeResponse(?string $response): void
    {
        if (null !== $response && '' !== $response) {
            try {
                $this->decodedResponse = json_decode($response, true) ?: null;
            } catch (Exception $exception) {
                $this->exception = CurlException::create($exception);
            }
        }
    }

    public function execute(string $url, array $postFields = []): CurlClient
    {
        $this->initialise();

        $options = $this->buildCurlOptions($url, $postFields);

        if (!curl_setopt_array($this->ch, $options)) {
            $this->setError('Cannot set curl options', $this->ch);
        }

        $response = $this->getHttpResponse();

        if (is_string($response)) {
            $this->decodeResponse($response);
        } else {
            $this->setError('Bad response', $this->ch);
        }

        $this->close();

        return $this;
    }

    /**
     * @return CurlException|null
     */
    public function getException(): ?CurlException
    {
        return $this->exception;
    }

    public function getErrorMessage(): string
    {
        if (null === $this->exception) {
            return '';
        }

        return $this->exception->getMessage();
    }

    public function getHttpResponse(): ?string
    {
        return curl_exec($this->ch) ?: null;
    }

    /**
     * @return mixed
     */
    public function getDecodedResponse()
    {
        if ($this->hasError()) {
            return null;
        }

        return $this->decodedResponse;
    }

    public function setTimeout(int $timeout): CurlClient
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * @param string $msg
     *
     * @param resource $handle
     */
    private function setError(string $msg, $handle = null): void
    {
        $this->exception = CurlException::generate($msg, $handle);
    }

    public function getErrorDetails(): string
    {
        if (null === $this->exception) {
            return '';
        }

        return $this->exception->getDetails();
    }

    public function hasError(): bool
    {
        return null !== $this->exception;
    }

    /**
     * @param string[] $httpHeaders
     *
     * @return CurlClient
     */
    public function setHttpHeaders(array $httpHeaders): CurlClient
    {
        $this->httpHeaders = $httpHeaders;

        return $this;
    }

    public function setOption(int $name, $value): void
    {
        curl_setopt($this->ch, $name, $value);
    }

    public function getInfo(?int $name)
    {
        return curl_getinfo($this->ch, $name);
    }

    public function initialise(): void
    {
        $handle = curl_init();

        if (false === $handle) {
            $this->setError('Cannot initialise curl');
        } else {
            $this->ch = $handle;
        }
    }

    public function close(): void
    {
        if (null !== $this->ch) {
            curl_close($this->ch);
        }
    }
}

<?php

declare(strict_types=1);

namespace AlfaAcquiring\HttpClient;

class CurlClient
{
    private int $timeout = 0;

    private ?CurlException $exception = null;

    /**
     * @var mixed
     */
    private $response = null;

    /**
     * @var string[]
     */
    private array $httpHeaders = [];

    /**
     * @param array $postFields
     *
     * @return array
     */
    private function buildCurlOptions(array $postFields = []): array
    {
        $options = [
            CURLOPT_VERBOSE => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING, 'gzip',
            CURLOPT_ENCODING, '',
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
            $this->response = json_decode($response, true) ?: null;
        }
    }

    public function execute(string $url, array $postFields = []): CurlClient
    {
        $handle = curl_init();

        if (false === $handle) {
            $this->setError('Cannot initialise curl');
        }

        $options = $this->buildCurlOptions($postFields);

        if (!curl_setopt_array($handle, $options)) {
            $this->setError('Cannot set curl options', $handle);
        }

        $response = curl_exec($handle) ?: null;

        if (is_string($response)) {
            $this->decodeResponse($response);
        } else {
            $this->setError('Bad response', $handle);
        }

        curl_close($handle);

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

    /**
     * @return mixed
     */
    public function getResponse()
    {
        if ($this->hasError()) {
            return null;
        }

        return $this->response;
    }

    public function setTimeout(int $timeout): CurlClient
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * @param string $msg
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
}

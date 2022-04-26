<?php

declare(strict_types=1);

namespace AlfaAcquiring\HttpClient;

interface HttpRequestInterface
{
    /**
     * @param int $name
     * @param mixed $value
     */
    public function setOption(int $name, $value): HttpRequestInterface;

    public function setHttpHeaders(array $headers): HttpRequestInterface;

    public function setTimeout(int $timeout): HttpRequestInterface;

    public function getHttpResponse(): ?string;

    public function execute(string $url, array $postFields = []): HttpRequestInterface;

    public function getDecodedResponse();

    public function hasError(): bool;

    public function getErrorDetails(): string;

    public function getLastQuery(): string;

    public function getHttpResponseCode(): int;

    public function getInfo(?int $name);

    public function initialise(): void;

    public function close(): void;
}

<?php

declare(strict_types=1);

namespace AlfaAcquiring\HttpClient;

interface HttpRequestInterface
{
    public function setOption(int $name, $value): void;

    public function setHttpHeaders(array $headers): HttpRequestInterface;

    public function getHttpResponse(): ?string;

    public function execute(string $url, array $postFields = []);

    public function getDecodedResponse();

    public function hasError(): bool;

    public function getErrorDetails(): string;

    public function getInfo(?int $name);

    public function initialise(): void;

    public function close(): void;
}

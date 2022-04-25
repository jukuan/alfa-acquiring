<?php

declare(strict_types=1);

namespace AlfaAcquiring\Response;

interface ResponseInterface
{
    public static function initialiseFailed(string $errorMsg, int $errorCode = 0): ResponseInterface;

    public function getErrorMessage(): ?string;

    public function isValid(): bool;
}

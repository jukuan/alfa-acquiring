<?php

declare(strict_types=1);

namespace AlfaAcquiring\Response;

interface ResponseInterface
{
    public static function initialiseFailed(string $errorMsg): ResponseInterface;

    public function getErrorMessage(): ?string;

    public function isValid(): bool;
}

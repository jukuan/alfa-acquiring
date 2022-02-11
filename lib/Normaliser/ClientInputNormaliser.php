<?php

declare(strict_types=1);

namespace AlfaAcquiring\Normaliser;

interface ClientInputNormaliser
{
    public function normalise(?string $value): string;
}

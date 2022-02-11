<?php

declare(strict_types=1);

namespace AlfaAcquiring\Normaliser;

abstract class AbstractNormaliser
{
    protected const INPUT_FILTER = FILTER_DEFAULT;

    protected function stringify(?string $value): string
    {
        $value = null !== $value ? trim($value) : '';

        return '' !== $value ? filter_var($value, self::INPUT_FILTER) : '';
    }
}

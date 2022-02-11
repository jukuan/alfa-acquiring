<?php

declare(strict_types=1);

namespace AlfaAcquiring\Normaliser;

class EmailNormaliser extends AbstractNormaliser implements ClientInputNormaliser
{
    protected const INPUT_FILTER = FILTER_VALIDATE_EMAIL;

    public function normalise(?string $value): string
    {
        return $this->stringify($value);
    }
}

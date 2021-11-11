<?php

declare(strict_types=1);

namespace AlfaAcquiring\Api\Interfaces;

interface PayableInterface
{
    public const BYN_CURRENCY = 933;

    public function getCurrency(): int;
}

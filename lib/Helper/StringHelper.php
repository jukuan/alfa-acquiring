<?php

declare(strict_types=1);

namespace AlfaAcquiring\Helper;

use AlfaAcquiring\Normaliser\EmailNormaliser;
use AlfaAcquiring\Normaliser\PhoneNormaliser;

class StringHelper
{
    public static function prepareEmail(?string $email): string
    {
        return (new EmailNormaliser())->normalise($email);
    }

    public static function preparePhone(?string $number): string
    {
        return (new PhoneNormaliser())->normalise($number);
    }
}

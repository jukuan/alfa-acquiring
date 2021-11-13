<?php

declare(strict_types=1);

namespace AlfaAcquiring\Helper;

class StringHelper
{
    private const MIN_PHONE_LENGTH = 9;

    public static function prepareEmail(?string $email): ?string
    {
        $email = null !== $email ? trim($email) : '';

        if ('' === $email || false === filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return null;
        }

        return $email;
    }

    public static function preparePhone(?string $number): ?string
    {
        $number = null !== $number ? trim($number) : '';

        if (strlen($number) < self::MIN_PHONE_LENGTH) {
            return null;
        }

        $number = preg_replace("/[^+0-9]/", '', $number);

        if ('+' === $number[0]) {
            $withoutCode = substr($number, 1);
        } else if (0 === strpos($number, '80')) {
            $withoutCode = substr($number, 1);
        } else {
            $withoutCode = $number;
        }

        if (strlen($withoutCode) < self::MIN_PHONE_LENGTH) {
            return null;
        }

        // TODO: check the country code and operator's code

        return $number;
    }
}

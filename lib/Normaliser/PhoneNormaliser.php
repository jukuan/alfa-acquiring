<?php

declare(strict_types=1);

namespace AlfaAcquiring\Normaliser;

class PhoneNormaliser extends AbstractNormaliser implements ClientInputNormaliser
{
    private const MIN_PHONE_LENGTH = 9;

    public function normalise(?string $value): string
    {
        $value = $this->stringify($value);

        if (strlen($value) < self::MIN_PHONE_LENGTH) {
            return '';
        }

        $value = $this->makePhoneFormat($value);

        if ('+' === $value[0]) {
            $withoutCode = substr($value, 1);
        } elseif (0 === strpos($value, '80')) {
            $withoutCode = substr($value, 1);
        } else {
            $withoutCode = $value;
        }

        if (strlen($withoutCode) < self::MIN_PHONE_LENGTH) {
            return '';
        }

        // TODO: check the country code and operator's code

        return $value;
    }

    private function makePhoneFormat(string $value): string
    {
        return preg_replace("/[^0-9+]/", '', $value);
    }
}

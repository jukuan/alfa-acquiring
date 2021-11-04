<?php

declare(strict_types=1);

namespace AlfaAcquiring\Model;

class Customer
{
    private const MIN_PHONE_LENGTH = 7;
    private const MIN_EMAIL_LENGTH = 6;

    private ?string $email = null;

    private ?string $phone = null;

    public function __construct(?string $email, ?string $phone = null)
    {
        $this->setEmail($email)->setPhone($phone);
    }

    /**
     * @param string|null $value
     *
     * @return string|null
     */
    private static function prepareEmail(?string $value): ?string
    {
        if (null === $value || '' === $value || false === filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return null;
        }

        return trim($value);
    }

    private static function preparePhone(?string $value): ?string
    {
        if (null === $value || '' === $value || strlen($value) < 7) {
            return null;
        }

        $value = preg_replace("/[^+0-9]/", '', $value);

        return trim($value);
    }

    public function hasValidEmail(): bool
    {
        if (null === $this->email || '' === $this->email) {
            return false;
        }

        return strlen($this->email) > self::MIN_EMAIL_LENGTH && mb_strpos($this->email, '@');
    }

    public function hasValidPhone(): bool
    {
        if (null === $this->phone || '' === $this->phone) {
            return false;
        }

        // TODO: check the country code and operator's code

        return strlen($this->phone) > self::MIN_PHONE_LENGTH;
    }

    public function isValid(): bool
    {
        return $this->hasValidEmail() || $this->hasValidPhone();
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(?string $email): Customer
    {
        if (null === $email || '' === $email) {
            $this->email = null;
        } else {
            $this->email = self::prepareEmail($email);
        }

        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): Customer
    {
        if (null === $phone || '' === $phone) {
            $this->phone = null;
        } else {
            $this->phone = self::preparePhone($phone);
        }

        return $this;
    }
}

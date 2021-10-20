<?php

declare(strict_types=1);

namespace AlfaAcquiring\Model;

class Customer
{
    private string $email;

    private string $phone;

    public function __construct(string $email, string $phone)
    {
        $this->email = self::prepareEmail($email);
        $this->phone = self::prepareEmail($phone);
    }

    private static function prepareEmail(string $value): string
    {
        // TODO: implement that
        return $value;
    }

    private static function preparePhone(string $value): string
    {
        // TODO: implement that
        return $value;
    }

    public function isValid(): bool
    {
        // TODO: implement that
        return $this->email && $this->email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): Customer
    {
        $this->email = $email;
        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): Customer
    {
        $this->phone = $phone;

        return $this;
    }
}

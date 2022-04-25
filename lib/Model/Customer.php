<?php

declare(strict_types=1);

namespace AlfaAcquiring\Model;

use AlfaAcquiring\Helper\StringHelper;

class Customer
{
    protected ?string $email = null;

    protected ?string $phone = null;

    public function __construct(?string $email, ?string $phone = null)
    {
        $this->setEmail($email);
        $this->setPhone($phone);
    }

    public function hasValidEmail(): bool
    {
        return null !== $this->email && '' !== $this->email;
    }

    public function hasValidPhone(): bool
    {
        return null !== $this->phone && '' !== $this->phone;
    }

    public function isValid(): bool
    {
        return $this->hasValidEmail() || $this->hasValidPhone();
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    protected function setEmail(?string $email): void
    {
        if (null === $email || '' === $email) {
            $this->email = null;
        } else {
            $this->email = StringHelper::prepareEmail($email);
        }
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    protected function setPhone(?string $phone): void
    {
        if (null === $phone || '' === $phone) {
            $this->phone = null;
        } else {
            $this->phone = StringHelper::preparePhone($phone);
        }
    }
}

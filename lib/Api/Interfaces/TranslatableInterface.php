<?php

declare(strict_types=1);

namespace AlfaAcquiring\Api\Interfaces;

interface TranslatableInterface
{
    public const LANGUAGE_BE = 'by';
    public const LANGUAGE_EN = 'en';
    public const LANGUAGE_RU = 'ru';
    public const LANGUAGES = [
        self::LANGUAGE_BE,
        self::LANGUAGE_EN,
        self::LANGUAGE_RU,
    ];
    public const DEFAULT_LANGUAGE = self::LANGUAGE_RU;

    public function getLanguage(): string;
}

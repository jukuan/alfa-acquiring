<?php

declare(strict_types=1);

namespace AlfaAcquiring\HttpRequest;

class BaseRequest
{
    protected array $fields = [];

    /**
     * @var string[]
     */
    protected array $inputNames = [];

    public function __construct(?array $fields = null)
    {
        $this->fields = $fields ?? $_REQUEST;
    }

    public static function isPost(): bool
    {
        return 'POST' === strtoupper($_SERVER['REQUEST_METHOD'] ?? '');
    }

    protected function getInputName(string $key, ?string $default = null): string
    {
        $default = $default ?: $key;

        return $this->inputNames[$key] ?? $default;
    }

    protected function getInputValue(string $key, string $default = ''): string
    {
        $name = $this->getInputName($key);

        return $this->fields[$name] ?? $default;
    }

    public function configureInputNames(array $fields): BaseRequest
    {
        $this->inputNames = array_merge(
            $this->inputNames,
            $fields
        );

        return $this;
    }

    public function getDomainName(): string
    {
        return $_SERVER['HTTP_HOST'] ?? ($_SERVER['SERVER_NAME'] ?? '');
    }

    public function getScheme(): string
    {
        $proto = 'http';

        if (isset($_SERVER['HTTPS'])) {
            $https = strtolower($_SERVER['HTTPS']);

            if ('on' === $https || 1 === (int) $https) {
                $proto = 'https';
            }
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            $proto = strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']);
        } elseif (isset($_SERVER['SERVER_PROTOCOL'])) {
            $proto = strtolower($_SERVER['SERVER_PROTOCOL']);
        }

        return $proto;
    }
}

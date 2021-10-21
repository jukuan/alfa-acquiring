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

    protected function getInputName(string $key): string
    {
        return $this->inputNames[$key] ?? $key;
    }

    /**
     * @param string[] $fields
     *
     * @return $this
     */
    public function configureInputNames(array $fields): BaseRequest
    {
        $this->inputNames = array_merge(
            $this->inputNames,
            $fields
        );

        return $this;
    }
}

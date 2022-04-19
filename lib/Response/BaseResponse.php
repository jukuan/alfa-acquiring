<?php

declare(strict_types=1);

namespace AlfaAcquiring\Response;

use Exception;

class BaseResponse implements ResponseInterface
{
    /**
     * @var string[]
     */
    protected array $fields = [];

    protected ?Exception $error = null;

    /**
     * @param array<array-key, mixed> $fields
     *
     * @return bool
     */
    protected function checkErrorFields(array $fields): bool
    {
        $errorCode = (int) ($fields['errorCode'] ?? 0);

        if ($errorCode > 0) {
            $this->setErrorFields($fields);
        }

        return $errorCode > 0;
    }

    /**
     * @param array<array-key, mixed> $fields
     */
    public function __construct(array $fields)
    {
        $this->fields = $fields;
        $this->checkErrorFields($this->fields);
    }

    public static function initialiseFailed(string $errorMsg): BaseResponse
    {
        return (new static([]))
            ->setErrorFields([
                'errorMessage' => $errorMsg
            ]);
    }

    protected function setErrorFields(array $fields): BaseResponse
    {
        $this->error = new Exception(
            (string) ($fields['errorMessage'] ?? ''),
            (int) ($fields['errorCode'] ?? 0)
        );

        return $this;
    }

    public function getErrorMessage(): ?string
    {
        if (null === $this->error) {
            return null;
        }

        return $this->error->getMessage();
    }

    public function isValid(): bool
    {
        return null === $this->error;
    }

    public function getField(string $fieldName): ?string
    {
        return $this->fields[$fieldName] ?? null;
    }

    public function getAllFields(): array
    {
        return $this->fields;
    }

    protected function getInnerFields(): array
    {
        foreach ($this->fields as $key => $list) {
            if (false !== strpos($key, 'fields') && is_array($list)) {
                return $list ?? [];
            }
        }

        return [];
    }

    protected function getErrorFields(): array
    {
        foreach ($this->fields as $key => $list) {
            if (false !== strpos($key, 'error') && is_array($list)) {
                return $list ?? [];
            }
        }

        return [];
    }
}

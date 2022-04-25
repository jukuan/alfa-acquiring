<?php

declare(strict_types=1);

namespace AlfaAcquiring\Response;

use Exception;

class BaseResponse implements ResponseInterface
{
    protected array $response = [];
    protected array $fields = [];

    protected ?Exception $error = null;

    /**
     * @param array $fields
     *
     * @return bool
     */
    protected function checkErrorFields(array $fields): bool
    {
        if (!isset($fields['errorMessage'])) {
            $fields = $this->getErrorFields();
        }

        $errorCode = (int) ($fields['errorCode'] ?? 0);
        $errorMsg = $fields['errorMessage'] ?? '';

        if ($errorCode > 0 || '' !== $errorMsg) {
            $this->setErrorFields($fields);

            return true;
        }

        return false;
    }

    /**
     * @param array $fields
     */
    public function __construct(array $fields)
    {
        $this->fields = $fields;
        $this->response = $this->findResponseFields();
        $this->checkErrorFields($this->fields);
    }

    public static function initialiseFailed(string $errorMsg, int $errorCode = 0): BaseResponse
    {
        return (new static([]))
            ->setErrorFields([
                'errorMessage' => $errorMsg,
                'errorCode' => $errorCode,
            ]);
    }

    protected function setErrorException(Exception $exception): BaseResponse
    {
        $this->error = $exception;

        return $this;
    }

    protected function setErrorMessageCode(string $msg, int $code = 0): BaseResponse
    {
        $this->setErrorException(new Exception($msg, $code));

        return $this;
    }

    protected function setErrorFields(array $fields): BaseResponse
    {
        $this->setErrorMessageCode(
            $fields['errorMessage'] ?? '',
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
        return $this->response[$fieldName] ?? $this->fields[$fieldName] ?? null;
    }

    /**
     * @deprecated
     * Use getResponse() instead.
     *
     * That method might be used that for debug only.
     * @return array
     */
    public function getAllFields(): array
    {
        return $this->fields;
    }

    public function getResponse(): array
    {
        return $this->fields;
    }

    protected function findResponseFields(): array
    {
        foreach ($this->fields as $key => $list) {
            if (false !== strpos($key, 'fields') && is_array($list)) {
                return $list;
            }
        }

        return [];
    }

    protected function getErrorFields(): array
    {
        foreach ($this->fields as $key => $list) {
            if (false !== strpos($key, 'error') && is_array($list)) {
                return $list;
            }
        }

        return [];
    }

    public function __sleep()
    {
        return ['fields', 'response', 'error'];
    }
}

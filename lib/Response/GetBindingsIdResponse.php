<?php

declare(strict_types=1);

namespace AlfaAcquiring\Response;

class GetBindingsIdResponse extends BaseResponse
{
    private array $bindings;

    public static function initialiseFailed(string $errorMsg): GetBindingsIdResponse
    {
        return (new GetBindingsIdResponse([]))
            ->setErrorFields([
                'errorMessage' => $errorMsg,
                'method' => 'getBindings.do',
            ]);
    }

    public function __construct(array $fields)
    {
        parent::__construct($fields);
        $this->bindings = $this->getBindingFields();
    }

    public function getBindingId(): string
    {
        return $this->bindings['bindingId'] ?? '';
    }

    public function getMaskedPan(): string
    {
        return $this->bindings['maskedPan'] ?? '';
    }

    public function getExpiryDate(): string
    {
        return $this->bindings['expiryDate'] ?? '';
    }

    public function getDisplayLabel(): string
    {
        return $this->bindings['displayLabel'] ?? '';
    }

    protected function getBindingFields(): array
    {
        return $this->response['bindings'][0] ?? [];
    }
}

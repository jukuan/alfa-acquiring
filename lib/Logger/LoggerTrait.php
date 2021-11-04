<?php

declare(strict_types=1);

namespace AlfaAcquiring\Logger;

use Psr\Log\LoggerInterface;
use Throwable;

trait LoggerTrait
{
    private ?LoggerInterface $logger = null;

    protected function setLogger(?LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    protected function logInfo(string $message, array $context = [])
    {
        if (null !== $this->logger) {
            $this->logger->info($message, $context);
        }
    }

    protected function logError(string $message, array $context = [])
    {
        if (null !== $this->logger) {
            $this->logger->error($message, $context);
        }
    }

    protected function logException(Throwable $exception, array $context = [])
    {
        $context['errorCode'] = $exception->getCode();

        if (null !== $exception->getPrevious()) {
            $context['previousErrorCode'] = $exception->getPrevious()->getCode();
        }

        if (null !== $this->logger) {
            $this->logger->error($exception->getMessage(), $context);
        }
    }
}

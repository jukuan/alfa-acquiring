<?php

declare(strict_types=1);

namespace AlfaAcquiring\Logger;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class SimpleFileLogger implements LoggerInterface
{
    private ?string $dirPath = null;

    protected function setDirPath(?string $dirPath): SimpleFileLogger
    {
        if (null !== $dirPath && '' !== $dirPath) {
            if (!file_exists($dirPath)) {
                mkdir($dirPath, 0755, true);
            }

            $this->dirPath = rtrim($dirPath, '/');
        }

        return $this;
    }

    public static function detectDocumentRoot(): ?string
    {
        $dirPath = null;

        if (isset($_SERVER['DOCUMENT_ROOT'])) {
            $dirPath = $_SERVER['DOCUMENT_ROOT'];
        } elseif (isset($_SERVER['SCRIPT_FILENAME'])) {
            $dirPath = dirname($_SERVER['SCRIPT_FILENAME']);
        }

        return $dirPath;
    }

    public function __construct(?string $dirPath = null)
    {
        if (null === $dirPath || '' === $dirPath) {
            $dirPath = static::detectDocumentRoot();
        }

        $this->setDirPath($dirPath);
    }

    public function emergency($message, array $context = array())
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    public function alert($message, array $context = array())
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    public function critical($message, array $context = array())
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    public function error($message, array $context = array())
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    public function warning($message, array $context = array())
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    public function notice($message, array $context = array())
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    public function info($message, array $context = array())
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    public function debug($message, array $context = array())
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    private function getFilePath(): ?string
    {
        if (null === $this->dirPath || '' === $this->dirPath) {
            return null;
        }

        if (!file_exists($this->dirPath)) {
            return null;
        }

        return $this->dirPath . sprintf('/log-alfa-acquiring_%s.log', date('Y-m-d'));
    }

    protected function generateContextMsg($message, array $context): string
    {
        if (!is_string($message)) {
            $message = print_r($message, true);
        }

        if (count($context) > 0) {
            $message .= ' Context: ' . print_r($context, true);
        }

        return $message;
    }

    public function log($level, $message, array $context = array())
    {
        $filePath = $this->getFilePath();

        if (null === $filePath) {
            return;
        }

        $message = $this->generateContextMsg($message, $context);
        $message = sprintf('%s: %s', $level, $message);

        file_put_contents($filePath, $message, FILE_APPEND);
    }
}

<?php

require_once 'LoggerDecorator.php';

class TimestampDecorator extends LoggerDecorator
{
    public function log(string $message): void
    {
        $timestamp = date('[Y-m-d H:i:s]');
        $this->logger->log("{$timestamp} {$message}");
    }
}

class ColorDecorator extends LoggerDecorator
{
    public function __construct(LoggerInterface $logger, private string $color = '32')
    {
        parent::__construct($logger);
    }
    
    public function log(string $message): void
    {
        $this->logger->log("\033[{$this->color}m{$message}\033[0m");
    }
}

class UppercaseDecorator extends LoggerDecorator
{
    public function log(string $message): void
    {
        $this->logger->log(strtoupper($message));
    }
}

class PrefixDecorator extends LoggerDecorator
{
    public function __construct(LoggerInterface $logger, private string $prefix)
    {
        parent::__construct($logger);
    }
    
    public function log(string $message): void
    {
        $this->logger->log("[{$this->prefix}] {$message}");
    }
}

class FileDecorator extends LoggerDecorator
{
    public function __construct(LoggerInterface $logger, private string $filename)
    {
        parent::__construct($logger);
    }
    
    public function log(string $message): void
    {
        file_put_contents($this->filename, $message . "\n", FILE_APPEND);
        $this->logger->log($message);
    }
}

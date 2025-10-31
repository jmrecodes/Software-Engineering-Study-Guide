<?php

require_once 'LoggerInterface.php';

abstract class LoggerDecorator implements LoggerInterface
{
    public function __construct(protected LoggerInterface $logger) {}
    
    public function log(string $message): void
    {
        $this->logger->log($message);
    }
}

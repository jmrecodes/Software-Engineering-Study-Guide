<?php

/**
 * AFTER: The Solution with Decorator Pattern
 * Component Interface
 */

interface LoggerInterface
{
    public function log(string $message): void;
}

<?php

require_once 'LoggerInterface.php';

class BasicLogger implements LoggerInterface
{
    public function log(string $message): void
    {
        echo $message . "\n";
    }
}

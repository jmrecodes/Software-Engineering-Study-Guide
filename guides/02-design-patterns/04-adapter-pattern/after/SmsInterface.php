<?php

/**
 * AFTER: The Solution with Adapter Pattern
 * Target Interface - The interface our application expects
 */

interface SmsInterface
{
    public function send(string $to, string $message): bool;
    public function getProvider(): string;
}

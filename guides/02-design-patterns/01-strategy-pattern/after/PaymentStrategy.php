<?php

/**
 * AFTER: The Solution with Strategy Pattern
 * 
 * Each payment method is now its own class implementing a common interface.
 * The PaymentProcessor is simplified and works with any payment strategy.
 */

interface PaymentStrategy
{
    public function pay(float $amount, array $details): array;
    public function refund(string $transactionId, float $amount): array;
    public function getName(): string;
}

<?php

require_once 'PaymentStrategy.php';

class PaymentProcessor
{
    private PaymentStrategy $strategy;
    
    public function __construct(PaymentStrategy $strategy)
    {
        $this->strategy = $strategy;
    }
    
    public function setStrategy(PaymentStrategy $strategy): void
    {
        $this->strategy = $strategy;
    }
    
    public function processPayment(float $amount, array $details): array
    {
        echo "Using {$this->strategy->getName()} payment method\n";
        return $this->strategy->pay($amount, $details);
    }
    
    public function refundPayment(string $transactionId, float $amount): array
    {
        return $this->strategy->refund($transactionId, $amount);
    }
}

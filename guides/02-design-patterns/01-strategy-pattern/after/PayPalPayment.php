<?php

require_once 'PaymentStrategy.php';

class PayPalPayment implements PaymentStrategy
{
    public function pay(float $amount, array $details): array
    {
        echo "Processing \${$amount} via PayPal...\n";
        
        if (!isset($details['email'])) {
            throw new Exception('PayPal requires an email');
        }
        
        // Simulate PayPal API call
        $payment = [
            'id' => 'PAY-' . uniqid(),
            'amount' => $amount,
            'payer' => $details['email'],
        ];
        
        return [
            'success' => true,
            'transaction_id' => $payment['id'],
            'provider' => 'paypal',
            'amount' => $amount,
        ];
    }
    
    public function refund(string $transactionId, float $amount): array
    {
        echo "Refunding \${$amount} via PayPal for transaction {$transactionId}...\n";
        
        return [
            'success' => true,
            'refund_id' => 'REF-' . uniqid(),
            'provider' => 'paypal',
            'amount' => $amount,
        ];
    }
    
    public function getName(): string
    {
        return 'PayPal';
    }
}

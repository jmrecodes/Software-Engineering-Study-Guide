<?php

require_once 'PaymentStrategy.php';

class StripePayment implements PaymentStrategy
{
    public function pay(float $amount, array $details): array
    {
        echo "Processing \${$amount} via Stripe...\n";
        
        if (!isset($details['token'])) {
            throw new Exception('Stripe requires a token');
        }
        
        // Simulate Stripe API call
        $charge = [
            'id' => 'ch_' . uniqid(),
            'amount' => $amount * 100, // Stripe uses cents
            'currency' => 'usd',
            'source' => $details['token'],
        ];
        
        return [
            'success' => true,
            'transaction_id' => $charge['id'],
            'provider' => 'stripe',
            'amount' => $amount,
        ];
    }
    
    public function refund(string $transactionId, float $amount): array
    {
        echo "Refunding \${$amount} via Stripe for transaction {$transactionId}...\n";
        
        return [
            'success' => true,
            'refund_id' => 'rf_' . uniqid(),
            'provider' => 'stripe',
            'amount' => $amount,
        ];
    }
    
    public function getName(): string
    {
        return 'Stripe';
    }
}

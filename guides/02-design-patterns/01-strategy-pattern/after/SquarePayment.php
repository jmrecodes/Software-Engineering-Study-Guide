<?php

require_once 'PaymentStrategy.php';

class SquarePayment implements PaymentStrategy
{
    public function pay(float $amount, array $details): array
    {
        echo "Processing \${$amount} via Square...\n";
        
        if (!isset($details['nonce'])) {
            throw new Exception('Square requires a nonce');
        }
        
        // Simulate Square API call
        $payment = [
            'id' => 'sq_' . uniqid(),
            'amount_money' => [
                'amount' => $amount * 100,
                'currency' => 'USD',
            ],
            'source_id' => $details['nonce'],
        ];
        
        return [
            'success' => true,
            'transaction_id' => $payment['id'],
            'provider' => 'square',
            'amount' => $amount,
        ];
    }
    
    public function refund(string $transactionId, float $amount): array
    {
        echo "Refunding \${$amount} via Square for transaction {$transactionId}...\n";
        
        return [
            'success' => true,
            'refund_id' => 'sqrf_' . uniqid(),
            'provider' => 'square',
            'amount' => $amount,
        ];
    }
    
    public function getName(): string
    {
        return 'Square';
    }
}

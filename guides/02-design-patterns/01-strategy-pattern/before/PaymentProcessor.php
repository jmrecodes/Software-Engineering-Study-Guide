<?php

/**
 * BEFORE: The Problem
 * 
 * This code demonstrates what happens WITHOUT the Strategy Pattern.
 * Notice how the PaymentProcessor is tightly coupled to specific payment methods
 * and uses messy if/else statements.
 */

class PaymentProcessor
{
    public function processPayment(string $method, float $amount, array $details): array
    {
        if ($method === 'stripe') {
            // Stripe-specific logic
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
            ];
            
        } elseif ($method === 'paypal') {
            // PayPal-specific logic
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
            ];
            
        } elseif ($method === 'square') {
            // Square-specific logic
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
            ];
            
        } else {
            throw new Exception("Unsupported payment method: {$method}");
        }
    }
    
    public function refund(string $method, string $transactionId, float $amount): array
    {
        // More messy conditionals for refunds
        if ($method === 'stripe') {
            echo "Refunding \${$amount} via Stripe for transaction {$transactionId}...\n";
            return ['success' => true, 'refund_id' => 'rf_' . uniqid()];
        } elseif ($method === 'paypal') {
            echo "Refunding \${$amount} via PayPal for transaction {$transactionId}...\n";
            return ['success' => true, 'refund_id' => 'REF-' . uniqid()];
        } elseif ($method === 'square') {
            echo "Refunding \${$amount} via Square for transaction {$transactionId}...\n";
            return ['success' => true, 'refund_id' => 'sqrf_' . uniqid()];
        } else {
            throw new Exception("Unsupported payment method: {$method}");
        }
    }
}

// Usage - Notice how we have to pass the method string everywhere
$processor = new PaymentProcessor();

try {
    echo "=== PROBLEMATIC APPROACH (Before Strategy Pattern) ===\n\n";
    
    $result1 = $processor->processPayment('stripe', 99.99, [
        'token' => 'tok_visa',
    ]);
    print_r($result1);
    
    $result2 = $processor->processPayment('paypal', 149.99, [
        'email' => 'customer@example.com',
    ]);
    print_r($result2);
    
    $result3 = $processor->processPayment('square', 79.99, [
        'nonce' => 'cnon_card',
    ]);
    print_r($result3);
    
    // Refund
    $refund = $processor->refund('stripe', $result1['transaction_id'], 99.99);
    print_r($refund);
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

/**
 * PROBLEMS WITH THIS APPROACH:
 * 
 * 1. Violation of Open/Closed Principle
 *    - Adding a new payment method requires modifying the PaymentProcessor class
 * 
 * 2. Violation of Single Responsibility Principle
 *    - PaymentProcessor knows about ALL payment methods
 * 
 * 3. Hard to Test
 *    - Can't test payment methods in isolation
 *    - Must test entire PaymentProcessor with all its conditionals
 * 
 * 4. Code Duplication
 *    - Similar conditional logic repeated in every method
 * 
 * 5. Tight Coupling
 *    - PaymentProcessor is coupled to every payment implementation
 * 
 * 6. Poor Scalability
 *    - Each new payment method adds more if/else statements
 *    - Methods become longer and harder to maintain
 * 
 * 7. Runtime Errors
 *    - Typos in payment method strings won't be caught until runtime
 *    - No IDE autocomplete support
 */

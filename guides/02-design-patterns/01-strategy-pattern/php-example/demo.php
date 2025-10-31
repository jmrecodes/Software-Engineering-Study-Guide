<?php

/**
 * Strategy Pattern - PHP 8.3 Complete Example
 * 
 * Run: php demo.php
 */

// ============================================================================
// STRATEGY INTERFACE
// ============================================================================

interface PaymentStrategy
{
    public function pay(float $amount, array $details): array;
    public function refund(string $transactionId, float $amount): array;
    public function getName(): string;
}

// ============================================================================
// CONCRETE STRATEGIES
// ============================================================================

class StripePayment implements PaymentStrategy
{
    public function pay(float $amount, array $details): array
    {
        echo "💳 Processing \${$amount} via Stripe...\n";
        
        if (!isset($details['token'])) {
            throw new Exception('Stripe requires a token');
        }
        
        $charge = [
            'id' => 'ch_' . uniqid(),
            'amount' => $amount * 100,
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
        echo "↩️  Refunding \${$amount} via Stripe for {$transactionId}...\n";
        
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

class PayPalPayment implements PaymentStrategy
{
    public function pay(float $amount, array $details): array
    {
        echo "💳 Processing \${$amount} via PayPal...\n";
        
        if (!isset($details['email'])) {
            throw new Exception('PayPal requires an email');
        }
        
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
        echo "↩️  Refunding \${$amount} via PayPal for {$transactionId}...\n";
        
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

class SquarePayment implements PaymentStrategy
{
    public function pay(float $amount, array $details): array
    {
        echo "💳 Processing \${$amount} via Square...\n";
        
        if (!isset($details['nonce'])) {
            throw new Exception('Square requires a nonce');
        }
        
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
        echo "↩️  Refunding \${$amount} via Square for {$transactionId}...\n";
        
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

// ============================================================================
// CONTEXT CLASS
// ============================================================================

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
        echo "\n🔄 Using {$this->strategy->getName()} payment method\n";
        return $this->strategy->pay($amount, $details);
    }
    
    public function refundPayment(string $transactionId, float $amount): array
    {
        return $this->strategy->refund($transactionId, $amount);
    }
}

// ============================================================================
// USAGE DEMONSTRATION
// ============================================================================

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║          Strategy Pattern - Payment Processing Demo          ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n";

try {
    // Start with Stripe
    $processor = new PaymentProcessor(new StripePayment());
    
    $stripeResult = $processor->processPayment(99.99, [
        'token' => 'tok_visa_debit',
    ]);
    echo "✅ Success: {$stripeResult['transaction_id']}\n";
    
    // Switch to PayPal at runtime
    echo "\n--- Switching to PayPal ---\n";
    $processor->setStrategy(new PayPalPayment());
    
    $paypalResult = $processor->processPayment(149.99, [
        'email' => 'customer@example.com',
    ]);
    echo "✅ Success: {$paypalResult['transaction_id']}\n";
    
    // Switch to Square
    echo "\n--- Switching to Square ---\n";
    $processor->setStrategy(new SquarePayment());
    
    $squareResult = $processor->processPayment(79.99, [
        'nonce' => 'cnon_card_nonce',
    ]);
    echo "✅ Success: {$squareResult['transaction_id']}\n";
    
    // Refund using current strategy (Square)
    echo "\n--- Processing Refund ---\n";
    $refundResult = $processor->refundPayment($squareResult['transaction_id'], 79.99);
    echo "✅ Refund Success: {$refundResult['refund_id']}\n";
    
    // Dynamic strategy selection based on user preference
    echo "\n--- Dynamic Strategy Selection ---\n";
    $userPreference = 'stripe'; // This could come from database or user input
    
    $strategy = match($userPreference) {
        'stripe' => new StripePayment(),
        'paypal' => new PayPalPayment(),
        'square' => new SquarePayment(),
        default => throw new Exception("Unknown payment method: {$userPreference}")
    };
    
    $processor->setStrategy($strategy);
    $result = $processor->processPayment(199.99, [
        'token' => 'tok_mastercard',
    ]);
    echo "✅ Success: {$result['transaction_id']}\n";
    
    echo "\n╔══════════════════════════════════════════════════════════════╗\n";
    echo "║                    ✨ Demo Complete ✨                        ║\n";
    echo "╚══════════════════════════════════════════════════════════════╝\n";
    
} catch (Exception $e) {
    echo "\n❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";
echo "Key Benefits Demonstrated:\n";
echo "  ✅ Easy to switch strategies at runtime\n";
echo "  ✅ No if/else conditionals in client code\n";
echo "  ✅ Each strategy is independent and testable\n";
echo "  ✅ Adding new payment methods doesn't modify existing code\n";
echo "  ✅ Type-safe with IDE autocomplete support\n";

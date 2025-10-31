# Laravel 11 Strategy Pattern Implementation

This is a practical Laravel 11 implementation of the Strategy Pattern for payment processing.

## Installation

To create a full Laravel 11 app with this implementation:

```bash
# Create new Laravel 11 app
laravel new payment-app
cd payment-app

# Or use Composer
composer create-project laravel/laravel payment-app
cd payment-app
```

## Implementation Steps

### 1. Create the Strategy Interface

Create `app/Contracts/PaymentStrategy.php`:

```php
<?php

namespace App\Contracts;

interface PaymentStrategy
{
    public function charge(float $amount, array $details): array;
    public function refund(string $transactionId, float $amount): array;
    public function getName(): string;
}
```

### 2. Create Concrete Strategies

Create `app/Services/Payment/StripePayment.php`:

```php
<?php

namespace App\Services\Payment;

use App\Contracts\PaymentStrategy;

class StripePayment implements PaymentStrategy
{
    public function charge(float $amount, array $details): array
    {
        // Integration with Stripe SDK
        // \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        
        return [
            'success' => true,
            'transaction_id' => 'ch_' . uniqid(),
            'provider' => 'stripe',
            'amount' => $amount,
        ];
    }
    
    public function refund(string $transactionId, float $amount): array
    {
        return [
            'success' => true,
            'refund_id' => 'rf_' . uniqid(),
            'provider' => 'stripe',
        ];
    }
    
    public function getName(): string
    {
        return 'Stripe';
    }
}
```

Create similar classes for PayPal and Square.

### 3. Create Payment Service (Context)

Create `app/Services/PaymentService.php`:

```php
<?php

namespace App\Services;

use App\Contracts\PaymentStrategy;

class PaymentService
{
    public function __construct(private PaymentStrategy $strategy) {}
    
    public function setStrategy(PaymentStrategy $strategy): void
    {
        $this->strategy = $strategy;
    }
    
    public function processPayment(float $amount, array $details): array
    {
        return $this->strategy->charge($amount, $details);
    }
    
    public function processRefund(string $transactionId, float $amount): array
    {
        return $this->strategy->refund($transactionId, $amount);
    }
}
```

### 4. Register in Service Provider

In `app/Providers/AppServiceProvider.php`:

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\PaymentStrategy;
use App\Services\Payment\StripePayment;
use App\Services\Payment\PayPalPayment;
use App\Services\Payment\SquarePayment;
use App\Services\PaymentService;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register based on config
        $this->app->bind(PaymentStrategy::class, function ($app) {
            $provider = config('payment.default', 'stripe');
            
            return match($provider) {
                'stripe' => new StripePayment(),
                'paypal' => new PayPalPayment(),
                'square' => new SquarePayment(),
                default => new StripePayment(),
            };
        });
        
        $this->app->singleton(PaymentService::class, function ($app) {
            return new PaymentService($app->make(PaymentStrategy::class));
        });
    }
}
```

### 5. Create Controller

Create `app/Http/Controllers/PaymentController.php`:

```php
<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $paymentService) {}
    
    public function charge(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:stripe,paypal,square',
            'details' => 'required|array',
        ]);
        
        // Switch strategy based on user selection
        if ($validated['payment_method'] === 'paypal') {
            $this->paymentService->setStrategy(app(\App\Services\Payment\PayPalPayment::class));
        } elseif ($validated['payment_method'] === 'square') {
            $this->paymentService->setStrategy(app(\App\Services\Payment\SquarePayment::class));
        }
        
        $result = $this->paymentService->processPayment(
            $validated['amount'],
            $validated['details']
        );
        
        return response()->json($result);
    }
}
```

### 6. Create Routes

In `routes/web.php`:

```php
<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::post('/payment/charge', [PaymentController::class, 'charge']);
Route::view('/checkout', 'checkout');
```

### 7. Create Config File

Create `config/payment.php`:

```php
<?php

return [
    'default' => env('PAYMENT_GATEWAY', 'stripe'),
    
    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    
    'paypal' => [
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'secret' => env('PAYPAL_SECRET'),
    ],
    
    'square' => [
        'application_id' => env('SQUARE_APPLICATION_ID'),
        'access_token' => env('SQUARE_ACCESS_TOKEN'),
    ],
];
```

### 8. Environment Variables

Add to `.env`:

```
PAYMENT_GATEWAY=stripe

STRIPE_KEY=your_stripe_key
STRIPE_SECRET=your_stripe_secret

PAYPAL_CLIENT_ID=your_paypal_client_id
PAYPAL_SECRET=your_paypal_secret

SQUARE_APPLICATION_ID=your_square_app_id
SQUARE_ACCESS_TOKEN=your_square_token
```

## Testing

Create `tests/Feature/PaymentTest.php`:

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\PaymentService;
use App\Services\Payment\StripePayment;

class PaymentTest extends TestCase
{
    public function test_can_process_payment_with_stripe()
    {
        $service = new PaymentService(new StripePayment());
        
        $result = $service->processPayment(99.99, [
            'token' => 'tok_visa',
        ]);
        
        $this->assertTrue($result['success']);
        $this->assertEquals('stripe', $result['provider']);
    }
}
```

## Usage in Blade View

Create `resources/views/checkout.blade.php`:

```html
<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
</head>
<body>
    <h1>Checkout</h1>
    
    <form id="payment-form">
        <label>Amount:</label>
        <input type="number" name="amount" step="0.01" required>
        
        <label>Payment Method:</label>
        <select name="payment_method" required>
            <option value="stripe">Stripe</option>
            <option value="paypal">PayPal</option>
            <option value="square">Square</option>
        </select>
        
        <button type="submit">Pay Now</button>
    </form>
    
    <script>
        document.getElementById('payment-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            
            const response = await fetch('/payment/charge', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    amount: formData.get('amount'),
                    payment_method: formData.get('payment_method'),
                    details: { /* payment details */ }
                })
            });
            
            const result = await response.json();
            console.log(result);
        });
    </script>
</body>
</html>
```

## Benefits in Laravel

1. **Easy Testing** - Mock payment strategies in tests
2. **Configuration-Based** - Switch providers via .env
3. **Dependency Injection** - Laravel container manages dependencies
4. **Middleware Support** - Add logging, rate limiting via middleware
5. **Event Integration** - Fire events when payments succeed/fail

## Running the App

```bash
php artisan serve
```

Visit `http://localhost:8000/checkout`

## Advanced: Using Factories

Combine Strategy with Factory pattern:

```php
class PaymentStrategyFactory
{
    public function create(string $provider): PaymentStrategy
    {
        return match($provider) {
            'stripe' => app(StripePayment::class),
            'paypal' => app(PayPalPayment::class),
            'square' => app(SquarePayment::class),
            default => throw new \InvalidArgumentException("Unknown provider: {$provider}")
        };
    }
}
```

This Laravel implementation demonstrates how the Strategy Pattern integrates perfectly with Laravel's service container and dependency injection!

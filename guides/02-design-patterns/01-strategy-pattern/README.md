# Strategy Pattern

## Real-World Analogy

Imagine you're traveling from your home to the airport. You have multiple transportation options: driving your own car, taking an Uber, using public transit, or riding a bike. Each method gets you to the same destination but uses a completely different strategy. You choose the strategy based on factors like traffic, budget, and time constraints. The Strategy Pattern works the same way - it lets you swap algorithms or behaviors at runtime.

## Problem It Solves

**The Problem:** You have multiple ways to accomplish a task, and you need to switch between them based on context. Without the Strategy Pattern, you end up with messy conditional statements (if/else or switch) scattered throughout your code, making it hard to maintain, test, and extend.

**Core Concept:** The Strategy Pattern defines a family of algorithms, encapsulates each one in its own class, and makes them interchangeable. The pattern lets the algorithm vary independently from clients that use it.

## Why Use This in Laravel?

**Best Use Cases in Laravel Applications:**

1. **Payment Gateways** - Handle multiple payment processors (Stripe, PayPal, Square, etc.)
2. **File Storage** - Switch between local, S3, DigitalOcean Spaces, etc.
3. **Authentication Methods** - Social login, email/password, OTP, biometric
4. **Pricing Strategies** - Regular price, member discount, seasonal sale, bulk pricing
5. **Shipping Calculators** - Different carriers with different rate calculations
6. **Email Providers** - SendGrid, Mailgun, SES, SMTP
7. **Cache Strategies** - Redis, Memcached, File, Database

**Example:** In an e-commerce Laravel app, the Strategy Pattern is perfect for implementing multiple payment gateways. Instead of having if/else statements checking which gateway to use throughout your checkout code, you encapsulate each gateway's logic in its own strategy class.

## Structure

```
01-strategy-pattern/
├── README.md (this file)
├── before/
│   └── PaymentProcessor.php (messy conditional code)
├── after/
│   ├── PaymentStrategy.php (interface)
│   ├── StripePayment.php
│   ├── PayPalPayment.php
│   └── PaymentProcessor.php (clean code using strategy)
├── php-example/
│   └── demo.php (runnable PHP 8.3 example)
└── laravel-app/
    └── (full Laravel 11 implementation)
```

## Running the Examples

### PHP Example
```bash
cd php-example
php demo.php
```

### Laravel Example
```bash
cd laravel-app
composer install
php artisan migrate
php artisan serve
```

Then visit: `http://localhost:8000/checkout`

## Key Benefits

✅ **Open/Closed Principle** - Open for extension, closed for modification  
✅ **Single Responsibility** - Each strategy handles one algorithm  
✅ **Testability** - Easy to unit test each strategy independently  
✅ **Runtime Flexibility** - Switch strategies dynamically  
✅ **No Conditionals** - Eliminates messy if/else chains  

## When NOT to Use

❌ When you only have one or two algorithms  
❌ When the algorithms never change  
❌ When the switching logic is extremely simple  
❌ Over-engineering simple problems  

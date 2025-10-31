# Adapter Pattern

## Real-World Analogy

Imagine you're traveling from the US to Europe. Your laptop charger has a US plug, but European outlets are different. You don't buy a new laptop or rewire the outlet—you use a plug adapter that converts the European socket interface to work with your US plug. The Adapter Pattern works the same way: it allows incompatible interfaces to work together without modifying the original code.

## Problem It Solves

**The Problem:** You have existing code that expects one interface, but you need to integrate with a library or service that has a different, incompatible interface. Rewriting either side is expensive, risky, or impossible (third-party code).

**Core Concept:** The Adapter Pattern converts the interface of a class into another interface that clients expect. It lets classes work together that couldn't otherwise because of incompatible interfaces, acting as a bridge or translator between them.

## Why Use This in Laravel?

**Best Use Cases in Laravel Applications:**

1. **Third-Party API Integration** - Adapt different API clients to a common interface (PayPal, Stripe, Square)
2. **Legacy Code Integration** - Make old code work with new systems without rewriting
3. **Multiple Email Providers** - Adapt SendGrid, Mailgun, SES to Laravel's Mail interface
4. **Storage Drivers** - Adapt different cloud storage APIs (AWS S3, DigitalOcean Spaces, Google Cloud)
5. **Social Login** - Adapt various OAuth providers to common authentication interface
6. **Multiple SMS Providers** - Twilio, Nexmo, SNS adapted to single interface
7. **Search Engines** - Adapt Algolia, Elasticsearch, Meilisearch to common search interface

**Example:** In a Laravel application, you might need to support multiple SMS providers (Twilio, Vonage, AWS SNS). Each has a different API and methods. The Adapter Pattern lets you create adapters that translate your application's `SmsInterface` to each provider's specific API, allowing you to switch providers without changing your application code.

## Structure

```
04-adapter-pattern/
├── README.md (this file)
├── before/
│   └── NotificationService.php (coupled to specific APIs)
├── after/
│   ├── SmsInterface.php (target interface)
│   ├── TwilioAdapter.php
│   ├── VonageAdapter.php
│   └── NotificationService.php (uses adapters)
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
php artisan serve
```

## Key Benefits

✅ **Single Responsibility** - Separation of interface conversion from business logic  
✅ **Open/Closed Principle** - Add new adapters without modifying existing code  
✅ **Decoupling** - Client code doesn't depend on concrete third-party classes  
✅ **Reusability** - Same adapter can be used across multiple projects  
✅ **Testability** - Easy to mock adapters for testing  

## When NOT to Use

❌ When both interfaces are identical or very similar  
❌ When you can modify the adaptee to match your interface  
❌ Simple scenarios where a facade or wrapper is sufficient  
❌ Over-engineering for minor interface differences  

## Adapter vs Facade vs Bridge

**Adapter:** Makes existing interfaces compatible (structural fix)  
**Facade:** Simplifies complex interfaces (convenience wrapper)  
**Bridge:** Separates abstraction from implementation (design pattern)  

Use Adapter when integrating incompatible interfaces you can't change.

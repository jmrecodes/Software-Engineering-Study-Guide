# Decorator Pattern

## Real-World Analogy

Think of ordering a coffee. You start with a basic coffee, then you can add decorations: extra shot of espresso, caramel syrup, whipped cream, chocolate drizzle. Each addition wraps the previous item and adds its own cost and description. You don't modify the original coffee; you decorate it with layers. The Decorator Pattern works the same way—it adds responsibilities to objects dynamically without altering their structure.

## Problem It Solves

**The Problem:** You need to add functionality to objects at runtime without modifying their code or using inheritance for every possible combination. Creating subclasses for every combination of features leads to class explosion (e.g., CoffeeWithMilk, CoffeeWithSugar, CoffeeWithMilkAndSugar, etc.).

**Core Concept:** The Decorator Pattern attaches additional responsibilities to an object dynamically. Decorators provide a flexible alternative to subclassing for extending functionality. Each decorator wraps an object and adds its own behavior before or after delegating to the wrapped object.

## Why Use This in Laravel?

**Best Use Cases in Laravel Applications:**

1. **HTTP Middleware** - Laravel's middleware is a perfect example of the Decorator pattern
2. **Query Scopes** - Adding filters to Eloquent queries dynamically
3. **Response Transformers** - Adding headers, encryption, compression to HTTP responses
4. **Logging** - Adding different log levels and handlers dynamically
5. **Caching Layers** - Wrapping services with caching behavior
6. **Authorization** - Adding permission checks to actions
7. **Rate Limiting** - Wrapping API calls with rate limiting
8. **Data Formatting** - Adding formatters (JSON, XML, CSV) to data

**Example:** In a Laravel API application, you might need to add various features to API responses: caching, rate limiting, encryption, compression, logging. The Decorator Pattern lets you wrap the basic response handler with decorators for each feature, combining them as needed without modifying the core response logic.

## Structure

```
05-decorator-pattern/
├── README.md (this file)
├── before/
│   └── Logger.php (using inheritance - class explosion)
├── after/
│   ├── LoggerInterface.php (component interface)
│   ├── BasicLogger.php (concrete component)
│   ├── LoggerDecorator.php (base decorator)
│   └── TimestampDecorator.php, ColorDecorator.php, etc.
├── php-example/
│   └── demo.php (runnable PHP 8.3 example)
└── laravel-app/
    └── (full Laravel 11 implementation with middleware)
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

✅ **Open/Closed Principle** - Add new functionality without modifying existing code  
✅ **Single Responsibility** - Each decorator has one purpose  
✅ **Flexible Combinations** - Mix and match decorators at runtime  
✅ **Alternative to Inheritance** - Avoids class explosion  
✅ **Runtime Configuration** - Add/remove behaviors dynamically  

## When NOT to Use

❌ When object creation code becomes overly complex  
❌ When order of decorators matters and is hard to manage  
❌ When simple inheritance or composition is sufficient  
❌ When you need to remove wrappers (decorators are hard to unwrap)  
❌ Over-engineering simple additions  

## Decorator vs Adapter vs Proxy

**Decorator:** Adds responsibilities while keeping interface the same  
**Adapter:** Converts one interface to another  
**Proxy:** Controls access to an object  

Use Decorator when you need to add features without changing the interface.

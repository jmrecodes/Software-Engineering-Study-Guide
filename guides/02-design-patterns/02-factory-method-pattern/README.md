# Factory Method Pattern

## Real-World Analogy

Think of a restaurant chain that serves breakfast. Each location follows the same menu structure, but the actual breakfast items vary by region. The New York location serves bagels and lox, the Southern location serves biscuits and gravy, and the West Coast location serves avocado toast. Each restaurant location (factory) knows how to create the appropriate regional breakfast (product), but the customer (client code) doesn't need to know the specifics—they just order "breakfast" and get what's appropriate for their location.

## Problem It Solves

**The Problem:** You need to create objects, but the exact type of object depends on runtime conditions or configuration. Hardcoding class instantiation leads to tight coupling and violates the dependency inversion principle.

**Core Concept:** The Factory Method Pattern defines an interface for creating objects but lets subclasses decide which class to instantiate. It delegates the instantiation logic to child classes, promoting loose coupling and following the Open/Closed Principle.

## Why Use This in Laravel?

**Best Use Cases in Laravel Applications:**

1. **Notification Channels** - Create different notification types (Email, SMS, Slack, Push)
2. **Report Generators** - PDF, Excel, CSV, HTML reports based on user preference
3. **Database Connections** - MySQL, PostgreSQL, SQLite, MongoDB based on environment
4. **Image Processors** - Different image manipulation libraries (GD, Imagick, Intervention)
5. **Export Formats** - JSON, XML, CSV exporters for API responses
6. **Authentication Providers** - Different OAuth providers (Google, Facebook, GitHub)
7. **Queue Drivers** - Redis, Database, SQS, Beanstalkd based on config

**Example:** In a Laravel SaaS application with multi-tenant architecture, the Factory Method Pattern is perfect for creating tenant-specific report generators. Each tenant might have different reporting requirements (PDF for Enterprise clients, CSV for Standard clients), and the factory method creates the appropriate generator without the controller needing to know the specifics.

## Structure

```
02-factory-method-pattern/
├── README.md (this file)
├── before/
│   └── ReportGenerator.php (hardcoded instantiation)
├── after/
│   ├── Report.php (product interface)
│   ├── PdfReport.php, ExcelReport.php, CsvReport.php
│   ├── ReportFactory.php (creator abstract class)
│   └── PdfReportFactory.php, ExcelReportFactory.php
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

Then visit: `http://localhost:8000/reports/generate/pdf`

## Key Benefits

✅ **Dependency Inversion** - Depend on abstractions, not concrete classes  
✅ **Single Responsibility** - Object creation logic is centralized  
✅ **Open/Closed Principle** - Add new product types without modifying existing code  
✅ **Testability** - Easy to mock factories in tests  
✅ **Flexibility** - Runtime selection of object types  

## When NOT to Use

❌ When you only create one type of object  
❌ When object creation is trivial (no complex logic)  
❌ When the variation in objects is minimal  
❌ Over-complicating simple instantiation  

## Factory Method vs Abstract Factory

**Factory Method:** Creates one product at a time  
**Abstract Factory:** Creates families of related products  

Use Factory Method when you need to create variations of a single product type.

# Observer Pattern

## Real-World Analogy

Think of a YouTube channel. When a content creator uploads a new video, all subscribers automatically get notified. The creator doesn't need to individually message each subscriber—they just publish the content, and YouTube's system handles notifying everyone who's interested. Subscribers can subscribe or unsubscribe at any time without the creator knowing or caring about the specifics. The Observer Pattern works exactly like this subscription system.

## Problem It Solves

**The Problem:** You have an object (subject) whose state changes, and you need to notify multiple other objects (observers) about those changes. Without the Observer Pattern, you end up with tight coupling where the subject must know about all dependent objects, making the code rigid and hard to maintain.

**Core Concept:** The Observer Pattern defines a one-to-many dependency between objects so that when one object changes state, all its dependents are notified and updated automatically. It promotes loose coupling between the subject and its observers.

## Why Use This in Laravel?

**Best Use Cases in Laravel Applications:**

1. **Event System** - Laravel's event system is built on the Observer pattern
2. **Model Events** - Eloquent observers watching model changes (creating, created, updating, updated, etc.)
3. **User Activity Logging** - Track user actions across the application
4. **Notification System** - Send email, SMS, push notifications when events occur
5. **Cache Invalidation** - Clear cache when data changes
6. **Analytics Tracking** - Log events to analytics services (Google Analytics, Mixpanel)
7. **Audit Trails** - Record all changes for compliance
8. **Real-time Updates** - WebSocket notifications when data changes

**Example:** In an e-commerce Laravel app, when an order is placed, multiple things need to happen: send confirmation email, update inventory, notify warehouse, create invoice, log analytics, send SMS to customer. The Observer Pattern lets you decouple all these actions from the order placement logic—each observer handles its own concern independently.

## Structure

```
03-observer-pattern/
├── README.md (this file)
├── before/
│   └── OrderService.php (tightly coupled notifications)
├── after/
│   ├── Subject.php (observable interface)
│   ├── Observer.php (observer interface)
│   ├── Order.php (concrete subject)
│   └── EmailNotifier.php, InventoryUpdater.php, etc.
├── php-example/
│   └── demo.php (runnable PHP 8.3 example)
└── laravel-app/
    └── (full Laravel 11 implementation with Eloquent Observers)
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

## Key Benefits

✅ **Loose Coupling** - Subject doesn't know concrete observer classes  
✅ **Open/Closed Principle** - Add observers without modifying subject  
✅ **Dynamic Relationships** - Observers can subscribe/unsubscribe at runtime  
✅ **Broadcast Communication** - One event notifies many observers  
✅ **Single Responsibility** - Each observer handles one concern  

## When NOT to Use

❌ When there's only one observer  
❌ When order of notification execution is critical  
❌ When observers need to modify the subject's state  
❌ When performance is critical (many observers = overhead)  
❌ Simple scenarios where direct method calls suffice  

## Observer vs Event Listener

In Laravel, the Observer pattern is implemented through:
- **Eloquent Observers** - For model-specific events
- **Event/Listener System** - For application-wide events

Both are Observer pattern implementations with different use cases.

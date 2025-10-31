Home / Design Patterns / Creational / Factory

# Factory Pattern

> **One-line summary**: Delegate object creation to dedicated factories to encapsulate construction logic and support DIP.

## Table of Contents
- [Overview](#overview)
- [When to Use](#when-to-use)
- [When NOT to Use](#when-not-to-use)
- [Implementation](#implementation)
- [Laravel Example](#laravel-example)
- [Testing](#testing)
- [Common Pitfalls](#common-pitfalls)
- [Trade-offs](#trade-offs)
- [Metrics](#metrics)
- [Exercises](#exercises)
- [Self-Check](#self-check)
- [See Also](#see-also)

## Overview
Factories encapsulate creation logic. They hide complex setup, enforce invariants, and allow you to swap dependencies without leaking implementation details to callers.

## When to Use
- Object construction requires multiple steps or dependencies.
- You need to create varying implementations based on runtime context.
- Construction logic changes frequently and should be isolated.

## When NOT to Use
- Plain value objects that can be instantiated directly.
- One-off constructions where the indirection adds noise.

## Implementation
```php
interface NotificationFactory
{
    public function make(string $channel): NotificationSender;
}

final class ChannelNotificationFactory implements NotificationFactory
{
    public function __construct(private readonly iterable $senders) {}

    public function make(string $channel): NotificationSender
    {
        foreach ($this->senders as $sender) {
            if ($sender->supports($channel)) {
                return $sender;
            }
        }

        throw new InvalidArgumentException("Unsupported channel: {$channel}");
    }
}
```
- **Complexity**: Time O(n) relative to registered senders.

## Laravel Example
```php
$this->app->tag([
    EmailNotificationSender::class,
    SmsNotificationSender::class,
], 'notification.senders');

$this->app->bind(NotificationFactory::class, function ($app) {
    return new ChannelNotificationFactory($app->tagged('notification.senders'));
});
```
- Use contextual binding for tenant-specific factories.
- Cache expensive dependencies inside the factory.

## Testing
- Unit test the factory with fakes implementing `NotificationSender`.
- Ensure invalid channels throw expected exceptions.
- Integration test container binding to verify dependency resolution.

## Common Pitfalls
- [ ] Factories that return `null` instead of signaling unsupported cases.
- [ ] Registering implementations manually instead of auto-discovering via configuration.

## Trade-offs
- Adds indirection but keeps construction consistent.
- Simplifies high-level code at the cost of extra files.

## Metrics
- Track factory usage count; low usage might indicate over-engineering.
- Monitor instantiation errors; factories should centralize validation.

## Exercises
1. Build a factory that returns payment gateways based on currency.
2. Write tests that ensure each gateway is resolved correctly.
3. Add a new gateway implementation without editing the factory logic.

## Self-Check
- [ ] Can you explain how factories support DIP?
- [ ] Can you locate duplicated construction logic in your project?

## See Also
- [`../../02-solid/dependency-inversion.md`](../../02-solid/dependency-inversion.md)
- [`../behavioral/strategy-pattern.md`](../behavioral/strategy-pattern.md)
- [`../structural/facade-pattern.md`](../structural/facade-pattern.md)

---
[Previous: Design Patterns Overview](../index.md) | [Home](../../README.md) | [Next: Builder Pattern](builder-pattern.md)

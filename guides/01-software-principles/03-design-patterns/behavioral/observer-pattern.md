Home / Design Patterns / Behavioral / Observer

# Observer Pattern

> **One-line summary**: Notify subscribed observers when an event occurs, enabling decoupled reactions.

## Table of Contents
- [Overview](#overview)
- [When to Use](#when-to-use)
- [When NOT to Use](#when-not-to-use)
- [Implementation](#implementation)
- [Laravel Example](#laravel-example)
- [Testing](#testing)
- [Common Pitfalls](#common-pitfalls)
- [Trade-offs](#trade-offs)
- [Exercises](#exercises)
- [See Also](#see-also)

## Overview
Observers listen to subjects and react when they change state. This pattern keeps the subject unaware of specific observers, promoting extensibility.

## When to Use
- Side effects (emails, logging, analytics) triggered by core domain events.
- Extensibility requirements where new reactions should not modify existing code.

## When NOT to Use
- Tight coupling between subject and observer is acceptable or desirable.
- Reactions must occur synchronously and deterministically in a specific order—consider facade/workflow instead.

## Implementation
```php
final class EventDispatcher
{
    /** @var array<string, callable[]> */
    private array $listeners = [];

    public function listen(string $event, callable $listener): void
    {
        $this->listeners[$event][] = $listener;
    }

    public function dispatch(string $event, mixed $payload = null): void
    {
        foreach ($this->listeners[$event] ?? [] as $listener) {
            $listener($payload);
        }
    }
}
```

## Laravel Example
- Use Laravel's events/listeners system: `OrderPlaced` event with listeners `SendOrderEmail`, `LogOrder`, `GenerateInvoice`.
- Queue listeners that perform slow operations using `ShouldQueue`.
- Monitor `storage/logs/laravel.log` or telemetry to ensure observers run correctly.

## Testing
- Trigger events in feature tests and assert on database/logging side effects.
- Use `Event::fake()` to assert events were fired without executing listeners.

## Common Pitfalls
- [ ] Observers performing heavy business logic that belongs in services.
- [ ] Failing to document observer order when it matters.

## Trade-offs
- Promotes extensibility but debugging can be harder due to implicit flows.

## Exercises
1. Emit `UserRegistered` and wire listeners for welcome email and analytics.
2. Queue the analytics listener and measure throughput improvements.

## See Also
- [`../../02-solid/open-closed.md`](../../02-solid/open-closed.md)
- [`command-pattern.md`](command-pattern.md)
- [`../structural/facade-pattern.md`](../structural/facade-pattern.md)

---
[Previous: Strategy Pattern](strategy-pattern.md) | [Home](../../README.md) | [Next: Command Pattern](command-pattern.md)

Home / Design Patterns / Structural / Adapter

# Adapter Pattern

> **One-line summary**: Translate one interface into another so incompatible components can collaborate.

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
Adapters act as translators between incompatible interfaces. They enable reuse of existing classes without altering their source code.

## When to Use
- Integrating third-party APIs with your domain models.
- Migrating legacy services to new interfaces incrementally.

## When NOT to Use
- When rewriting the consumer or provider is cheaper than maintaining an adapter.
- When adapters introduce unnecessary indirection for simple transformations.

## Implementation
```php
interface SmsSender
{
    public function send(string $number, string $message): void;
}

final class TwilioAdapter implements SmsSender
{
    public function __construct(private readonly TwilioClient $client) {}

    public function send(string $number, string $message): void
    {
        $this->client->messages->create($number, ['body' => $message]);
    }
}
```
- **Complexity**: Depends on external client; adapter adds minimal overhead.

## Laravel Example
- Bind adapters as implementations of your domain interfaces.
- Use adapters to wrap HTTP clients (e.g., Guzzle) for consistent error handling.
- Combine with factories to switch providers based on configuration.

## Testing
- Mock the underlying client to ensure adapter forwards calls with correct parameters.
- Contract tests guarantee adapter adheres to `SmsSender` expectations.

## Common Pitfalls
- [ ] Hiding failing responses—ensure adapters propagate or map exceptions properly.
- [ ] Allowing adapters to grow responsibilities beyond translation.

## Trade-offs
- Enables incremental migration but adds layers to trace when debugging.

## Exercises
1. Wrap a third-party geocoding API with an adapter implementing your domain interface.
2. Write integration tests using Laravel’s HTTP fake to simulate responses.

## See Also
- [`facade-pattern.md`](facade-pattern.md)
- [`../../02-solid/dependency-inversion.md`](../../02-solid/dependency-inversion.md)
- [`../behavioral/command-pattern.md`](../behavioral/command-pattern.md)

---
[Previous: Decorator Pattern](decorator-pattern.md) | [Home](../../README.md) | [Next: Facade Pattern](facade-pattern.md)

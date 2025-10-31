Home / Design Patterns / Structural / Decorator

# Decorator Pattern

> **One-line summary**: Wrap objects to extend behavior dynamically without modifying the original implementation.

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
- [See Also](#see-also)

## Overview
Decorators allow you to enhance an object’s functionality at runtime by wrapping it with another object that adheres to the same interface.

## When to Use
- You need to add cross-cutting concerns (logging, caching, rate limiting).
- Modifying the original class is risky or impossible.

## When NOT to Use
- Behavior could be achieved with a simple method parameter or composition.
- Too many decorators complicate debugging.

## Implementation
```php
interface ReportRenderer
{
    public function render(ReportPayload $payload): string;
}

final class CachedReportRenderer implements ReportRenderer
{
    public function __construct(
        private readonly ReportRenderer $inner,
        private readonly CacheRepository $cache
    ) {}

    public function render(ReportPayload $payload): string
    {
        $key = sha1(json_encode($payload));

        return $this->cache->remember($key, 600, fn () => $this->inner->render($payload));
    }
}
```
- **Complexity**: Rendering cost depends on inner implementation; cache access is O(1).

## Laravel Example
- Register decorators via container tags and build them in service providers.
- Use decorators for HTTP clients (add retry logic) or repositories (add caching).
- Chain decorators carefully: `new LoggedReportRenderer(new CachedReportRenderer($inner, $cache), $logger)`.

## Testing
- Mock the inner service to assert decorator behavior.
- Integration test ensures caching or logging occurs when expected.

## Common Pitfalls
- [ ] Forgetting to forward all methods of the interface.
- [ ] Layering decorators without documenting order, leading to subtle bugs.

## Trade-offs
- Flexible extension but may introduce performance overhead if stacks grow deep.

## Metrics
- Track decorator chain length. More than three layers may warrant refactoring to pipelines.

## Exercises
1. Add a logging decorator to an existing mailer implementation.
2. Compose caching and logging decorators and measure response time improvements.

## See Also
- [`../../02-solid/liskov-substitution.md`](../../02-solid/liskov-substitution.md)
- [`../behavioral/strategy-pattern.md`](../behavioral/strategy-pattern.md)
- [`adapter-pattern.md`](adapter-pattern.md)

---
[Previous: Singleton Pattern](../creational/singleton-pattern.md) | [Home](../../README.md) | [Next: Adapter Pattern](adapter-pattern.md)

Home / Design Patterns / Creational / Singleton

# Singleton Pattern (Use Sparingly)

> **One-line summary**: Restrict a class to a single instance while providing global access—primarily for legacy compatibility.

## Table of Contents
- [Overview](#overview)
- [When to Use](#when-to-use)
- [When NOT to Use](#when-not-to-use)
- [Implementation](#implementation)
- [Laravel Perspective](#laravel-perspective)
- [Testing](#testing)
- [Common Pitfalls](#common-pitfalls)
- [Trade-offs](#trade-offs)
- [Alternatives](#alternatives)
- [Exercises](#exercises)
- [See Also](#see-also)

## Overview
Singletons guarantee a single instance. Modern frameworks with IoC containers render explicit singletons largely unnecessary, but legacy code or low-level integrations might still require them.

## When to Use
- Legacy integrations where global state is unavoidable.
- Infrastructure components that must maintain a single connection pool.

## When NOT to Use
- Most application services—prefer dependency injection.
- Anywhere testability and isolation matter.

## Implementation
```php
final class ConfigRegistry
{
    private static ?self $instance = null;

    private function __construct(private array $items = []) {}

    public static function instance(): self
    {
        return self::$instance ??= new self();
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->items[$key] ?? $default;
    }
}
```
- **Complexity**: Access is O(1); but introduces hidden global state.

## Laravel Perspective
- Laravel’s container already scopes singletons via `$this->app->singleton()`. Prefer that over manual singletons.
- Use service providers to register shared instances.
- Testing is easier when singletons are container-managed and resettable between tests.

## Testing
- Global singletons complicate tests; use container bindings that can be swapped with fakes.
- If unavoidable, add reset methods and call them in `tearDown()`.

## Common Pitfalls
- [ ] Hidden state causing order-dependent tests.
- [ ] Difficulty parallelizing jobs due to shared state.

## Trade-offs
- Simplifies access to shared resources but hurts modularity and testability.

## Alternatives
- Dependency injection with container singletons.
- Configuration objects passed explicitly.

## Exercises
1. Replace a manual singleton with a container binding.
2. Write tests verifying the container returns the same instance.

## See Also
- [`../../02-solid/dependency-inversion.md`](../../02-solid/dependency-inversion.md)
- [`factory-pattern.md`](factory-pattern.md)

---
[Previous: Builder Pattern](builder-pattern.md) | [Home](../../README.md) | [Next: Decorator Pattern](../structural/decorator-pattern.md)

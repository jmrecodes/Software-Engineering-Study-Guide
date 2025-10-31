Home / Design Patterns / Creational / Builder

# Builder Pattern

> **One-line summary**: Incrementally assemble complex objects with a fluent API to maintain readability and enforce invariants.

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
Builders separate object construction from representation. They help when constructors become unwieldy or when optional parameters deserve clarity.

## When to Use
- Objects with many optional parameters or configuration combinations.
- Scenarios where construction order matters and must be explicit.
- When you need immutable objects assembled step by step.

## When NOT to Use
- Simple value objects with two or three parameters.
- Situations where a data transfer object or array is sufficient.

## Implementation
```php
final class ReportBuilder
{
    private array $filters = [];
    private array $columns = [];
    private ?string $format = null;

    public function addFilter(string $field, string $value): self
    {
        $this->filters[$field] = $value;
        return $this;
    }

    public function addColumn(string $column): self
    {
        $this->columns[] = $column;
        return $this;
    }

    public function exportAs(string $format): self
    {
        $this->format = $format;
        return $this;
    }

    public function build(): ReportRequest
    {
        return new ReportRequest(
            filters: $this->filters,
            columns: $this->columns,
            format: $this->format ?? 'csv',
        );
    }
}
```
- **Complexity**: Each builder method is O(1); `build` is O(n) relative to column count.

## Laravel Example
- Use builders for query objects, e.g., assembling `Scout` search queries.
- Combine with request validation to create typed request objects before hitting services.
- Cache builder results when constructing identical datasets.

## Testing
- Unit test that chained methods produce the correct `ReportRequest`.
- Mutation testing ensures defaults remain intact when options change.
- Integration test using the builder in controllers for end-to-end coverage.

## Common Pitfalls
- [ ] Builders that mutate the same instance after `build()` is called.
- [ ] Allowing invalid states (e.g., missing required data) without validation in `build()`.

## Trade-offs
- Improves readability but introduces extra classes.
- Encourages immutability while requiring discipline to avoid stateful misuse.

## Metrics
- Track constructor parameter counts; if >5, evaluate builder usage.
- Measure bug reports tied to optional parameters; builders should reduce them.

## Exercises
1. Create a builder for complex email campaign payloads.
2. Ensure required fields throw exceptions when missing.
3. Integrate the builder into a controller and write feature tests.

## See Also
- [`factory-pattern.md`](factory-pattern.md)
- [`../../02-solid/single-responsibility.md`](../../02-solid/single-responsibility.md)
- [`../structural/facade-pattern.md`](../structural/facade-pattern.md)

---
[Previous: Factory Pattern](factory-pattern.md) | [Home](../../README.md) | [Next: Singleton Pattern](singleton-pattern.md)

Home / Laravel / Repository Pattern

# Repository Pattern

> **One-line summary**: Encapsulate data access logic behind repositories to decouple domain services from persistence strategies.

## Table of Contents
- [Overview](#overview)
- [When to Use](#when-to-use)
- [When NOT to Use](#when-not-to-use)
- [Implementation](#implementation)
- [Query Optimization](#query-optimization)
- [Testing](#testing)
- [Common Pitfalls](#common-pitfalls)
- [Trade-offs](#trade-offs)
- [Refactoring Path](#refactoring-path)
- [Exercises](#exercises)
- [See Also](#see-also)

## Overview
Repositories hide persistence details (Eloquent, Query Builder, external APIs) behind a cohesive interface. They enable swapping storage, centralizing optimizations, and enforcing consistency.

## When to Use
- Complex queries shared across multiple services.
- Need to switch between relational DB, cache, or API backends.
- Data access includes cross-cutting concerns (tenancy, soft deletes, scopes).

## When NOT to Use
- CRUD endpoints already well-served by Eloquent models.
- The repository adds little value beyond `Model::find()`.

## Implementation
```php
interface OrderRepository
{
    public function create(OrderData $data, PaymentReceipt $receipt): Order;
    public function find(int $id): ?Order;
    public function recentForUser(int $userId, int $limit = 10): Collection;
}

final class EloquentOrderRepository implements OrderRepository
{
    public function create(OrderData $data, PaymentReceipt $receipt): Order
    {
        return Order::create([
            'user_id' => $data->userId,
            'total' => $data->total->amount(),
            'status' => OrderStatus::PLACED,
            'payment_reference' => $receipt->id(),
        ]);
    }

    public function find(int $id): ?Order
    {
        return Order::with(['items.product'])->find($id);
    }

    public function recentForUser(int $userId, int $limit = 10): Collection
    {
        return Order::query()
            ->where('user_id', $userId)
            ->latest()
            ->limit($limit)
            ->with(['items.product'])
            ->get();
    }
}
```

## Query Optimization
- Use eager loading (`with`) and select necessary columns to avoid N+1 queries.
- Apply scopes (`->whereTenant`) to enforce tenancy rules.
- Cache read-heavy queries using `remember` or repository-level cache decorators.
- Analyze queries with `DB::listen` or Laravel Telescope.

## Testing
- **Unit Tests**: Use SQLite in-memory database or mocks for repository interfaces.
- **Integration Tests**: Hit the real database using factories to ensure SQL correctness.
- **Performance Tests**: Benchmark query count using `DB::enableQueryLog()` and assert thresholds.

## Common Pitfalls
- [ ] Repositories duplicating Eloquent methods without added value.
- [ ] Overly generic repositories that leak domain knowledge.
- [ ] Ignoring transaction boundaries—wrap complex writes in transactions.

## Trade-offs
- Adds structure and testability but can become boilerplate if overused.

## Refactoring Path
1. Identify shared query logic in controllers/services.
2. Extract to repository methods and update callers.
3. Add tests ensuring repositories return expected models and relations.

## Exercises
1. Build a repository that caches expensive analytics queries.
2. Implement a second repository using Scout or external API and swap through DI.
3. Measure query counts before and after repository introduction.

## See Also
- [`service-layer-pattern.md`](service-layer-pattern.md)
- [`../02-solid/interface-segregation.md`](../02-solid/interface-segregation.md)
- [`../03-design-patterns/structural/decorator-pattern.md`](../03-design-patterns/structural/decorator-pattern.md)

---
[Previous: Service Layer Pattern](service-layer-pattern.md) | [Home](../README.md) | [Next: Action Classes](action-classes.md)

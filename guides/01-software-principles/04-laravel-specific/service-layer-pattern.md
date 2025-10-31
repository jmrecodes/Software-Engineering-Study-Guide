Home / Laravel / Service Layer

# Service Layer Pattern

> **One-line summary**: Centralize business orchestration in services so controllers remain thin and infrastructure stays interchangeable.

## Table of Contents
- [Overview](#overview)
- [When to Use](#when-to-use)
- [When NOT to Use](#when-not-to-use)
- [Architecture Diagram](#architecture-diagram)
- [Implementation](#implementation)
- [Performance Considerations](#performance-considerations)
- [Testing](#testing)
- [Common Pitfalls](#common-pitfalls)
- [Trade-offs](#trade-offs)
- [Refactoring Path](#refactoring-path)
- [Exercises](#exercises)
- [See Also](#see-also)

## Overview
Service layers act as the domain orchestration tier. They convert controller inputs into domain operations, coordinate repositories, emit events, and return results without concerning themselves with HTTP plumbing.

## When to Use
- Controllers or jobs handle multiple responsibilities (validation, querying, side effects).
- Several entry points (HTTP, CLI, queue) share the same business logic.

## When NOT to Use
- Simple CRUD endpoints where controllers already remain succinct.
- Scenarios where business logic is trivial and unlikely to grow.

## Architecture Diagram
```
[HTTP/CLI Job] --> [Controller] --> [Service Layer] --> [Repositories, Events, Jobs]
                                         |
                                         --> [Policies, Validators]
```

## Implementation
```php
final class OrderService
{
    public function __construct(
        private readonly OrderRepository $orders,
        private readonly InventoryService $inventory,
        private readonly PaymentGateway $gateway,
        private readonly EventDispatcher $events
    ) {}

    public function place(OrderData $data): Order
    {
        $this->inventory->reserve($data->items);

        $receipt = $this->gateway->charge($data->total, $data->paymentMethod);

        $order = $this->orders->create($data, $receipt);

        $this->events->dispatch(new OrderPlaced($order));

        return $order;
    }
}
```

## Performance Considerations
- Wrap reservations and order creation in database transactions.
- Cache read-heavy operations within services using tagged cache.
- Dispatch slow processes (emails, exports) to queues.
- Instrument services with metrics (execution time, failure rates).

## Testing
- **Unit Tests**: Mock repositories and gateways to assert collaboration.
- **Integration Tests**: Use `RefreshDatabase` and in-memory queues to verify behavior end-to-end.
- **Performance Tests**: Leverage Laravel Telescope or custom metrics to monitor service latency.

## Common Pitfalls
- [ ] Services growing into "God objects" by accumulating unrelated workflows.
- [ ] Controllers bypassing services for "quick fixes", reintroducing duplication.

## Trade-offs
- Adds indirection but promotes reuse across entry points. Keep services cohesive.

## Refactoring Path
1. Extract business logic from controller into a new service method.
2. Inject dependencies (repositories, gateways) via constructor.
3. Update controllers, jobs, and listeners to call the service.
4. Add unit tests for the service and thin out controller tests.

## Exercises
1. Move checkout logic from controller to service and ensure feature tests pass.
2. Instrument the service with logging middleware measuring execution time.

## See Also
- [`repository-pattern.md`](repository-pattern.md)
- [`../02-solid/single-responsibility.md`](../02-solid/single-responsibility.md)
- [`../03-design-patterns/behavioral/command-pattern.md`](../03-design-patterns/behavioral/command-pattern.md)

---
[Previous: Laravel Overview](index.md) | [Home](../README.md) | [Next: Repository Pattern](repository-pattern.md)

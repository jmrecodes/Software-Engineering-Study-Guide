Home / Code Examples / Before & After

# Before & After Comparisons

> **One-line summary**: Side-by-side refactors demonstrating how applying these principles improves structure, performance, and testability.

## Table of Contents
- [SRP: Order Controller Refactor](#srp-order-controller-refactor)
- [OCP: Payment Gateway Extension](#ocp-payment-gateway-extension)
- [DRY: Tenant Scope Consolidation](#dry-tenant-scope-consolidation)
- [Performance Benchmarks](#performance-benchmarks)
- [Commit Log Example](#commit-log-example)
- [Self-Check](#self-check)

## SRP: Order Controller Refactor
**Before** (Controller doing everything):
```php
public function store(Request $request)
{
    $validated = $request->validate([...]);

    $product = Product::find($validated['product_id']);
    if ($product->stock < $validated['quantity']) {
        throw ValidationException::withMessages(['product_id' => 'Insufficient stock']);
    }

    $order = Order::create([...]);

    Mail::to(auth()->user())->queue(new OrderPlacedMail($order));
    Log::info('Order created', ['order' => $order->id]);
}
```

**After** (Service + listeners):
```php
public function store(CreateOrderRequest $request, OrderService $service)
{
    $order = $service->create($request->validated(), $request->user());

    return new OrderResource($order);
}
```
- **Result**: Controller LOC down 60%, isolated tests for validation, inventory, and notifications.

## OCP: Payment Gateway Extension
**Before** (Switch statement):
```php
switch ($method) {
    case 'stripe':
        // charge stripe
        break;
    case 'paypal':
        // charge paypal
        break;
}
```

**After** (Strategy + factory):
```php
$gateway = $factory->make($request->input('method'));
$receipt = $gateway->charge($amount);
```
- **Result**: Adding `crypto` required a new class only; zero existing files changed.

## DRY: Tenant Scope Consolidation
**Before**: Tenant condition repeated in several repositories.

**After**:
```php
final class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $builder->where('tenant_id', auth()->user()->tenant_id);
    }
}
```
- **Result**: 12 duplicate conditions removed. Queries consistently scoped with index usage.

## Performance Benchmarks
| Scenario | Before | After |
| --- | --- | --- |
| Order placement request | 240ms avg | 170ms avg (async mail + cached products) |
| Payment gateway integration addition | 4 file edits | 1 new class, 0 edits |
| Tenant-report query | 12 queries | 4 queries (eager loading + scope) |

Measured using Laravel Telescope sample set of 100 requests. [Inference]

## Commit Log Example
```
commit 91e21aa (2025-10-12)
Author: Team Lead

    refactor: extract order service and event listeners

    - move validation to CreateOrderRequest
    - add OrderService orchestrating inventory + payment
    - register listeners for mail/log operations
    - add unit + feature tests for new service
```

## Self-Check
- [ ] Can you identify similar hotspots in your project?
- [ ] Can you benchmark before/after performance?
- [ ] Can you craft a commit message summarizing structural improvements?

---
[Previous: Testing Principles](../04-laravel-specific/testing-principles.md) | [Home](../README.md) | [Next: Real-World Scenarios](real-world-scenarios.md)

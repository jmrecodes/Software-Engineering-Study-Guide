Home / SOLID / Single Responsibility Principle

# Single Responsibility Principle (SRP)

> **One-line summary**: Give every class, module, or function exactly one reason to change so that responsibilities stay isolated and predictable.

## Table of Contents
- [Overview](#overview)
- [The Problem](#the-problem)
- [The Solution](#the-solution)
- [Refactoring Path](#refactoring-path)
- [Examples](#examples)
- [Laravel Implementation](#laravel-implementation)
- [Testing](#testing)
- [Common Pitfalls](#common-pitfalls)
- [Trade-offs](#trade-offs)
- [Metrics](#metrics)
- [When to Apply](#when-to-apply)
- [Related Principles](#related-principles)
- [Exercises](#exercises)
- [Review Checklist](#review-checklist)
- [Self-Check Questions](#self-check-questions)

## Overview
SRP borrows from real-world specialization. In a restaurant kitchen everyone has a distinct job—chef, server, dishwasher—so workflow remains smooth. Translating that to code, each artifact should own one responsibility. When that boundary blurs, edits become risky and time-consuming.

## The Problem
Monolithic classes accumulate validation, persistence, networking, and presentation code. Changing one aspect risks unintended side effects elsewhere. Testing becomes tedious because there are too many collaborators to orchestrate.

## The Solution
Slice responsibilities along the seams of your application: HTTP concerns, domain logic, persistence, and side effects. Each slice gets its own class or layer. When a change request arrives, you only touch the class that owns the responsibility.

## Refactoring Path
1. **Inventory responsibilities**: Highlight validation, orchestration, persistence, notification, and formatting concerns in the bloated class.
2. **Group by axis of change**: Cluster code into categories (e.g., "validation rules", "order creation", "email side effects").
3. **Extract abstractions**: Move each cluster into a dedicated class or service. Keep method signatures explicit.
4. **Wire orchestration**: Introduce a coordinator (often a service) that composes the extracted pieces.
5. **Add tests**: Cover the extracted units first, then add integration tests for the orchestrator.
6. **Delete dead code**: Remove leftover conditional branches and unused imports to minimize regression risk.

## Examples

### Basic Example (Beginner)
```php
final class UsernameValidator
{
    public function validate(string $username): void
    {
        if ($username === '') {
            throw new InvalidArgumentException('Username is required.');
        }

        if (strlen($username) < 3) {
            throw new InvalidArgumentException('Username must be at least three characters.');
        }
    }
}
```
- **Responsibility**: Enforce username rules only.
- **Complexity**: Time O(n) due to string length; Space O(1).

### Intermediate Example (Intermediate)
```php
final class InvoiceCreator
{
    public function __construct(
        private readonly InvoiceRepository $repository,
        private readonly TaxCalculator $taxCalculator,
        private readonly ClockInterface $clock
    ) {}

    public function create(array $payload): Invoice
    {
        $data = InvoiceData::fromArray($payload);

        $taxedLines = $this->taxCalculator->apply($data->items);

        return $this->repository->store(
            $data->toModel(
                issuedAt: $this->clock->now(),
                taxedLines: $taxedLines
            )
        );
    }
}
```
- **Responsibility**: Orchestrate invoice creation only; validation and persistence live elsewhere.
- **Complexity**: Time O(n log n) if tax calculation sorts items; Space O(n) for transformed lines.

### Advanced Example (Advanced)
```php
final class OrderPlacementWorkflow
{
    public function __construct(
        private readonly OrderValidator $validator,
        private readonly CartService $cartService,
        private readonly PaymentGateway $gateway,
        private readonly OrderLogger $logger,
        private readonly OrderFulfillmentDispatcher $dispatcher
    ) {}

    public function place(OrderRequest $request): Order
    {
        $this->validator->assert($request);

        $cart = $this->cartService->hydrate($request->cartId);

        $payment = $this->gateway->charge($cart->total(), $request->paymentMethod);

        $order = $cart->toOrder($payment);

        $this->dispatcher->dispatch($order);

        $this->logger->log($order);

        return $order;
    }
}
```
- **Responsibility**: Coordinate the workflow; individual collaborators specialize.
- **Complexity**: Time O(n) relative to cart lines; Space O(n) for order aggregates.
- **Performance considerations**: Lazy-load heavy collaborators, memoize cart totals, and log asynchronously via queue jobs when throughput matters.

## Laravel Implementation
- **Controllers**: Defer validation to [`FormRequest`](https://laravel.com/docs/validation#form-request-validation) classes and business logic to services or actions.
- **Services**: Encapsulate orchestration similar to `OrderPlacementWorkflow`. Bind them in the container for testability.
- **Repositories**: Keep data access dedicated. Use query scopes and eager loading to eliminate N+1 queries.
- **Events & Listeners**: Dispatch domain events (e.g., `OrderPlaced`) and register listeners for emails, logging, or invoicing.
- **Performance considerations**: Cache immutable lookups, queue slow notifications, and leverage chunked database operations when processing large collections.
- **Database optimization**: Limit columns with `select`, batch writes with transactions, and prefetch relationships using `->with()` in repositories.

## Testing
- **Unit Tests**: Mock dependencies to assert the orchestrator calls each collaborator exactly once. Example with PHPUnit:
```php
public function test_it_places_an_order()
{
    $validator = $this->createMock(OrderValidator::class);
    $cartService = $this->createMock(CartService::class);
    $gateway = $this->createMock(PaymentGateway::class);
    $logger = $this->createMock(OrderLogger::class);
    $dispatcher = $this->createMock(OrderFulfillmentDispatcher::class);

    $cart = TestCartFactory::make(total: Money::from(4999));
    $order = $cart->toOrder(new PaymentReference('abc-123'));

    $validator->expects($this->once())->method('assert');
    $cartService->expects($this->once())->method('hydrate')->willReturn($cart);
    $gateway->expects($this->once())->method('charge')->willReturn(new PaymentReference('abc-123'));
    $dispatcher->expects($this->once())->method('dispatch')->with($order);
    $logger->expects($this->once())->method('log')->with($order);

    $workflow = new OrderPlacementWorkflow($validator, $cartService, $gateway, $logger, $dispatcher);

    self::assertSame($order, $workflow->place(TestOrderRequest::make()));
}
```
- **Integration Tests**: Use Laravel's `RefreshDatabase` trait to verify modules collaborate while respecting boundaries.
- **TDD Workflow**: Write failing tests for the extracted collaborator before moving code.

## Common Pitfalls
- [ ] Leaving validation in controllers after creating a `FormRequest`.
- [ ] Allowing services to manipulate HTTP request objects directly.
- [ ] Reintroducing side effects (mailing, logging) into orchestrators "for convenience".

## Trade-offs
- **Pros**: Lower cognitive load, faster tests, easier reuse of business logic.
- **Cons**: More files/classes, potential over-abstraction for very small features.
- **Mitigation**: Start extracting once duplication or multi-axis change requests appear.

## Metrics
- Track class size (lines of code) and constructor parameter counts; spikes indicate responsibility drift.
- Monitor unit test execution time. If orchestration tests require heavy bootstrapping, responsibilities may be mixed.
- Use static analysis (e.g., PHPStan's `TooManyMethodsRule`) to enforce thresholds.

## When to Apply
✅ **Use when:**
- Interleaved concerns make testing or deployment risky.
- Different team members need to work on distinct aspects simultaneously.

❌ **Avoid when:**
- A function truly does one thing and extracting would harm readability.
- Prototype code is being thrown away (avoid premature refactoring).

## Related Principles
- [`open-closed.md`](open-closed.md): SRP simplifies extending behavior without edits.
- [`interface-segregation.md`](interface-segregation.md): Small interfaces help enforce single responsibilities.
- [`dependency-inversion.md`](dependency-inversion.md): Abstractions keep responsibilities swappable.

## Exercises
1. **Beginner**: Extract validation logic from a controller into a dedicated `FormRequest` and write a unit test.
2. **Intermediate**: Refactor a service mixing persistence and notifications into separate classes wired by an orchestrator.
3. **Advanced**: Introduce domain events for a complex workflow and measure reduced test setup time.

## Review Checklist
- [ ] Does the class have only one high-level reason to change?
- [ ] Are external side effects delegated to listeners, jobs, or dedicated services?
- [ ] Can each collaborator be unit tested independently without the others?

## Self-Check Questions
- [ ] Can you explain SRP to a junior developer with a non-technical analogy?
- [ ] Can you identify at least two SRP violations in your current project?
- [ ] Can you outline a refactoring plan that restores SRP without breaking behavior?

---
[Previous: SOLID Overview](index.md) | [Home](../README.md) | [Next: Open/Closed Principle](open-closed.md)

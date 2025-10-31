Home / Design Patterns / Behavioral / Strategy

# Strategy Pattern

> **One-line summary**: Define a family of algorithms, encapsulate each one, and make them interchangeable at runtime.

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
Strategy separates the "what" from the "how". It allows you to swap implementations without changing the client, reinforcing OCP.

## When to Use
- Multiple algorithms or integrations fulfill the same role (payment gateways, shipping calculators).
- Decision depends on runtime data or configuration.

## When NOT to Use
- There is only one implementation and no expectation of change.
- Strategies differ wildly in inputs/outputs, making a common interface awkward.

## Implementation
```php
interface PaymentGateway
{
    public function charge(Money $amount, array $metadata = []): PaymentReceipt;
}

final class PaymentProcessor
{
    public function __construct(private readonly PaymentGateway $gateway) {}

    public function process(Money $amount): PaymentReceipt
    {
        return $this->gateway->charge($amount);
    }
}
```
- Swap the concrete `PaymentGateway` at runtime.

## Laravel Example
- Use contextual binding to choose a gateway per tenant:
```php
$this->app->when(TenantAProcessor::class)
    ->needs(PaymentGateway::class)
    ->give(fn () => app(StripeGateway::class));
```
- Combine with feature flags to toggle strategies gradually.
- Cache credentials per strategy to avoid repeated lookups.

## Testing
- Contract tests ensure each strategy behaves identically.
- Use fakes to simulate third-party responses.
- Mutation testing uncovers differences between strategies.

## Common Pitfalls
- [ ] Strategies that require different method signatures—split interfaces if needed.
- [ ] Hard-coding strategy selection logic instead of delegating to a factory or container.

## Trade-offs
- Increases flexibility while adding indirection. Document selection rules clearly.

## Metrics
- Track the number of conditionals removed by introducing strategy.
- Measure time-to-add-new-integration before and after strategy adoption.

## Exercises
1. Implement payment strategies for Stripe, Braintree, and a fake gateway.
2. Add a factory that resolves the strategy based on the `payment_method` request input.
3. Write integration tests for each strategy using HTTP fakes.

## See Also
- [`../../02-solid/open-closed.md`](../../02-solid/open-closed.md)
- [`../creational/factory-pattern.md`](../creational/factory-pattern.md)
- [`observer-pattern.md`](observer-pattern.md)

---
[Previous: Facade Pattern](../structural/facade-pattern.md) | [Home](../../README.md) | [Next: Observer Pattern](observer-pattern.md)

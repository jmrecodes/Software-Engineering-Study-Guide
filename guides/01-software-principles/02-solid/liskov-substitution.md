Home / SOLID / Liskov Substitution Principle

# Liskov Substitution Principle (LSP)

> **One-line summary**: Subtypes must be perfectly substitutable for their base types without altering expected behavior.

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
If a client relies on a contract, any replacement must honor that agreement. Think of universal power adapters—if they claim to support a socket type but fry your device, the promise is broken. LSP formalizes that promise in code.

## The Problem
Child classes that modify preconditions, postconditions, or return types break expectations. Consumers either fail at runtime or require type checks, defeating polymorphism.

## The Solution
Model contracts as interfaces or abstract classes with stable pre/post-conditions. Ensure each implementation honors them. Favor composition over inheritance when constraints diverge.

## Refactoring Path
1. **Document the contract**: Clarify parameter expectations, return types, and side effects.
2. **Add regression tests**: Write tests that exercise the contract using the base type.
3. **Adjust subtypes**: Refactor or split subtypes that cannot honor the contract.
4. **Consider composition**: When behavior deviates, create a separate class instead of forcing inheritance.
5. **Enforce via static analysis**: Tools like PHPStan can ensure signatures remain compatible.

## Examples

### Basic Example (Beginner)
```php
interface PaymentGateway
{
    public function charge(Money $amount, array $options = []): PaymentReference;
}
```
- **Contract**: Always returns a `PaymentReference` or throws a documented exception.

### Intermediate Example (Intermediate)
```php
final class NullPaymentGateway implements PaymentGateway
{
    public function charge(Money $amount, array $options = []): PaymentReference
    {
        return new PaymentReference('test-token');
    }
}
```
- **Use case**: Substitutable in tests without altering client assumptions.
- **Complexity**: Constant time and space.

### Advanced Example (Advanced)
```php
final class RateLimitedGateway implements PaymentGateway
{
    public function __construct(
        private readonly PaymentGateway $inner,
        private readonly RateLimiter $limiter
    ) {}

    public function charge(Money $amount, array $options = []): PaymentReference
    {
        $key = $options['customer_id'] ?? 'global';

        if (!$this->limiter->allow($key)) {
            throw new RateLimitExceeded('Payment throttled.');
        }

        return $this->inner->charge($amount, $options);
    }
}
```
- **Complexity**: Time O(1); Space O(1).
- **Performance considerations**: Cache limiter tokens, batch expensive checks, and ensure fallback paths keep the contract intact.

## Laravel Implementation
- Swap service bindings in `AppServiceProvider` to test substitutability (`StripeGateway`, `FakeGateway`, etc.).
- Use interface type hints in controllers and services to guarantee any implementation works (`PaymentGateway` contract).
- Avoid returning arrays when the interface promises objects—otherwise responses differ per implementation.
- When working with Eloquent repositories, keep return types consistent (`Collection`, `Paginator`, or DTOs) across implementations.
- **Database optimization**: If a substitute uses different query strategies, ensure they yield equivalent results (eager loading, consistent ordering).

## Testing
- **Contract Test Suite**: Create a reusable test trait that accepts a factory for each implementation and verifies identical behavior.
- **Mutation Testing**: Tools like Infection can highlight differences when substituting implementations.
- **Static Analysis**: Enable PHPStan strict rules to detect signature mismatches.

```php
abstract class PaymentGatewayContractTest extends TestCase
{
    abstract protected function gateway(): PaymentGateway;

    public function test_charge_returns_reference(): void
    {
        $reference = $this->gateway()->charge(Money::from(1000));

        self::assertInstanceOf(PaymentReference::class, $reference);
        self::assertNotEmpty($reference->id);
    }
}
```

## Common Pitfalls
- [ ] Changing return types or throwing new exception types in a child class.
- [ ] Tightening parameter constraints (e.g., rejecting inputs accepted by the base).
- [ ] Leaking infrastructure details (arrays, HTTP responses) that differ per implementation.

## Trade-offs
- **Pros**: Predictable polymorphism, simpler swapping for tests, reusable code paths.
- **Cons**: Sometimes restricts flexibility if contracts are too generic; may require more abstractions.
- **Mitigation**: Split interfaces when implementations legitimately diverge (see ISP).

## Metrics
- Track the number of `instanceof` checks; rising counts suggest LSP violations.
- Monitor polymorphic bugs (QA reports). Frequent substitution errors imply contract drift.
- Use static analysis to enforce signature compatibility.

## When to Apply
✅ **Use when:**
- You want to swap infrastructure (e.g., queue, cache, payment) without client changes.
- Writing reusable libraries consumed by multiple teams.

❌ **Avoid when:**
- Domain variants truly behave differently (e.g., synchronous vs. asynchronous flows) and deserve separate contracts.
- API responses differ drastically; forcing substitution would hide important nuance.

## Related Principles
- [`interface-segregation.md`](interface-segregation.md): Smaller interfaces make it easier to uphold LSP.
- [`dependency-inversion.md`](dependency-inversion.md): Abstractions enable substitutability across modules.
- [`../03-design-patterns/structural/decorator-pattern.md`](../03-design-patterns/structural/decorator-pattern.md): Decorators rely on LSP to wrap objects safely.

## Exercises
1. **Beginner**: Create a contract test ensuring fake and real payment gateways behave identically.
2. **Intermediate**: Refactor a subclass that throws additional exceptions into a composition-based decorator.
3. **Advanced**: Replace inheritance with composition in a reporting system and measure the reduction in `instanceof` checks.

## Review Checklist
- [ ] Do all implementations honor the documented pre/post-conditions?
- [ ] Are consumers free of type checks or conditional logic based on the implementation?
- [ ] Do tests cover each implementation via the same contract suite?

## Self-Check Questions
- [ ] Can you describe how LSP differs from simple interface implementation?
- [ ] Can you name a recent bug caused by substituting an implementation?
- [ ] Can you fix that bug by adjusting the contract or separating responsibilities?

---
[Previous: Open/Closed Principle](open-closed.md) | [Home](../README.md) | [Next: Interface Segregation Principle](interface-segregation.md)

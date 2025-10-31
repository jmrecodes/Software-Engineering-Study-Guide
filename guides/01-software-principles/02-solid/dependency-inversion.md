Home / SOLID / Dependency Inversion Principle

# Dependency Inversion Principle (DIP)

> **One-line summary**: Depend on abstractions instead of concrete implementations so high-level policy remains decoupled from low-level details.

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
DIP ensures high-level business rules (policy) do not rely on implementation details (mechanisms). It is akin to using universal plugs and adapters so appliances work in any country—devices rely on a standard rather than a specific outlet.

## The Problem
Without DIP, high-level services instantiate concrete dependencies directly. Swapping infrastructure (mail providers, payment gateways, databases) requires editing business logic. Tests become brittle because setup demands real services or complex mocks.

## The Solution
Introduce abstractions (interfaces) that represent the behavior the high-level module needs. Low-level modules implement these abstractions. The container wires them together, so swapping implementations never touches the high-level code.

## Refactoring Path
1. **Identify concrete dependencies**: Look for `new SomeService()` inside high-level classes.
2. **Extract interfaces**: Define contracts capturing required behavior.
3. **Implement contracts**: Let existing classes implement the new interfaces.
4. **Bind via container**: Configure the IoC container or factory to resolve abstractions.
5. **Update constructors**: Inject interfaces rather than concrete classes.
6. **Write tests**: Use fake implementations or mocks to verify high-level modules.

## Examples

### Basic Example (Beginner)
```php
interface Mailer
{
    public function send(Mailable $message): void;
}
```
- **Complexity**: Interface definition only.

### Intermediate Example (Intermediate)
```php
final class WelcomeUser
{
    public function __construct(
        private readonly UserRepository $users,
        private readonly Mailer $mailer
    ) {}

    public function handle(int $userId): void
    {
        $user = $this->users->find($userId);

        if ($user === null) {
            throw new RuntimeException('User not found');
        }

        $this->mailer->send(new WelcomeMail($user));
    }
}
```
- **Complexity**: Time O(1); Space O(1).
- **Observation**: High-level logic depends on abstractions only.

### Advanced Example (Advanced)
```php
final class PaymentService
{
    public function __construct(
        private readonly PaymentGateway $gateway,
        private readonly EventDispatcher $events,
        private readonly AuditLogger $logger
    ) {}

    public function process(PaymentRequest $request): PaymentReceipt
    {
        $receipt = $this->gateway->charge($request->amount(), $request->metadata());

        $this->events->dispatch(new PaymentProcessed($receipt));
        $this->logger->log($receipt);

        return $receipt;
    }
}
```
- **Performance considerations**: Swap `AuditLogger` with an async queue implementation without touching `PaymentService`.
- **Complexity**: Time depends on gateway and logging; DIP makes substitution trivial.

## Laravel Implementation
- **Service Container**: Bind interfaces in `AppServiceProvider`.
```php
$this->app->bind(Mailer::class, function () {
    return new PostmarkMailer(config('services.postmark.token'));
});
```
- **Contextual Binding**: Use `$this->app->when(OrderService::class)->needs(PaymentGateway::class)...` to supply different implementations per context.
- **Contracts**: Lean on Laravel's built-in contracts (`Illuminate\Contracts\Cache\Repository`) for core services.
- **Database optimization**: Repositories implementing `UserRepository` can vary between Eloquent, raw SQL, or cached adapters without client changes.
- **Queues and Jobs**: Inject interfaces into jobs, letting tests supply in-memory fakes.

## Testing
- **Fakes**: Provide fake implementations of interfaces to isolate tests from infrastructure.
- **Mocks**: Use PHPUnit mocks to assert interactions without network calls.
- **TDD**: Write tests against the abstract contract before implementing the concrete class.
- **Performance Benchmarks**: Swap in-memory versus Redis cache implementations and measure impact without altering business code.

## Common Pitfalls
- [ ] Binding interfaces to closures in test setups but forgetting production bindings.
- [ ] Introducing an interface per class without a clear abstraction (premature generalization).
- [ ] Leaking concrete class methods through the interface (defeating the abstraction).

## Trade-offs
- **Pros**: Easier testing, infrastructure swaps, and modularity.
- **Cons**: More upfront design, potential interface bloat if abstractions are guessed prematurely.
- **Mitigation**: Introduce abstractions when variation or testing pain appears.

## Metrics
- Track constructor `new` calls; they should trend toward zero in high-level modules.
- Monitor test execution time. Faster tests indicate successful decoupling from slow dependencies.
- Count container bindings; ensure each interface has exactly one default binding.

## When to Apply
✅ **Use when:**
- You need to switch infrastructure without feature freezes.
- Tests require fakes or mocks to avoid external systems.

❌ **Avoid when:**
- There is a single implementation with no foreseeable variation and the abstraction would add noise.
- Critical domain logic could be obfuscated by a generic interface.

## Related Principles
- [`open-closed.md`](open-closed.md): DIP enables OCP by routing interactions through abstractions.
- [`single-responsibility.md`](single-responsibility.md): Clear boundaries make dependency inversion easier.
- [`../03-design-patterns/creational/factory-pattern.md`](../03-design-patterns/creational/factory-pattern.md): Factories create objects that adhere to DIP.

## Exercises
1. **Beginner**: Extract an interface for an email service and update the container bindings.
2. **Intermediate**: Introduce contextual bindings for payment gateways per tenant and ensure no high-level code changes.
3. **Advanced**: Benchmark two cache implementations by swapping container bindings and documenting the results.

## Review Checklist
- [ ] Do high-level classes depend on interfaces instead of concretes?
- [ ] Are implementations registered in a single place (service provider)?
- [ ] Do tests run against fake implementations without network or filesystem usage?

## Self-Check Questions
- [ ] Can you explain why DIP is foundational for testability?
- [ ] Can you list modules currently instantiating concretes internally?
- [ ] Can you outline the steps to inject abstractions there?

---
[Previous: Interface Segregation Principle](interface-segregation.md) | [Home](../README.md) | [Next: Design Patterns Overview](../03-design-patterns/index.md)

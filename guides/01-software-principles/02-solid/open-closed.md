Home / SOLID / Open-Closed Principle

# Open/Closed Principle (OCP)

> **One-line summary**: Design modules so they can be safely extended with new behavior without modifying existing, stable code.

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
OCP mirrors how smartphone operating systems allow new apps without rewriting the OS. In software, the goal is to add features by plugging into extension points instead of editing the core module and risking regressions.

## The Problem
When business rules are hard-coded, each new requirement forces edits to the same core class. Merge conflicts multiply, regression risk climbs, and release cycles slow down.

## The Solution
Introduce abstractions, configuration, and composition so new behavior lives in separate modules. The existing component exposes an extension surface (interfaces, pipelines, events) and delegates to injected collaborators.

## Refactoring Path
1. **Detect hotspots**: Identify classes frequently modified for new variants (payment types, export formats, notification channels).
2. **Introduce an abstraction**: Define an interface that captures what varies (e.g., `PaymentGateway`).
3. **Register implementations**: Create concrete classes per variant and bind them via configuration or a registry.
4. **Delegate via composition**: Let the original class resolve an implementation based on runtime context without `switch` statements.
5. **Add tests per implementation**: Ensure each class fulfills the contract without altering others.

## Examples

### Basic Example (Beginner)
```php
interface DiscountRule
{
    public function appliesTo(Basket $basket): bool;
    public function apply(Basket $basket): Money;
}
```
- **Responsibility**: Define the extension surface for discount rules.
- **Complexity**: Interface definition only.

### Intermediate Example (Intermediate)
```php
final class DiscountEngine
{
    /** @var DiscountRule[] */
    public function __construct(private array $rules) {}

    public function calculate(Basket $basket): Money
    {
        $total = Money::zero();

        foreach ($this->rules as $rule) {
            if ($rule->appliesTo($basket)) {
                $total = $total->add($rule->apply($basket));
            }
        }

        return $total;
    }
}
```
- **Complexity**: Time O(n) relative to rule count; Space O(1).
- **Extensibility**: Add a new rule by binding another `DiscountRule` implementation without changing `DiscountEngine`.

### Advanced Example (Advanced)
```php
final class ReportGenerator
{
    public function __construct(private readonly iterable $renderers) {}

    public function render(string $format, ReportPayload $payload): string
    {
        foreach ($this->renderers as $renderer) {
            if ($renderer->supports($format)) {
                return $renderer->render($payload);
            }
        }

        throw new InvalidArgumentException("Unsupported format: {$format}");
    }
}
```
- **Complexity**: Time O(n) to locate the renderer; Space O(1).
- **Performance considerations**: Cache renderers in a keyed array or use the Laravel container tagging feature to avoid iteration in hot paths.

## Laravel Implementation
- **Service Provider Binding**: Use `app()->tag()` or `->bind()` to register implementations. Example:
```php
$this->app->tag([
    CsvReportRenderer::class,
    PdfReportRenderer::class,
], 'report.renderers');

$this->app->bind(ReportGenerator::class, function ($app) {
    return new ReportGenerator($app->tagged('report.renderers'));
});
```
- **Pipelines & Middleware**: Laravel's `Pipeline` and HTTP middleware stacks are OCP playgrounds—append new steps without editing the dispatcher.
- **Events**: Dispatch domain events (e.g., `InvoiceFinalized`) and register listeners for new behaviors.
- **Database optimization**: When adding new strategies that hit the database, prefer eager loading and caching per strategy to avoid repeating queries across implementations.

## Testing
- **Contract Tests**: Create an abstract test case that exercises the interface and run it against each implementation using PHPUnit's `@dataProvider`.
- **Integration Tests**: Assert the registrar or factory resolves the correct implementation based on configuration.
- **Regression Tests**: Capture existing behavior in snapshot tests before refactoring to OCP to ensure parity.

## Common Pitfalls
- [ ] Leaving `switch`/`match` statements that require editing for each new variant.
- [ ] Hard-coding class names instead of using the service container.
- [ ] Forgetting to document or test new implementations, causing silent failures.

## Trade-offs
- **Pros**: Lower regression risk, simpler feature toggles, easier third-party integrations.
- **Cons**: Additional indirection, potential over-abstraction if future variants never arrive.
- **Mitigation**: Start with concrete implementations and extract abstractions when the second variant appears.

## Metrics
- Count edits to core modules vs. newly added classes. A downward trend indicates OCP success.
- Track time-to-release for variant features; OCP should shorten the cycle.
- Use architecture linting (e.g., Deptrac) to ensure modules depend on abstractions only.

## When to Apply
✅ **Use when:**
- You frequently ship new variants or integrations.
- Multiple teams need to add behavior without coordinating code changes.

❌ **Avoid when:**
- The requirement surface is stable and unlikely to grow.
- The abstraction would hide critical domain differences.

## Related Principles
- [`single-responsibility.md`](single-responsibility.md): SRP extracts responsibilities that OCP can later extend.
- [`dependency-inversion.md`](dependency-inversion.md): DIP supplies the abstractions that enable OCP.
- [`../03-design-patterns/behavioral/strategy-pattern.md`](../03-design-patterns/behavioral/strategy-pattern.md) (to be created): Strategy pattern operationalizes OCP.

## Exercises
1. **Beginner**: Replace a `match` statement for notification channels with a strategy interface and implementations.
2. **Intermediate**: Tag multiple discount calculators in a service provider and inject them into a checkout service.
3. **Advanced**: Implement a plugin architecture where new payment gateways are auto-discovered via configuration.

## Review Checklist
- [ ] Does the core class avoid editing when new variants appear?
- [ ] Are implementations registered through configuration or the container?
- [ ] Do tests cover each implementation against the shared contract?

## Self-Check Questions
- [ ] Can you explain how OCP reduces regression risk during feature growth?
- [ ] Can you point to extension seams in your current project?
- [ ] Can you add a new variant without touching existing classes?

---
[Previous: Single Responsibility Principle](single-responsibility.md) | [Home](../README.md) | [Next: Liskov Substitution Principle](liskov-substitution.md)

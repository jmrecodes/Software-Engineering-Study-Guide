Home / Foundations / DRY

# DRY (Don't Repeat Yourself)

> **One-line summary**: Ensure every piece of knowledge has a single, authoritative representation.

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
DRY mirrors the idea of a master recipe card instead of scattered sticky notes. When knowledge lives in one place, updates are swift and accurate.

## The Problem
Copy-pasted logic multiplies maintenance effort. Fixing a bug requires updating each duplicate. Missing one introduces inconsistent behavior and hidden defects.

## The Solution
Extract shared logic into reusable functions, services, or configuration. Reference it consistently. Document the single source to reduce future duplication.

## Refactoring Path
1. **Inventory duplication**: Use static analysis or search for repeated code blocks.
2. **Classify knowledge**: Determine whether duplication is business logic, validation, or configuration.
3. **Choose a shared abstraction**: Trait, helper, service, or configuration file depending on context.
4. **Replace duplicates**: Refactor each instance to use the new abstraction.
5. **Add regression tests**: Ensure the shared logic behaves correctly under varied inputs.

## Examples

### Basic Example (Beginner)
```php
final class PhoneSanitizer
{
    public static function normalize(string $number): string
    {
        return preg_replace('/\D+/', '', $number);
    }
}
```
- **Complexity**: Time O(n); Space O(1).
- **Usage**: Centralize number formatting across controllers and services.

### Intermediate Example (Intermediate)
```php
final class MoneyFormatter
{
    public function __construct(private readonly string $locale = 'en_US') {}

    public function format(Money $money): string
    {
        $formatter = new NumberFormatter($this->locale, NumberFormatter::CURRENCY);

        return $formatter->formatCurrency($money->amount() / 100, $money->currency());
    }
}
```
- **Complexity**: Time O(1) per call; Space O(1).
- **Performance considerations**: Cache `NumberFormatter` instances to avoid recreation inside loops.

### Advanced Example (Advanced)
```php
final class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $builder->where('tenant_id', auth()->user()->tenant_id);
    }
}
```
- **Responsibility**: Enforce tenant isolation globally.
- **Database optimization**: Ensure `tenant_id` is indexed; add composite indexes when combined with status filters.

## Laravel Implementation
- Register shared validation rules with custom form requests or rule objects.
- Use global scopes, macros, or helpers instead of repeating query constraints.
- Extract repeated blade structures into components or `@include`s.
- Centralize configuration in `config/*.php` rather than scattering environment conditionals.

## Testing
- Create unit tests for shared services (e.g., `PhoneSanitizerTest`).
- Add integration tests ensuring global scopes apply automatically.
- Use snapshot tests sparingly for consistent formatting logic.

## Common Pitfalls
- [ ] Duplicating logic under time pressure and forgetting to refactor later.
- [ ] Over-generalizing abstractions so they no longer fit specific cases.
- [ ] Ignoring configuration duplication (e.g., color codes in multiple CSS files).

## Trade-offs
- **Pros**: Reduced maintenance cost, consistent behavior, easier onboarding.
- **Cons**: Poor abstractions can become rigid or hard to understand.
- **Mitigation**: Validate abstraction against multiple use cases before extraction.

## Metrics
- Track occurrences of identical code fragments using tools like PHP Copy/Paste Detector.
- Measure bug-fix propagation time. Faster updates indicate successful DRY practices.
- Keep shared modules covered by tests; coverage gaps signal risky centralization.

## When to Apply
✅ **Use when:**
- Multiple teams touch the same logic.
- Inconsistent outputs across modules stem from divergence.

❌ **Avoid when:**
- Similarity is superficial (code looks alike but behavior differs).
- Extracted abstraction would hide critical domain nuance.

## Related Principles
- [`kiss-principle.md`](kiss-principle.md): Prefer simple abstractions when eliminating duplication.
- [`single-responsibility.md`](../02-solid/single-responsibility.md): Ensures extracted modules stay focused.
- [`../03-design-patterns/creational/factory-pattern.md`](../03-design-patterns/creational/factory-pattern.md): Factories can consolidate object creation logic.

## Exercises
1. **Beginner**: Replace duplicate phone number parsing with `PhoneSanitizer` and unit tests.
2. **Intermediate**: Consolidate duplicated invoice formatting into a shared service and benchmark the change.
3. **Advanced**: Implement a global tenant scope and document its impact on query plans.

## Review Checklist
- [ ] Does a single abstraction represent each business rule?
- [ ] Are configuration values centralized?
- [ ] Is shared code covered by automated tests?

## Self-Check Questions
- [ ] Can you name three duplicated code areas in your project?
- [ ] Can you refactor one today without breaking functionality?
- [ ] Can you explain why an attempted abstraction failed in the past?

---
[Previous: Foundations Overview](index.md) | [Home](../README.md) | [Next: KISS Principle](kiss-principle.md)

Home / Foundations / KISS

# KISS (Keep It Simple, Stupid)

> **One-line summary**: Favor straightforward solutions that solve today’s problem without unnecessary complexity.

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
KISS is the engineering equivalent of using a hammer and nail rather than building a robotic arm to hang a picture. The simplest workable solution is often the most maintainable.

## The Problem
Over-engineered systems introduce indirection layers, factories, and design patterns long before they are needed. Developers burn time understanding the structure rather than delivering value.

## The Solution
Implement the minimal solution that satisfies requirements, then evolve only when pain emerges. Prefer explicit code, avoid speculative abstractions, and keep architecture diagram-friendly.

## Refactoring Path
1. **Identify complexity hotspots**: Long call chains, deep inheritance trees, or excessive indirection.
2. **Inline and simplify**: Collapse layers that add negligible value.
3. **Replace configuration with code** when defaults suffice.
4. **Document rationale**: Clarify why the simpler approach meets requirements.
5. **Iterate**: Reintroduce structure only when new constraints demand it.

## Examples

### Basic Example (Beginner)
```php
final class DiscountCalculator
{
    public function calculate(float $price, string $tier): float
    {
        return match ($tier) {
            'premium' => $price * 0.8,
            'regular' => $price * 0.95,
            default => $price,
        };
    }
}
```
- **Complexity**: Time O(1); Space O(1).
- **Note**: Avoids unnecessary strategy factories until multiple tiers require it.

### Intermediate Example (Intermediate)
```php
final class ProfileController
{
    public function show(int $id)
    {
        return view('profiles.show', ['user' => User::with('posts')->findOrFail($id)]);
    }
}
```
- **Complexity**: Time O(n) relative to posts count; Space O(n).
- **Observation**: Keeps logic in controller without introducing repository layers prematurely.

### Advanced Example (Advanced)
```php
final class HealthCheckController
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'database' => DB::connection()->getPdo() !== null,
            'cache' => Cache::store()->getDefaultDriver(),
            'queue' => Queue::getName(),
        ]);
    }
}
```
- **Performance considerations**: Execute lightweight checks; defer heavy diagnostics to separate tooling.

## Laravel Implementation
- Use route closures or single-action controllers for lightweight endpoints.
- Prefer built-in features (resource controllers, policies) before writing custom orchestration.
- Use Eloquent’s eager loading and query scopes before adding repositories.
- Keep configuration defaults unless business rules require customization.

## Testing
- Focus on behavior. Simpler designs need fewer mocks.
- Snapshot tests can verify simple response structures.
- Use integration tests to ensure straightforward flows succeed end-to-end.

## Common Pitfalls
- [ ] Introducing patterns "just in case" future requirements appear.
- [ ] Writing generic utilities before specific use cases exist.
- [ ] Dismissing simplicity as "not enterprise-ready" without evidence.

## Trade-offs
- **Pros**: Faster delivery, easier onboarding, clearer bug surface.
- **Cons**: May require later refactoring if requirements grow significantly.
- **Mitigation**: Monitor for duplication or strain; refactor when necessary.

## Metrics
- Track number of abstraction layers touched per feature. High counts hint at over-engineering.
- Measure onboarding time for new developers; quicker ramps imply simplicity.
- Review cyclomatic complexity; keep it low.

## When to Apply
✅ **Use when:**
- Requirements are evolving or uncertain.
- Team experience varies and clarity is paramount.

❌ **Avoid when:**
- Regulatory or architectural constraints demand formal structures.
- Performance demands require optimized, specialized solutions.

## Related Principles
- [`dry-principle.md`](dry-principle.md): Simplicity complements centralized knowledge.
- [`yagni-principle.md`](yagni-principle.md): Avoid building features before they are needed.
- [`../02-solid/open-closed.md`](../02-solid/open-closed.md): Introduce extensibility only when change pressure warrants it.

## Exercises
1. **Beginner**: Replace a small factory+strategy stack with a direct `match` statement, documenting trade-offs.
2. **Intermediate**: Remove an unused service layer and measure controller complexity before/after.
3. **Advanced**: Present a design review comparing an over-engineered module with a simplified rewrite and gather feedback.

## Review Checklist
- [ ] Does the implementation solve the problem with minimal moving parts?
- [ ] Are abstractions justified by current requirements?
- [ ] Can a teammate understand the flow quickly?

## Self-Check Questions
- [ ] Can you articulate why simplicity improves maintainability?
- [ ] Can you identify an over-engineered area in your project today?
- [ ] Can you outline a plan to simplify it incrementally?

---
[Previous: DRY Principle](dry-principle.md) | [Home](../README.md) | [Next: YAGNI Principle](yagni-principle.md)

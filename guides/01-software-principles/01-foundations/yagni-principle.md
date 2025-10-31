Home / Foundations / YAGNI

# YAGNI (You Ain't Gonna Need It)

> **One-line summary**: Implement only what delivers immediate value; defer speculative features until they are truly required.

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
YAGNI is like packing for a beach getaway—leave the snow boots at home. Build the features you need now; future-proofing can wait until the future actually arrives.

## The Problem
Premature features consume time, introduce bugs, and complicate deployments. The "just in case" code often rots unused, while real requirements remain underserved.

## The Solution
Validate requirements with stakeholders, build only confirmed features, and keep design flexible enough to evolve later. Time spent on unused functionality is better invested in quality and testing.

## Refactoring Path
1. **Audit backlog**: Flag features with unclear demand or metrics.
2. **Measure usage**: Instrument existing features to see which paths users take.
3. **Delete or postpone**: Remove or comment out speculative code, documenting decisions.
4. **Focus scope**: Implement minimal viable features with clear success criteria.
5. **Iterate**: Add functionality only after evidence supports it.

## Examples

### Basic Example (Beginner)
```php
final class PaymentController
{
    public function process(Request $request): JsonResponse
    {
        $gateway = new StripeGateway();

        $receipt = $gateway->charge($request->integer('amount'));

        return response()->json(['receipt' => $receipt->id()]);
    }
}
```
- **Complexity**: Time O(1); Space O(1).
- **Observation**: Implements only the required Stripe integration.

### Intermediate Example (Intermediate)
```php
final class NotificationService
{
    public function __construct(private readonly Mailer $mailer) {}

    public function sendWelcome(User $user): void
    {
        $this->mailer->send(new WelcomeMail($user));
    }
}
```
- **Note**: Adds SMS or push only when customers request them.

### Advanced Example (Advanced)
```php
final class FeatureToggle
{
    public function isEnabled(string $feature): bool
    {
        return config("features.{$feature}", false);
    }
}
```
- **Usage**: Introduce toggles to ship minimal core functionality, then expand.
- **Performance considerations**: Cache feature flags to avoid repeated config lookups.

## Laravel Implementation
- Delay queue adoption until synchronous processing proves insufficient.
- Start with single-language support unless localization is requested; use Laravel's translation scaffolding when needed.
- Use feature flags stored in config or database to roll out incrementally.
- Avoid building multi-tenant structures until multiple tenants sign contracts; leverage Laravel's soft prerequisites when demand arrives.

## Testing
- Cover the core flows thoroughly before adding optional ones.
- Use feature flag-driven tests to assert behavior only when flags are enabled.
- Document deleted or deferred tests in commit messages for traceability.

## Common Pitfalls
- [ ] Overbuilding admin panels for future roles that never materialize.
- [ ] Adding infrastructure (Kafka, Redis clusters) ahead of load requirements.
- [ ] Building API endpoints without validated consumers.

## Trade-offs
- **Pros**: Faster delivery, reduced maintenance, clearer focus.
- **Cons**: May require later rework when requirements do arrive.
- **Mitigation**: Keep code modular and documented so future additions slot in cleanly.

## Metrics
- Track percentage of unused features in analytics dashboards.
- Monitor time-to-market; reducing speculative work should shorten cycles.
- Survey customers to validate feature adoption before building expansions.

## When to Apply
✅ **Use when:**
- Requirements are uncertain, prototypes are still being validated.
- Team velocity suffers due to overbuilt infrastructure.

❌ **Avoid when:**
- Regulatory demands require upfront compliance features.
- Architecture decisions (e.g., multi-region) must be made early for contractual reasons.

## Related Principles
- [`kiss-principle.md`](kiss-principle.md): Simplicity reduces speculative branching.
- [`../02-solid/open-closed.md`](../02-solid/open-closed.md): Extensible codebases make future additions easier.
- [`../03-design-patterns/behavioral/command-pattern.md`](../03-design-patterns/behavioral/command-pattern.md): Commands allow incremental feature rollout.

## Exercises
1. **Beginner**: Remove unused routes or controllers and verify no tests fail.
2. **Intermediate**: Use analytics to prove a feature is unutilized, then sunset it with stakeholder approval.
3. **Advanced**: Implement feature flags and measure deployment frequency improvements.

## Review Checklist
- [ ] Does the feature address a validated requirement?
- [ ] Are future extensions documented instead of implemented prematurely?
- [ ] Are feature flags used to control rollout instead of speculative builds?

## Self-Check Questions
- [ ] Can you list features built "just in case" that never shipped value?
- [ ] Can you propose a plan to decommission or delay speculative work?
- [ ] Can you explain the opportunity cost of premature implementation?

---
[Previous: KISS Principle](kiss-principle.md) | [Home](../README.md) | [Next: SOLID Overview](../02-solid/index.md)

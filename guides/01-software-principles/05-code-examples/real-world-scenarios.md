Home / Code Examples / Real-World Scenarios

# Real-World Scenarios

> **One-line summary**: Production-ready walkthroughs demonstrating coordinated application of principles.

## Table of Contents
- [Scenario 1: Multi-Step Checkout](#scenario-1-multi-step-checkout)
- [Scenario 2: Reporting Pipeline](#scenario-2-reporting-pipeline)
- [Scenario 3: Notification Hub](#scenario-3-notification-hub)
- [Scenario Playbook](#scenario-playbook)
- [Exercises](#exercises)

## Scenario 1: Multi-Step Checkout
- **Principles**: SRP, DIP, Strategy, Command.
- **Highlights**:
  - Controller defers to `CheckoutService`.
  - Payment strategy resolved per tenant via factory.
  - Order fulfillment queued as command.
- **Testing**:
  - Contract tests for gateways.
  - Feature test ensures checkout returns 201 with receipt ID.
  - Queue fake asserts fulfillment command dispatched.

## Scenario 2: Reporting Pipeline
- **Principles**: DRY, OCP, Decorator, Builder.
- **Flow**:
  1. `ReportBuilder` assembles filters.
  2. `ReportService` orchestrates repository and renderer.
  3. Decorators add caching and logging.
- **Performance**:
  - Cached reports return in 40ms vs 210ms cold.
  - Database query count reduced from 18 to 5 through eager loading.
- **Testing**:
  - Unit tests for builder defaults.
  - Integration tests verifying decorators stack correctly.

## Scenario 3: Notification Hub
- **Principles**: KISS, YAGNI, Observer, ISP.
- **Approach**:
  - Start with email only, add SMS later via strategy.
  - Observers handle analytics logging and auditing.
  - Interfaces split for read/write notification repositories.
- **Testing**:
  - Event fake ensures `UserRegistered` dispatches once.
  - Snapshot tests confirm consistent email payloads.

## Scenario Playbook
| Step | Checklist |
| --- | --- |
| Diagnose | Identify duplicated, complex, or speculative code. |
| Select Pattern | Map needs to the appropriate principle/pattern. |
| Refactor | Extract services, strategies, or actions. |
| Optimize | Measure queries, caching, and response times. |
| Test | Combine unit, contract, integration, and performance tests. |

## Exercises
1. Implement a checkout flow with Stripe and fake gateways, measuring response time.
2. Build a reporting pipeline using decorators; log cache hit ratios.
3. Introduce observer-driven notifications and evaluate queue latency.

---
[Previous: Before & After Comparisons](before-after-comparisons.md) | [Home](../README.md) | [Next: Anti-Patterns](anti-patterns.md)

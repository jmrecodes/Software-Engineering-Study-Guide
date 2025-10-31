Home / SOLID / Interface Segregation Principle

# Interface Segregation Principle (ISP)

> **One-line summary**: Provide focused interfaces so clients depend only on what they need, avoiding needless coupling.

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
Think of ISP as giving craftsmen specialized tools instead of an unwieldy Swiss Army knife. Every extra blade they never use increases weight and complexity. Interfaces should be similarly slim, tailored to the consumer's needs.

## The Problem
Bulky interfaces force implementers to stub out unused methods and clients to depend on functionality they never invoke. Any change to unused methods still triggers recompilation, redeployment, or retesting for all consumers.

## The Solution
Split interfaces by responsibility or consumer category. Offer narrow contracts that expose only the necessary operations. Compose them when a class legitimately needs multiple capabilities.

## Refactoring Path
1. **Identify fat interfaces**: Look for interfaces with optional methods or many consumers ignoring most methods.
2. **Group by consumer**: Partition methods according to who uses them.
3. **Define focused interfaces**: Create smaller contracts (e.g., `ReadableRepository`, `WritableRepository`).
4. **Compose as needed**: Implement multiple interfaces for consumers requiring combined capabilities.
5. **Update clients**: Depend on the narrow interface matching their needs.

## Examples

### Basic Example (Beginner)
```php
interface ReadableRepository
{
    public function all(): Collection;
    public function find(int $id): ?Model;
}
```
- **Responsibility**: Expose read-only operations.
- **Complexity**: Interface only.

### Intermediate Example (Intermediate)
```php
interface WritableRepository
{
    public function create(array $attributes): Model;
    public function update(int $id, array $attributes): Model;
    public function delete(int $id): void;
}

final class UserRepository implements ReadableRepository, WritableRepository
{
    // ...implementation omitted for brevity
}
```
- **Complexity**: Time depends on underlying persistence; interface keeps clients honest.

### Advanced Example (Advanced)
```php
interface Exportable
{
    public function export(int $id): string;
}

interface Importable
{
    public function import(string $payload): Model;
}

final class ContentSyncService implements ReadableRepository, Exportable
{
    // Combines only the contracts it needs
}
```
- **Performance considerations**: Export/import paths can stream data to avoid memory pressure.

## Laravel Implementation
- **Repositories**: Separate read/write contracts to align with query builders and Eloquent models. Controllers that mutate data depend on `WritableRepository`; API resources rely on `ReadableRepository`.
- **Services**: Inject the narrowest interface. For example, a dashboard widget only needs `StatisticsProvider`, not the entire `ReportService`.
- **Validation**: Specialized interfaces let you mock only the methods under test, simplifying test doubles.
- **Database optimization**: Read-only interfaces can favor caching or read replicas, while write interfaces use transactions and queueing.

## Testing
- **Mocking**: Tests become simpler because doubles implement fewer methods. You avoid setting up expectations for irrelevant calls.
- **Contract Tests**: Validate each interface in isolation using dedicated test suites.
- **Mutation Testing**: Confirms that removing unused methods does not break consumers.

## Common Pitfalls
- [ ] Creating micro-interfaces with single methods that overlap heavily.
- [ ] Forgetting to compose interfaces when a consumer legitimately needs multiple capabilities.
- [ ] Allowing controllers to depend on `*Repository` interfaces that expose more than their action requires.

## Trade-offs
- **Pros**: Reduced coupling, faster compilation, more focused mocks.
- **Cons**: Too many small interfaces can become difficult to track.
- **Mitigation**: Group interfaces by functional area and document them in module index files.

## Metrics
- Count methods per interface; set reasonable thresholds (e.g., <=5 primary methods).
- Measure mock setup complexity in tests. Excess arrangements signal bloated interfaces.
- Track change blasts—how many files change when an interface updates.

## When to Apply
✅ **Use when:**
- Different clients use distinct subsets of an interface.
- Testing doubles require unnecessary method stubs.

❌ **Avoid when:**
- The interface represents a cohesive domain concept where splitting would fragment understanding.
- There is only one consumer and the interface exists solely for theoretical flexibility.

## Related Principles
- [`single-responsibility.md`](single-responsibility.md): Focused interfaces reinforce single-responsibility implementations.
- [`liskov-substitution.md`](liskov-substitution.md): Smaller interfaces make substitution safer.
- [`dependency-inversion.md`](dependency-inversion.md): DIP relies on interfaces that reflect true needs.

## Exercises
1. **Beginner**: Split a repository interface into read/write variants and update controllers accordingly.
2. **Intermediate**: Introduce specialized interfaces for a queue system (enqueue vs. inspect) and adapt services.
3. **Advanced**: Measure deployment blast radius before and after applying ISP to a shared package.

## Review Checklist
- [ ] Does each consumer depend only on the methods it uses?
- [ ] Are interfaces organized by responsibility rather than technology?
- [ ] Do mocks in unit tests require only relevant method expectations?

## Self-Check Questions
- [ ] Can you explain how ISP differs from SRP?
- [ ] Can you list interfaces where consumers stub unused methods?
- [ ] Can you redesign those interfaces without breaking existing features?

---
[Previous: Liskov Substitution Principle](liskov-substitution.md) | [Home](../README.md) | [Next: Dependency Inversion Principle](dependency-inversion.md)

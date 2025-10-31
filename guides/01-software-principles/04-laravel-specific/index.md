Home / Laravel / Overview

# Laravel Best Practices Overview

> **One-line summary**: Translate foundational and SOLID principles into idiomatic Laravel architecture patterns.

## Table of Contents
- [Focus Areas](#focus-areas)
- [Navigation](#navigation)
- [Performance Checklist](#performance-checklist)
- [Self-Assessment](#self-assessment)

## Focus Areas
- **Service Layer**: Keep controllers skinny by orchestrating domain logic elsewhere.
- **Repository Layer**: Abstract persistence with query optimization strategies.
- **Action Classes**: Encapsulate single operations for clarity, queuing, or reuse.
- **Testing Principles**: Align PHPUnit, Pest, and Laravel testing tools with these patterns.

## Navigation
- [`service-layer-pattern.md`](service-layer-pattern.md)
- [`repository-pattern.md`](repository-pattern.md)
- [`action-classes.md`](action-classes.md)
- [`testing-principles.md`](testing-principles.md)

## Performance Checklist
- [ ] Queries eagerly load necessary relationships (`->with`).
- [ ] Long-running work dispatched to queues with retry logic.
- [ ] Feature flags guard experimental functionality.
- [ ] Caching has invalidation strategies documented.

## Self-Assessment
- [ ] Can you trace a request from controller to service to repository?
- [ ] Do you know where to add cross-cutting concerns (logging, metrics)?
- [ ] Are tests in place for each architectural layer?

---
[Previous: Command Pattern](../03-design-patterns/behavioral/command-pattern.md) | [Home](../README.md) | [Next: Service Layer Pattern](service-layer-pattern.md)

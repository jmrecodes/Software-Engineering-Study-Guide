Home / SOLID / Overview

# SOLID Principles Overview

> **One-line summary**: A navigable guide to the SOLID principles with context, prerequisites, and how they reinforce maintainable PHP and Laravel architectures.

## Table of Contents
- [Why SOLID Matters](#why-solid-matters)
- [Prerequisites](#prerequisites)
- [How to Navigate This Section](#how-to-navigate-this-section)
- [Principle Summaries](#principle-summaries)
- [Case Study Roadmap](#case-study-roadmap)
- [Self-Assessment](#self-assessment)

## Why SOLID Matters
SOLID provides guardrails for evolving codebases. Well-applied principles reduce regression risk, keep business rules isolated, and enable test automation. In Laravel, SOLID aligns with framework features like service containers, events, middleware, and resource classes.

## Prerequisites
- Comfortable with PHP objects, interfaces, and traits.
- Familiarity with Laravel's service container and dependency injection.
- Basic understanding of design patterns (strategy, factory, observer).

## How to Navigate This Section
1. Start with the principle that addresses your most pressing pain point.
2. Follow the bottom navigation to move sequentially.
3. Use the exercises to practice against existing projects.
4. Jump to `../../05-code-examples/before-after-comparisons.md` for applied samples once you understand the basics.

## Principle Summaries
- [`single-responsibility.md`](single-responsibility.md): Prevent "God classes" with clear seams between HTTP, domain, and infrastructure code.
- [`open-closed.md`](open-closed.md): Add new behaviors via composition and configuration instead of editing core classes.
- [`liskov-substitution.md`](liskov-substitution.md): Ensure polymorphic contracts remain reliable when swapping implementations.
- [`interface-segregation.md`](interface-segregation.md): Keep interfaces thin so consumers only depend on what they actually use.
- [`dependency-inversion.md`](dependency-inversion.md): Rely on abstractions and Laravel's container to swap infrastructure without rewrites.

## Case Study Roadmap
1. **Baseline**: Review an overgrown `OrderController` in [`single-responsibility.md`](single-responsibility.md).
2. **Extension**: Add a new payment provider via configuration in [`open-closed.md`](open-closed.md).
3. **Polymorphism**: Swap data stores safely using [`liskov-substitution.md`](liskov-substitution.md).
4. **Client Focus**: Refine repository contracts in [`interface-segregation.md`](interface-segregation.md).
5. **Abstraction**: Wire everything together through the service container in [`dependency-inversion.md`](dependency-inversion.md).

## Self-Assessment
- [ ] Can you articulate the outcome each principle enables?
- [ ] Can you spot the principle violated in an unfamiliar code snippet?
- [ ] Can you demonstrate a refactoring that applies at least three principles together?

---
[Previous: Foundations Overview](../01-foundations/index.md) | [Home](../README.md) | [Next: Single Responsibility](single-responsibility.md)

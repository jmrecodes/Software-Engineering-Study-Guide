Home / Code Examples / Anti-Patterns

# Anti-Patterns

> **One-line summary**: Recognize and remediate common traps that violate foundational and SOLID principles.

## Table of Contents
- [God Controllers](#god-controllers)
- [Feature Flags Everywhere](#feature-flags-everywhere)
- [Repository bloat](#repository-bloat)
- [Inline Caching](#inline-caching)
- [Refactoring Framework](#refactoring-framework)

## God Controllers
- **Symptoms**: 300+ lines, validation, payment, logging in one method.
- **Impact**: Hard to test, frequent merge conflicts.
- **Fix**: Extract `FormRequest`, services, events. See [`../04-laravel-specific/service-layer-pattern.md`](../04-laravel-specific/service-layer-pattern.md).

## Feature Flags Everywhere
- **Symptoms**: Conditional branches sprinkled across views/controllers.
- **Impact**: Hard to reason about active features, brittle rollouts.
- **Fix**: Centralize in a feature toggle service; add integration tests per flag.

## Repository bloat
- **Symptoms**: Repositories with dozens of methods, returning different types.
- **Impact**: Violates ISP and LSP; hard to substitute.
- **Fix**: Split into read/write/search interfaces; add decorators where needed.

## Inline Caching
- **Symptoms**: Controllers caching queries directly with `Cache::remember`.
- **Impact**: Duplication, scattered TTL policies.
- **Fix**: Wrap caching in repository decorators, document invalidation.

## Refactoring Framework
```
Identify -> Prioritize -> Extract -> Test -> Benchmark -> Document -> Share
```
- Use this loop to guide remediation efforts and keep teams aligned.

---
[Previous: Real-World Scenarios](real-world-scenarios.md) | [Home](../README.md) | [Next: Cheatsheet](../06-quick-reference/cheatsheet.md)

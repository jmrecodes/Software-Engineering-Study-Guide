Home / Quick Reference / Cheat Sheet

# Cheat Sheet

> **One-line summary**: Rapid reference for principles, patterns, and Laravel implementations.

## Principles at a Glance
| Principle | Summary | Key Metric | Checklist |
| --- | --- | --- | --- |
| DRY | Single source of truth | Duplicate blocks/month | Shared modules tested? |
| KISS | Minimal workable solution | Abstraction layers/feature | Simplicity justified? |
| YAGNI | Build on demand | Unused features | Requirement validated? |
| SRP | One reason to change | Constructor params | Collaborator isolated? |
| OCP | Extend via composition | Files edited per variant | New behavior via new class? |
| LSP | Substitute safely | `instanceof` checks | Contracts documented? |
| ISP | Focused interfaces | Methods/interface | Clients depend on minimal set? |
| DIP | Depend on abstractions | `new` in services | Interfaces bound in container? |

## Pattern Selector
```
if (creation complexity) -> Factory/Builder
else if (interface mismatch) -> Adapter/Facade
else if (extend behavior) -> Decorator/Strategy
else if (event driven) -> Observer/Command
```

## Laravel Tactics
- Controllers delegate to services.
- Services orchestrate repositories, gateways, events.
- Repositories encapsulate data access with eager loading.
- Actions encapsulate reusable operations.
- Observers/listeners handle side effects.

## Code Review Sprint
- [ ] Principle alignment verified (DRY, SRP, etc.).
- [ ] Database queries counted and optimized.
- [ ] Tests (unit + feature) updated.
- [ ] Feature flags documented.

## Tooling Quick Hits
- `phpstan analyse` (level max) for static analysis.
- `php artisan test --parallel` to shorten suites.
- `phpqa --report` for duplicate detection.
- Telescope for request/DB metrics.

---
[Previous: Anti-Patterns](../05-code-examples/anti-patterns.md) | [Home](../README.md) | [Next: Decision Tree](decision-tree.md)

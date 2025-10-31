Home / Design Patterns / Overview

# Design Patterns Overview

> **One-line summary**: Curated PHP and Laravel-friendly patterns that reinforce DRY, SOLID, and pragmatic architecture.

## Table of Contents
- [Why Patterns Matter](#why-patterns-matter)
- [How to Navigate](#how-to-navigate)
- [Pattern Catalog](#pattern-catalog)
- [Selection Guide](#selection-guide)
- [Self-Assessment](#self-assessment)

## Why Patterns Matter
Patterns encapsulate proven solutions to recurring design problems. Applied thoughtfully, they accelerate development and keep implementations consistent across teams. Misused patterns, however, create unnecessary complexity—use them as tools, not rules.

## How to Navigate
1. Start with the **Creational** patterns to manage object construction.
2. Move to **Structural** patterns to learn composition techniques.
3. Explore **Behavioral** patterns for runtime flexibility and event-driven flows.
4. Cross-link to `../04-laravel-specific/index.md` to see Laravel-oriented implementations.

## Pattern Catalog
- [`creational/factory-pattern.md`](creational/factory-pattern.md)
- [`creational/builder-pattern.md`](creational/builder-pattern.md)
- [`creational/singleton-pattern.md`](creational/singleton-pattern.md)
- [`structural/decorator-pattern.md`](structural/decorator-pattern.md)
- [`structural/adapter-pattern.md`](structural/adapter-pattern.md)
- [`structural/facade-pattern.md`](structural/facade-pattern.md)
- [`behavioral/strategy-pattern.md`](behavioral/strategy-pattern.md)
- [`behavioral/observer-pattern.md`](behavioral/observer-pattern.md)
- [`behavioral/command-pattern.md`](behavioral/command-pattern.md)

## Selection Guide
- Need to simplify complex object creation? Start with factory or builder.
- Need to extend behavior without modifying existing code? Consider decorator or strategy.
- Need to align interface mismatches? Adapter or facade can help.
- Need event-driven coordination? Observer or command is ideal.

## Self-Assessment
- [ ] Can you explain when to favor composition over inheritance?
- [ ] Can you tie patterns back to the principle they reinforce?
- [ ] Can you map current project challenges to one or more patterns here?

---
[Previous: Dependency Inversion Principle](../02-solid/dependency-inversion.md) | [Home](../README.md) | [Next: Factory Pattern](creational/factory-pattern.md)

Home / Laravel / Testing Principles

# Testing Principles

> **One-line summary**: Align your test strategy with DRY, SOLID, and Laravel best practices to ensure repeatable quality.

## Table of Contents
- [Overview](#overview)
- [Test Pyramid](#test-pyramid)
- [Unit Testing Patterns](#unit-testing-patterns)
- [Integration Testing Patterns](#integration-testing-patterns)
- [TDD Workflow](#tdd-workflow)
- [Mocking and Dependency Injection](#mocking-and-dependency-injection)
- [Testing SOLID Compliance](#testing-solid-compliance)
- [Metrics and Benchmarks](#metrics-and-benchmarks)
- [Tooling Recommendations](#tooling-recommendations)
- [Exercises](#exercises)
- [See Also](#see-also)

## Overview
Testing is the enforcement arm of your architectural principles. Good tests reveal violations early, provide living documentation, and keep refactors safe.

## Test Pyramid
```
        [ UI / Dusk ]
    [ Feature / API ]
  [ Integration Services ]
[ Unit Tests & Contracts ]
```
- Favor many fast unit tests, enough integration tests for confidence, and selective end-to-end coverage.

## Unit Testing Patterns
- Test services and actions in isolation with mocks/fakes.
- Assert SRP by verifying each collaborator handles its responsibility.
- Use Pest or PHPUnit data providers for scenario coverage.

## Integration Testing Patterns
- Use `RefreshDatabase` with SQLite or testing databases.
- Ensure endpoints run through middleware, policies, and services.
- Leverage HTTP fake, Mail fake, Queue fake for external dependencies.

## TDD Workflow
1. Write a failing test describing the behavior.
2. Implement the simplest code to pass (KISS, YAGNI).
3. Refactor to apply DRY and SOLID once tests are green.
4. Repeat for incremental build-up.

## Mocking and Dependency Injection
- Prefer constructor injection for easier substitution.
- Use Laravel’s container to swap implementations in tests (`app()->bind`).
- Avoid over-mocking; integration tests should cover real collaborators.

## Testing SOLID Compliance
- **SRP**: Ensure adding new side effects requires new listeners, not controller changes.
- **OCP**: Add contract tests to confirm new strategies comply with expected behavior.
- **LSP**: Run shared test suites across implementations.
- **ISP**: Verify clients mock only the methods they need.
- **DIP**: Assert high-level classes depend on interfaces via reflection or analyzer tools.

## Metrics and Benchmarks
- Track test runtime by suite; >5 minutes suggests optimization.
- Monitor mutation score (Infection) to ensure tests catch behavior changes.
- Benchmark performance-critical tests with Blackfire or Laravel Telescope.

## Tooling Recommendations
- **Static Analysis**: PHPStan/Psalm with Laravel plugins.
- **Mutation Testing**: Infection with configured test suites.
- **Coverage**: Xdebug or PCOV for line and branch coverage.
- **IDE Support**: PhpStorm or VS Code with PHPUnit/Telescope integrations.

## Exercises
1. Write a contract test for multiple payment strategies.
2. Convert a feature test into a combination of unit + integration tests while retaining coverage.
3. Introduce mutation testing to a module and interpret the results.

## See Also
- [`../05-code-examples/real-world-scenarios.md`](../05-code-examples/real-world-scenarios.md)
- [`../02-solid/dependency-inversion.md`](../02-solid/dependency-inversion.md)
- [`service-layer-pattern.md`](service-layer-pattern.md)

---
[Previous: Action Classes](action-classes.md) | [Home](../README.md) | [Next: Before/After Comparisons](../05-code-examples/before-after-comparisons.md)

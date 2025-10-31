Home / Laravel / Action Classes

# Action Classes

> **One-line summary**: Encapsulate single, focused operations in invokable classes for clarity, reuse, and queuing.

## Table of Contents
- [Overview](#overview)
- [When to Use](#when-to-use)
- [When NOT to Use](#when-not-to-use)
- [Implementation](#implementation)
- [Integration Patterns](#integration-patterns)
- [Testing](#testing)
- [Common Pitfalls](#common-pitfalls)
- [Trade-offs](#trade-offs)
- [Exercises](#exercises)
- [See Also](#see-also)

## Overview
Action classes (a.k.a. single-action controllers or use-case classes) wrap one responsibility. They are perfect for reusable domain operations invoked by controllers, jobs, or listeners.

## When to Use
- Repeated operations across multiple entry points (HTTP, console, queue).
- Need to enforce policy or validation consistently for a single action.

## When NOT to Use
- Operations so trivial that a service method suffices.
- Highly stateful workflows needing orchestration (favor services instead).

## Implementation
```php
final class AssignRoleAction
{
    public function __construct(private readonly RoleRepository $roles) {}

    public function __invoke(User $user, string $role): void
    {
        $roleModel = $this->roles->findByName($role);

        $user->assignRole($roleModel);
    }
}
```
- Registered in controllers: `$assignRole($user, 'admin');`

## Integration Patterns
- Use Laravel’s container auto-resolution to inject dependencies.
- Tag actions for discovery (e.g., policy enforcement, scheduled tasks).
- Convert to queued jobs by implementing `ShouldQueue` if heavy.

## Testing
- Unit test invocation with stubbed dependencies.
- Feature test controllers that rely on actions to ensure integration wiring.
- Use Pest’s closures for concise tests when appropriate.

## Common Pitfalls
- [ ] Actions accumulating multiple responsibilities; split them.
- [ ] Bypassing authorization—wrap `Gate::authorize` inside the action when necessary.

## Trade-offs
- Adds a class per action but improves readability and reuse.

## Exercises
1. Extract a repeated policy check into an action class.
2. Queue an action for asynchronous execution and measure throughput.

## See Also
- [`service-layer-pattern.md`](service-layer-pattern.md)
- [`../03-design-patterns/behavioral/command-pattern.md`](../03-design-patterns/behavioral/command-pattern.md)
- [`testing-principles.md`](testing-principles.md)

---
[Previous: Repository Pattern](repository-pattern.md) | [Home](../README.md) | [Next: Testing Principles](testing-principles.md)

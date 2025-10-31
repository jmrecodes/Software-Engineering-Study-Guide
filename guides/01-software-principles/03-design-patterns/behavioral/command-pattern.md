Home / Design Patterns / Behavioral / Command

# Command Pattern

> **One-line summary**: Encapsulate a request as an object to parameterize clients, support queues, and enable undo/redo.

## Table of Contents
- [Overview](#overview)
- [When to Use](#when-to-use)
- [When NOT to Use](#when-not-to-use)
- [Implementation](#implementation)
- [Laravel Example](#laravel-example)
- [Testing](#testing)
- [Common Pitfalls](#common-pitfalls)
- [Trade-offs](#trade-offs)
- [Exercises](#exercises)
- [See Also](#see-also)

## Overview
Commands package operations and context into discrete objects. They decouple the invoker from the receiver and allow scheduling, queuing, or auditing requests.

## When to Use
- Background jobs or queueable tasks.
- Undo/redo functionality or auditing pipelines.
- Batch processing where commands represent steps.

## When NOT to Use
- Simple methods where an extra class adds friction.
- Stateless operations better handled by closures or pipeline stages.

## Implementation
```php
interface Command
{
    public function execute(): void;
}

final class AssignRole implements Command
{
    public function __construct(private readonly User $user, private readonly string $role) {}

    public function execute(): void
    {
        $this->user->assignRole($this->role);
    }
}
```

## Laravel Example
- Laravel jobs are commands: implement `ShouldQueue` and handle logic in `handle()`.
- Use command buses (e.g., `dispatch(new AssignRoleJob($user, 'admin'))`).
- Log command execution for auditing using middleware or pipeline.

## Testing
- Unit test command `handle()` methods with fakes.
- Use `Bus::fake()` to assert commands are dispatched.
- Benchmark synchronous vs. queued execution to justify the pattern.

## Common Pitfalls
- [ ] Bloated command classes containing multiple responsibilities.
- [ ] Coupling commands tightly to HTTP requests.

## Trade-offs
- Enables queuing and retries; additional classes may increase boilerplate.

## Exercises
1. Convert a synchronous report generation into a queued command and measure response time.
2. Implement middleware to log command execution duration.

## See Also
- [`observer-pattern.md`](observer-pattern.md)
- [`../../02-solid/single-responsibility.md`](../../02-solid/single-responsibility.md)
- [`../../04-laravel-specific/action-classes.md`](../../04-laravel-specific/action-classes.md)

---
[Previous: Observer Pattern](observer-pattern.md) | [Home](../../README.md) | [Next: Laravel Best Practices](../../04-laravel-specific/index.md)

# Comparison: Before vs After

This document compares the two implementations side-by-side and highlights why the refactored `after-app` is superior for maintenance, testing, and scaling.

Key differences

1) Controller responsibilities
- Before: `OrderController` contains validation, cart management, business logic, database writes, and side effects — huge single file with many responsibilities.
- After: `OrderController` is thin. It delegates to `CartService`, `OrderService`, and `OrderRepository`.

2) Validation
- Before: Uses inline `$request->validate()` in actions.
- After: Uses `StoreOrderRequest` to centralize validation and `authorize()`.

3) Cart management
- Before: Session is manipulated directly in the controller.
- After: `CartService` encapsulates session storage and computations.

4) Side effects (email, inventory)
- Before: `Mail::raw()` and inventory decrement are done inline in the controller's `store` method.
- After: `OrderPlaced` event is dispatched and `SendOrderConfirmationEmail` and `UpdateProductInventory` listeners handle side effects (queued).

5) Data access
- Before: Controller issues ORM calls directly for creation and reads.
- After: `OrderRepository` centralizes order queries and eager-loading concerns.

6) Configuration
- Before: Tax rate and shipping logic are hard-coded.
- After: `config/cart.php` holds tax and shipping defaults.

Why the After version is better
- Testability: Services and repositories are isolated and can be unit tested without framework facades.
- Maintainability: Small classes with single responsibilities are easier to reason about and change safely.
- Scalability: Event-driven side effects allow introduction of queue workers and retries without changing request flow.
- Security: Policies centralize authorization logic and reduce chance of mistakes.

When to choose each approach
- The Before style is occasionally OK for simple prototypes or throwaway code.
- The After style is appropriate for production or code that must be maintained and extended.

Summary
- The refactor focuses on replacing ad-hoc procedural code with explicit, testable units and clear boundaries between HTTP, domain logic, persistence and side effects.

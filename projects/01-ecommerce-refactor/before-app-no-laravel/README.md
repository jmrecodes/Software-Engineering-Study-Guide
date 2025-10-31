# Before App — E-commerce Shopping Cart (Bad Practice Version)

This repository folder (`before-app`) contains a deliberately unstructured, monolithic Laravel-style implementation of a simple e-commerce shopping cart and checkout flow.

Purpose
- Demonstrate common anti-patterns and maintainability problems in a small real-world app.
- Serve as the "Before" half of a before/after case study for teaching refactoring.

What this version intentionally includes
- Fat controller: all business logic lives inside `OrderController` (validation, cart logic, total calculation, DB writes, email sending).
- Manual validation in controller using `$request->validate()` rather than Form Requests.
- Direct session usage and Facade abuse (shopping cart stored and manipulated with the `Session` facade / `session()` helper directly in controller methods).
- No service layer — calculation and business rules are private methods or inline in the controller.
- No decoupling for side effects — emails are sent inline with `Mail::raw()` in the controller's `store` method.
- No policies — authorization checks are inline with `if (Auth::id() !== $order->user_id)`.

Primary files (key locations)
- `routes/web.php` — routes pointing to a single controller.
- `app/Http/Controllers/OrderController.php` — the fat controller (index, show, addToCart, showCart, checkout, store) containing nearly all logic.
- `app/Models/Product.php`, `app/Models/Order.php`, `app/Models/OrderItem.php` — Eloquent models used as thin data holders.
- `database/migrations/*` — basic table migrations for users, products, orders, order_items.

How the shopping flow works (high level)
1. Product listing (index) reads `Product::orderBy('name')->get()` and shows a simple add-to-cart form.
2. `addToCart` validates quantity with `$request->validate()`, manipulates `Session::get('cart')` directly, calculates line subtotal inline and writes back to session.
3. `showCart` and `checkout` read from session and compute totals via a private `calculateCartTotals` method.
4. `store` validates shipping/payment (again via `$request->validate()`), calculates subtotal/tax/shipping inline, starts a DB transaction, creates `Order` and `OrderItem` records, decrements product stock inline and sends a confirmation email using `Mail::raw(...)`.

Why this is useful for teaching
- The app is intentionally functional and minimal (easy to run), but it's a compact example that shows many maintainability problems that appear in real projects.
- It gives a safe playground to show how incremental refactors (extract service, add DI, events, listeners, policies) dramatically improve the codebase.

Limitations (intended)
- Hard to unit test because logic uses facades and is embedded in controller methods.
- Hard to extend: adding features (discounts, promotions, shipping rules) requires editing the fat controller.
- Risky: side effects (mail + inventory) are done inline, making transactional guarantees and retries more complicated.

Next steps (in the case study)
- See `../before-app/CODE_REVIEW.md` for a line-by-line code review and a catalog of the anti-patterns.
- See `../COMPARISON.md` to compare this version to the refactored version in `after-app`.

# Migrations Summary

This file summarizes the migration files included in both `before-app` and `after-app` migration folders.

Files (all follow the same names in both before/after):

- `2025_01_01_000000_create_users_table.php`
  - Creates `users` table with `id`, `name`, `email`, `password`, `email_verified_at`, `remember_token`, timestamps.

- `2025_01_01_010000_create_products_table.php`
  - Creates `products` table with `id`, `name`, `description`, `price` (`decimal`), `stock` (unsigned integer), timestamps.

- `2025_01_01_020000_create_orders_table.php`
  - Creates `orders` table with `id`, `user_id` (FK), financial columns (`subtotal`, `tax`, `shipping`, `total`), `status` (string), `shipping_address` (text), timestamps. In the `after-app` version this migration also includes `softDeletes()`.

- `2025_01_01_030000_create_order_items_table.php`
  - Creates `order_items` table with `id`, `order_id` (FK), `product_id` (FK), `quantity`, `unit_price`, `line_total`, timestamps.

Notes
- Primary foreign key relations assume the users and products tables exist and `constrained()` references them.
- If you use SQLite for local testing, the `foreignId()->constrained()` behavior works but may need `PRAGMA foreign_keys = ON` (Laravel handles this in most environments).
- `softDeletes()` was added to the `after-app` orders migration to demonstrate safe-retention of historical order data.

Guidance
- If you plan to copy these migrations into a fresh Laravel project, run `php artisan migrate` after configuring your `DB_*` environment variables.
- For local quick runs use SQLite to avoid DB setup complexity.

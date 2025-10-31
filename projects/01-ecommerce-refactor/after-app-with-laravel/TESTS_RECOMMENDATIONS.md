# Tests & Validation Recommendations

This document suggests tests (unit and feature) to validate both apps and ensure the refactor preserved behavior.

Unit tests (fast, isolated)

1. CartService tests
- Test adding a product increases quantity and updates subtotal.
- Test updating quantity to zero removes the item.
- Test `getTotals()` returns correct subtotal, tax, shipping, and total for multiple items.

2. OrderService tests
- Mock `CartService` to return a sample cart and totals. Test `placeOrder` successfully creates an `Order` (use an in-memory DB or mock repository).
- Test insufficient stock path: mock product with lower stock and assert `RuntimeException` thrown.
- Test that `OrderPlaced` event is dispatched after success (use `Event::fake()` or mock the dispatcher).

3. OrderRepository tests
- Ensure `getOrdersForUser` returns paginated results with items loaded.

Feature tests (integration)

1. Checkout flow
- Seed products, authenticate a test user, add items to cart via controller endpoints, post to `/orders` and assert redirect to `orders.show`.
- Assert DB has `orders` and `order_items` rows with expected totals.

2. Authorization
- Attempt to `GET /orders/{order}` as a different user and assert 403.

3. Queued listeners
- Use `Queue::fake()` to ensure `SendOrderConfirmationEmail` and `UpdateProductInventory` are dispatched.
- Optionally run `php artisan queue:work` in a CI stage and assert side effects (emails in log table or inventory decremented).

Testing tips
- Use Laravel's in-memory SQLite (`:memory:`) for fast DB-backed tests.
- Use `RefreshDatabase` trait for clean DB state each test.
- Use `Event::fake()` / `Queue::fake()` when asserting events and queued jobs rather than actually enqueuing.

Example skeleton (PHPUnit / Pest style):

```php
public function test_checkout_creates_order()
{
    $user = User::factory()->create();
    $product = Product::factory()->create(['price' => 10.00, 'stock' => 10]);

    $this->actingAs($user)
         ->post('/cart/add/'.$product->id, ['quantity' => 2]);

    $this->post('/orders', [
        'shipping_address' => '123 Test St',
        'payment_method' => 'credit_card',
    ])
    ->assertRedirect();

    $this->assertDatabaseHas('orders', ['user_id' => $user->id]);
    $this->assertDatabaseHas('order_items', ['product_id' => $product->id, 'quantity' => 2]);
}
```

Summary
- Focus tests on services and policies where possible. The refactored app is designed to make unit testing straightforward by keeping logic in services rather than controllers.

<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_checkout_attempted(): void
    {
        $response = $this->post('/orders', []);

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_place_order(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10, 'price' => 10.00]);

        $this->actingAs($user)
            ->post("/cart/add/{$product->id}", ['quantity' => 2]);

        $response = $this->post('/orders', [
            'shipping_address' => '123 Test Street',
            'payment_method' => 'credit_card',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', ['user_id' => $user->id]);
        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
    }
}

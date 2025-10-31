<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::orderBy('name')->get();

        $orders = Order::with(['items.product'])
            ->where('user_id', optional(Auth::user())->id)
            ->latest()
            ->get();

        $cart = Session::get('cart', []);
        $cartTotals = $this->calculateCartTotals($cart);

        return view('orders.index', [
            'products' => $products,
            'orders' => $orders,
            'cart' => $cart,
            'cartTotals' => $cartTotals,
        ]);
    }

    public function show(int $orderId): View
    {
        $order = Order::with('items.product')->findOrFail($orderId);

        if (Auth::id() !== $order->user_id) {
            abort(403, 'You cannot view this order.');
        }

        return view('orders.show', [
            'order' => $order,
            'items' => $order->items,
        ]);
    }

    public function addToCart(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = Session::get('cart', []);
        $currentQuantity = $cart[$product->id]['quantity'] ?? 0;
        $newQuantity = $currentQuantity + $data['quantity'];

        if ($newQuantity > $product->stock) {
            return back()->withErrors('Not enough stock available.');
        }

        $cart[$product->id] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $newQuantity,
            'subtotal' => $product->price * $newQuantity,
        ];

        Session::put('cart', $cart);
        Session::flash('cart_totals', $this->calculateCartTotals($cart));

        return back()->with('message', 'Product added to cart.');
    }

    public function showCart(): View
    {
        $cart = Session::get('cart', []);
        $cartTotals = $this->calculateCartTotals($cart);

        return view('cart.show', [
            'cart' => $cart,
            'cartTotals' => $cartTotals,
        ]);
    }

    public function checkout(): View
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            abort(400, 'Your cart is empty.');
        }

        $cartTotals = $this->calculateCartTotals($cart);

        return view('orders.checkout', [
            'cart' => $cart,
            'cartTotals' => $cartTotals,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'shipping_address' => ['required', 'string', 'max:500'],
            'payment_method' => ['required', 'string'],
        ]);

        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return back()->withErrors('Your cart is empty.');
        }

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $taxRate = 0.1;
        $shippingCost = $subtotal > 100 ? 0 : 9.99;
        $tax = round($subtotal * $taxRate, 2);
        $total = round($subtotal + $tax + $shippingCost, 2);

        $order = null;

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => $user->id,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => $shippingCost,
                'total' => $total,
                'status' => 'processing',
                'shipping_address' => $data['shipping_address'],
            ]);

            foreach ($cart as $cartItem) {
                $product = Product::find($cartItem['product_id']);

                if (!$product || $product->stock < $cartItem['quantity']) {
                    DB::rollBack();
                    return back()->withErrors('Insufficient stock for ' . $cartItem['name']);
                }

                $product->decrement('stock', $cartItem['quantity']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem['product_id'],
                    'quantity' => $cartItem['quantity'],
                    'unit_price' => $cartItem['price'],
                    'line_total' => $cartItem['price'] * $cartItem['quantity'],
                ]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors('Unable to process your order: ' . $e->getMessage());
        }

        Session::forget('cart');

        Mail::raw(
            'Thanks for your order #' . $order->id . '! We will ship it soon.',
            function ($message) use ($user) {
                $message->to($user->email)->subject('Order Confirmation');
            }
        );

        return redirect()->route('orders.show', $order->id)->with('message', 'Order placed successfully.');
    }

    private function calculateCartTotals(array $cart): array
    {
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $tax = round($subtotal * 0.1, 2);
        $shipping = $subtotal > 100 ? 0 : 9.99;
        $total = round($subtotal + $tax + $shipping, 2);

        return [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping' => $shipping,
            'total' => $total,
        ];
    }
}

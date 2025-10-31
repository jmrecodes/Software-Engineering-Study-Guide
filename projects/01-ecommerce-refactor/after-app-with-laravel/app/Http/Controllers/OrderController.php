<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Repositories\OrderRepository;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderRepository $orders,
        private readonly CartService $cart,
        private readonly OrderService $orderService
    ) {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $user = Auth::user();
        $products = Product::orderBy('name')->get();

        return view('orders.index', [
            'orders' => $this->orders->getOrdersForUser($user),
            'products' => $products,
            'cart' => $this->cart->getItems(),
            'totals' => $this->cart->getTotals(),
        ]);
    }

    public function show(Order $order): View
    {
        $this->authorize('view', $order);

        return view('orders.show', [
            'order' => $this->orders->findWithItems($order->id),
        ]);
    }

    public function addToCart(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        if ($validated['quantity'] > $product->stock) {
            return back()->withErrors('Not enough stock available.');
        }

        $this->cart->addProduct($product, $validated['quantity']);

        return back()->with('message', 'Product added to cart.');
    }

    public function showCart(): View
    {
        return view('cart.show', [
            'cart' => $this->cart->getItems(),
            'totals' => $this->cart->getTotals(),
        ]);
    }

    public function checkout(): View
    {
        if (empty($this->cart->getItems())) {
            abort(400, 'Your cart is empty.');
        }

        return view('orders.checkout', [
            'cart' => $this->cart->getItems(),
            'totals' => $this->cart->getTotals(),
        ]);
    }

    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $order = $this->orderService->placeOrder(
            $request->user()->id,
            $request->validated()['shipping_address'],
            $request->validated()['payment_method']
        );

        return redirect()->route('orders.show', $order)->with('message', 'Order placed successfully.');
    }
}

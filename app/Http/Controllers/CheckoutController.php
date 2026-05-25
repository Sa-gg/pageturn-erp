<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect('/cart')->with('error', 'Your cart is empty.');
        }

        $subtotal = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        // Dummy shipping and tax for now
        $shipping = 5.00;
        $tax = $subtotal * 0.12;
        $total = $subtotal + $shipping + $tax;

        return view('checkout.index', compact('cart', 'subtotal', 'shipping', 'tax', 'total'));
    }

    public function process(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect('/cart');
        }

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'shipping_address' => 'required|string',
            'payment_method' => 'required|string|in:card,paypal,bank_transfer'
        ]);

        $items = [];
        foreach ($cart as $id => $item) {
            $items[] = [
                'book_id' => $id,
                'book_title' => $item['title'],
                'unit_price' => $item['price'],
                'quantity' => $item['quantity'],
            ];
        }

        $payload = [
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'shipping_address' => $request->shipping_address,
            'payment_method' => $request->payment_method,
            'items' => $items
        ];

        try {
            $response = $this->orderService->createOrder($payload);
            
            if ($response->successful()) {
                session()->forget('cart');
                return redirect('/profile')->with('success', 'Order placed successfully! Your order number is: ' . $response->json('order.order_number'));
            } else {
                return back()->with('error', 'Failed to place order: ' . $response->json('message', 'Unknown error'));
            }
        } catch (\Exception $e) {
            Log::error('Order placement failed: ' . $e->getMessage());
            return back()->with('error', 'Checkout service is currently unavailable. Please try again later.');
        }
    }
}

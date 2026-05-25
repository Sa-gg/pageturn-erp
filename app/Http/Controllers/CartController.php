<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        
        $subtotal = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        return view('cart.index', compact('cart', 'subtotal'));
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'book_id' => 'required|integer',
            'title' => 'required|string',
            'price' => 'required|numeric',
            'cover_image' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$data['book_id']])) {
            $cart[$data['book_id']]['quantity'] += $data['quantity'];
        } else {
            $cart[$data['book_id']] = $data;
        }

        session()->put('cart', $cart);

        return redirect('/cart')->with('success', 'Book added to cart!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        return redirect('/cart')->with('success', 'Cart updated successfully.');
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect('/cart')->with('success', 'Item removed from cart.');
    }
}

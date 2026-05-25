@extends('layouts.app')

@section('title', 'Shopping Cart | PageTurn Books')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Your Shopping Cart</h1>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-8 rounded-md">
            <p class="text-sm text-green-700">{{ session('success') }}</p>
        </div>
    @endif

    @if(empty($cart))
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <svg class="mx-auto h-24 w-24 text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Your cart is empty</h2>
            <p class="text-gray-500 mb-8 max-w-md mx-auto">Looks like you haven't added any books to your cart yet. Discover your next great read in our catalog.</p>
            <a href="/catalog" class="btn-primary py-3 px-8 text-lg">Start Shopping</a>
        </div>
    @else
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Cart Items -->
            <div class="w-full lg:w-2/3">
                <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">
                    <ul class="divide-y divide-gray-200">
                        @foreach($cart as $id => $item)
                        <li class="p-6 flex flex-col sm:flex-row gap-6 items-start sm:items-center">
                            <div class="flex-shrink-0 w-24 h-32 bg-gray-100 rounded-md overflow-hidden relative">
                                <img src="{{ $item['cover_image'] ?? 'https://via.placeholder.com/150' }}" alt="{{ $item['title'] }}" class="absolute inset-0 w-full h-full object-cover">
                            </div>
                            
                            <div class="flex-1 flex flex-col justify-between h-full w-full">
                                <div class="flex justify-between">
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900"><a href="/catalog/{{ $id }}" class="hover:text-brand-forest">{{ $item['title'] }}</a></h3>
                                        <p class="text-xl font-bold text-brand-brown mt-1">${{ number_format($item['price'], 2) }}</p>
                                    </div>
                                    <form action="/cart/remove/{{ $id }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 p-2" title="Remove item">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                                
                                <div class="mt-4 flex items-center justify-between w-full">
                                    <form action="/cart/update/{{ $id }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <label for="qty-{{ $id }}" class="sr-only">Quantity</label>
                                        <input type="number" id="qty-{{ $id }}" name="quantity" value="{{ $item['quantity'] }}" min="1" class="w-20 rounded-md border-gray-300 shadow-sm py-1.5 text-center focus:ring-brand-forest focus:border-brand-forest">
                                        <button type="submit" class="text-sm text-gray-500 hover:text-brand-forest underline">Update</button>
                                    </form>
                                    
                                    <div class="font-bold text-gray-900">
                                        Subtotal: ${{ number_format($item['price'] * $item['quantity'], 2) }}
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="w-full lg:w-1/3">
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 sticky top-24">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Order Summary</h2>
                    
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span>${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Shipping estimate</span>
                            <span>$5.00</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Tax estimate</span>
                            <span>${{ number_format($subtotal * 0.12, 2) }}</span>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-4 mb-8">
                        <div class="flex justify-between text-xl font-bold text-gray-900">
                            <span>Total</span>
                            <span>${{ number_format($subtotal + 5.00 + ($subtotal * 0.12), 2) }}</span>
                        </div>
                    </div>
                    
                    <a href="/checkout" class="w-full btn-primary py-4 text-lg text-center block">
                        Proceed to Checkout
                    </a>
                    
                    <div class="mt-4 text-center">
                        <a href="/catalog" class="text-sm font-medium text-brand-forest hover:underline">
                            or Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

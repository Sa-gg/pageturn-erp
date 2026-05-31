@extends('layouts.app')

@section('title', 'Checkout | PageTurn Books')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-8 rounded-md">
            <p class="text-sm text-red-700">{{ session('error') }}</p>
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Checkout Form -->
        <div class="w-full lg:w-2/3">
            <form action="/checkout" method="POST" id="checkout-form">
                @csrf
                
                <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden mb-6">
                    <div class="p-6 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-xl font-bold text-gray-900">1. Contact & Shipping Information</h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <input type="text" id="customer_name" name="customer_name" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-forest focus:ring focus:ring-brand-forest focus:ring-opacity-50">
                            </div>
                            <div>
                                <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                <input type="email" id="customer_email" name="customer_email" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-forest focus:ring focus:ring-brand-forest focus:ring-opacity-50">
                            </div>
                        </div>
                        <div>
                            <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-1">Full Shipping Address</label>
                            <textarea id="shipping_address" name="shipping_address" rows="3" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-forest focus:ring focus:ring-brand-forest focus:ring-opacity-50"></textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div>
                                <label for="shipping_city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                <input type="text" id="shipping_city" name="shipping_city" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-forest focus:ring focus:ring-brand-forest focus:ring-opacity-50">
                            </div>
                            <div>
                                <label for="shipping_zip" class="block text-sm font-medium text-gray-700 mb-1">ZIP Code</label>
                                <input type="text" id="shipping_zip" name="shipping_zip" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-forest focus:ring focus:ring-brand-forest focus:ring-opacity-50">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden mb-6">
                    <div class="p-6 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-xl font-bold text-gray-900">2. Payment Method</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="card" checked class="text-brand-forest focus:ring-brand-forest w-5 h-5">
                                <span class="ml-3 font-medium text-gray-900 flex items-center gap-2">
                                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                    Credit / Debit Card
                                </span>
                            </label>
                            <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="paypal" class="text-brand-forest focus:ring-brand-forest w-5 h-5">
                                <span class="ml-3 font-medium text-gray-900 flex items-center gap-2">
                                    <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M7.076 21.337H2.47a.641.641 0 01-.633-.74L4.944.901C5.026.382 5.474 0 5.998 0h7.46c2.57 0 4.578.543 5.69 1.81 1.01 1.15 1.304 2.42 1.012 4.287-.023.143-.047.288-.077.437-.983 5.05-4.349 6.797-8.647 6.797h-2.19c-.524 0-.968.382-1.05.9l-1.12 7.106z"/></svg>
                                    PayPal
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Order Summary Sidebar -->
        <div class="w-full lg:w-1/3">
            <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 sticky top-24">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Review Order</h2>
                
                <ul class="divide-y divide-gray-200 mb-6">
                    @foreach($cart as $item)
                    <li class="py-3 flex gap-3">
                        <div class="w-12 h-16 bg-gray-200 flex-shrink-0 rounded overflow-hidden">
                            <img src="{{ $item['cover_image'] ?? '' }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 text-sm">
                            <h4 class="font-bold text-gray-900 line-clamp-1">{{ $item['title'] }}</h4>
                            <p class="text-gray-500">Qty: {{ $item['quantity'] }} &times; ${{ number_format($item['price'], 2) }}</p>
                            <p class="font-bold mt-1">${{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                        </div>
                    </li>
                    @endforeach
                </ul>

                <div class="space-y-3 mb-6 pt-4 border-t border-gray-200 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Shipping</span>
                        <span>${{ number_format($shipping, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Tax</span>
                        <span>${{ number_format($tax, 2) }}</span>
                    </div>
                </div>
                
                <div class="border-t border-gray-200 pt-4 mb-8 text-lg font-bold">
                    <div class="flex justify-between text-gray-900">
                        <span>Total to Pay</span>
                        <span>${{ number_format($total, 2) }}</span>
                    </div>
                </div>
                
                <button type="submit" form="checkout-form" class="w-full btn-primary py-4 text-lg text-center block">
                    Place Order Now
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

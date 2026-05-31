@extends('layouts.app')

@section('title', 'Shopping Cart | PageTurn Books')

@section('styles')
<style>
.cart-item-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid rgba(0,0,0,0.05);
    box-shadow: 0 2px 10px rgba(0,0,0,0.04);
    transition: box-shadow 0.2s ease;
    overflow: hidden;
}
.dark .cart-item-card {
    background: #1E1E2A;
    border-color: rgba(255,255,255,0.05);
    box-shadow: 0 2px 10px rgba(0,0,0,0.3);
}
.cart-item-card:hover {
    box-shadow: 0 6px 24px rgba(0,0,0,0.08);
}
.dark .cart-item-card:hover {
    box-shadow: 0 6px 24px rgba(0,0,0,0.4);
}
.qty-btn {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.15s ease;
}
.qty-btn:hover {
    background: rgba(212,168,83,0.12);
    color: #D4A853;
}
.summary-card {
    background: #fff;
    border-radius: 20px;
    border: 1px solid rgba(0,0,0,0.05);
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    overflow: hidden;
}
.dark .summary-card {
    background: #1E1E2A;
    border-color: rgba(255,255,255,0.05);
}
</style>
@endsection

@section('content')
<div class="bg-brand-cream dark:bg-brand-ink-2 min-h-screen py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="font-serif text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                    @if(!empty($cart))
                        Your Cart <span class="text-brand-gold text-2xl">({{ count($cart) }})</span>
                    @else
                        Your Cart
                    @endif
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Review your items before checkout</p>
            </div>
            <a href="/catalog" class="hidden sm:flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 hover:text-brand-gold transition-colors font-medium">
                <i class="fas fa-arrow-left text-xs"></i>
                Continue shopping
            </a>
        </div>

        @if(empty($cart))
            {{-- Empty State --}}
            <div class="summary-card py-20 px-8 text-center max-w-md mx-auto">
                <div class="w-24 h-24 bg-gray-50 dark:bg-white/5 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-shopping-bag text-gray-300 dark:text-white/20 text-4xl"></i>
                </div>
                <h2 class="font-serif text-2xl font-bold text-gray-900 dark:text-white mb-2">Your cart is empty</h2>
                <p class="text-gray-500 dark:text-gray-400 text-sm mb-8 leading-relaxed">Looks like you haven't picked any books yet. Browse our curated collection and find your next great read.</p>
                <a href="/catalog" class="inline-flex items-center gap-2 bg-brand-gold hover:bg-brand-gold-light text-brand-ink font-bold px-8 py-3.5 rounded-xl transition-all duration-200 shadow-lg shadow-brand-gold/20 hover:-translate-y-0.5">
                    <i class="fas fa-book-open text-sm"></i>
                    Browse Collection
                </a>
                <div class="mt-6">
                    <a href="/categories" class="text-sm text-gray-400 hover:text-brand-gold transition-colors">Or explore by category →</a>
                </div>
            </div>
        @else
            <div class="flex flex-col lg:flex-row gap-7">

                {{-- Cart Items --}}
                <div class="flex-1 space-y-4">
                    @foreach($cart as $id => $item)
                    @php
                        $isbnCovers = [
                            '978-0747532699' => 'https://covers.openlibrary.org/b/isbn/9780747532699-M.jpg',
                            '978-0451524935' => 'https://covers.openlibrary.org/b/isbn/9780451524935-M.jpg',
                            '978-0132350884' => 'https://covers.openlibrary.org/b/isbn/9780132350884-M.jpg',
                            '978-0062316097' => 'https://covers.openlibrary.org/b/isbn/9780062316097-M.jpg',
                        ];
                        $coverUrl = $item['cover_image'] ?? 'https://images.unsplash.com/photo-1621351183012-e2f9972dd9bf?w=200&h=280&fit=crop';
                    @endphp
                    <div class="cart-item-card">
                        <div class="flex items-start gap-5 p-5">
                            {{-- Book Cover --}}
                            <a href="/catalog/{{ $id }}" class="flex-shrink-0">
                                <div class="w-20 h-28 rounded-lg overflow-hidden bg-gray-100 dark:bg-white/5 shadow-sm">
                                    <img
                                        src="{{ $coverUrl }}"
                                        alt="{{ $item['title'] }}"
                                        class="w-full h-full object-cover"
                                        onerror="this.src='https://images.unsplash.com/photo-1532012197267-da84d127e765?w=150&h=210&fit=crop'"
                                    >
                                </div>
                            </a>

                            {{-- Item Info --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <h3 class="font-semibold text-gray-900 dark:text-white leading-snug line-clamp-2 mb-0.5">
                                            <a href="/catalog/{{ $id }}" class="hover:text-brand-gold transition-colors">{{ $item['title'] }}</a>
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Paperback</p>
                                    </div>
                                    {{-- Remove --}}
                                    <form action="/cart/remove/{{ $id }}" method="POST" class="flex-shrink-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 dark:text-white/20 hover:text-red-500 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all" title="Remove">
                                            <i class="fas fa-times text-sm"></i>
                                        </button>
                                    </form>
                                </div>

                                <div class="mt-4 flex items-center justify-between">
                                    {{-- Quantity stepper --}}
                                    <form action="/cart/update/{{ $id }}" method="POST" class="flex items-center gap-1" id="qty-form-{{ $id }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" onclick="stepQty('{{ $id }}', -1)" class="qty-btn text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-white/5">−</button>
                                        <input
                                            type="number"
                                            name="quantity"
                                            id="qty-{{ $id }}"
                                            value="{{ $item['quantity'] }}"
                                            min="1"
                                            class="w-10 h-8 text-center text-sm font-bold text-gray-900 dark:text-white bg-transparent border-0 focus:ring-0 focus:outline-none"
                                            onchange="document.getElementById('qty-form-{{ $id }}').submit()"
                                        >
                                        <button type="button" onclick="stepQty('{{ $id }}', 1)" class="qty-btn text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-white/5">+</button>
                                    </form>

                                    {{-- Line total --}}
                                    <div class="text-right">
                                        <span class="font-bold text-gray-900 dark:text-white text-base">${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                        @if($item['quantity'] > 1)
                                        <p class="text-xs text-gray-400">${{ number_format($item['price'], 2) }} each</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    {{-- Promo code strip --}}
                    <div class="bg-white dark:bg-brand-ink-3 rounded-2xl border border-dashed border-gray-200 dark:border-white/10 p-4">
                        <div class="flex gap-3">
                            <div class="relative flex-1">
                                <i class="fas fa-tag absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 dark:text-white/20 text-xs"></i>
                                <input type="text" placeholder="Promo code" class="w-full pl-8 pr-3 py-2.5 rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:border-brand-gold focus:ring-1 focus:ring-brand-gold transition-colors">
                            </div>
                            <button class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-white/10 text-sm font-semibold text-gray-600 dark:text-gray-300 hover:border-brand-gold hover:text-brand-gold transition-colors">Apply</button>
                        </div>
                    </div>
                </div>

                {{-- Order Summary --}}
                <div class="w-full lg:w-80 flex-shrink-0">
                    <div class="summary-card sticky top-24">
                        {{-- Header --}}
                        <div class="px-6 pt-6 pb-4 border-b border-gray-100 dark:border-white/5">
                            <h2 class="font-serif text-lg font-bold text-gray-900 dark:text-white">Order Summary</h2>
                        </div>

                        {{-- Line items --}}
                        <div class="px-6 py-5 space-y-3">
                            <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                                <span>Subtotal ({{ collect($cart)->sum('quantity') }} items)</span>
                                <span class="font-medium text-gray-900 dark:text-white">${{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                                <span>Shipping</span>
                                <span class="text-emerald-500 font-medium">
                                    @if($subtotal >= 50) Free
                                    @else $5.00
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                                <span>Tax (12%)</span>
                                <span class="font-medium text-gray-900 dark:text-white">${{ number_format($subtotal * 0.12, 2) }}</span>
                            </div>

                            @if($subtotal < 50)
                            {{-- Free shipping nudge --}}
                            <div class="mt-2 p-3 bg-amber-50 dark:bg-amber-900/20 rounded-xl">
                                <p class="text-xs text-amber-700 dark:text-amber-400 font-medium">
                                    <i class="fas fa-truck text-[10px] mr-1"></i>
                                    Add <strong>${{ number_format(50 - $subtotal, 2) }}</strong> more for free shipping!
                                </p>
                                <div class="mt-2 h-1.5 bg-amber-200 dark:bg-amber-900 rounded-full overflow-hidden">
                                    <div class="h-full bg-amber-500 rounded-full transition-all" style="width: {{ min(100, ($subtotal / 50) * 100) }}%"></div>
                                </div>
                            </div>
                            @endif
                        </div>

                        {{-- Total --}}
                        <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-white/3">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-gray-900 dark:text-white">Total</span>
                                <span class="font-bold text-2xl text-gray-900 dark:text-white font-serif">
                                    ${{ number_format($subtotal + ($subtotal >= 50 ? 0 : 5.00) + ($subtotal * 0.12), 2) }}
                                </span>
                            </div>
                            @if($subtotal >= 50)
                            <p class="text-xs text-emerald-500 mt-1 flex items-center gap-1">
                                <i class="fas fa-check-circle"></i> Free shipping applied!
                            </p>
                            @endif
                        </div>

                        {{-- CTA --}}
                        <div class="px-6 pb-6 pt-4">
                            <a href="/checkout" class="w-full bg-brand-gold hover:bg-brand-gold-light text-brand-ink font-bold py-4 px-6 rounded-xl flex items-center justify-center gap-2 transition-all duration-200 shadow-lg shadow-brand-gold/20 hover:-translate-y-0.5 text-sm">
                                <i class="fas fa-lock text-xs"></i>
                                Proceed to Checkout
                            </a>

                            {{-- Trust row --}}
                            <div class="mt-4 grid grid-cols-3 gap-2 text-center">
                                <div class="text-xs text-gray-400 dark:text-gray-500 flex flex-col items-center gap-1">
                                    <i class="fas fa-shield-alt text-brand-gold/60 text-base"></i>
                                    Secure
                                </div>
                                <div class="text-xs text-gray-400 dark:text-gray-500 flex flex-col items-center gap-1">
                                    <i class="fas fa-undo text-brand-gold/60 text-base"></i>
                                    Returns
                                </div>
                                <div class="text-xs text-gray-400 dark:text-gray-500 flex flex-col items-center gap-1">
                                    <i class="fas fa-truck text-brand-gold/60 text-base"></i>
                                    Fast ship
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
function stepQty(id, delta) {
    const input = document.getElementById('qty-' + id);
    const newVal = Math.max(1, parseInt(input.value || 1) + delta);
    input.value = newVal;
    // Auto-submit the form
    document.getElementById('qty-form-' + id).submit();
}
</script>
@endsection

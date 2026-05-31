@extends('layouts.app')

@section('title', ($book['title'] ?? 'Book') . ' | PageTurn Books')

@section('styles')
<style>
.detail-card {
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 30px rgba(0,0,0,0.06);
    border: 1px solid rgba(0,0,0,0.04);
}
.dark .detail-card {
    background: #1E1E2A;
    border-color: rgba(255,255,255,0.05);
}
.badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.5px;
}
</style>
@endsection

@section('content')
@php
$isbnCovers = [
    '978-0747532699' => 'https://covers.openlibrary.org/b/isbn/9780747532699-L.jpg',
    '978-0451524935' => 'https://covers.openlibrary.org/b/isbn/9780451524935-L.jpg',
    '978-0132350884' => 'https://covers.openlibrary.org/b/isbn/9780132350884-L.jpg',
    '978-0062316097' => 'https://covers.openlibrary.org/b/isbn/9780062316097-L.jpg',
    '978-0394800011' => 'https://covers.openlibrary.org/b/isbn/9780394800011-L.jpg',
    '978-1569319017' => 'https://covers.openlibrary.org/b/isbn/9781569319017-L.jpg',
    '978-0451526342' => 'https://covers.openlibrary.org/b/isbn/9780451526342-L.jpg',
    '978-0137081073' => 'https://covers.openlibrary.org/b/isbn/9780137081073-L.jpg',
];
$isbn = $book['isbn'] ?? '';
$coverUrl = $book['cover_image_url'] ?? ($isbnCovers[$isbn] ?? 'https://images.unsplash.com/photo-1621351183012-e2f9972dd9bf?w=400&h=580&fit=crop&q=80');
@endphp

<div class="bg-brand-cream dark:bg-brand-ink-2 min-h-screen py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Breadcrumb --}}
        <div class="mb-6 flex items-center gap-2 text-sm">
            <a href="/catalog" class="text-gray-400 hover:text-brand-gold transition-colors flex items-center gap-1.5">
                <i class="fas fa-arrow-left text-xs"></i> Catalog
            </a>
            <span class="text-gray-300 dark:text-white/20">/</span>
            <span class="text-gray-600 dark:text-gray-400 line-clamp-1">{{ $book['title'] }}</span>
        </div>

        <div class="detail-card">
            <div class="flex flex-col md:flex-row">
                {{-- Cover --}}
                <div class="w-full md:w-2/5 lg:w-1/3 bg-gray-50 dark:bg-brand-ink p-10 flex justify-center items-start">
                    <div class="relative">
                        <img
                            src="{{ $coverUrl }}"
                            alt="{{ $book['title'] }}"
                            class="rounded-xl shadow-2xl max-w-[240px] w-full object-cover"
                            onerror="this.src='https://images.unsplash.com/photo-1532012197267-da84d127e765?w=300&h=420&fit=crop'"
                        >
                        <div class="absolute -bottom-3 -right-3 w-20 h-20 bg-brand-gold/10 rounded-full blur-xl pointer-events-none"></div>
                    </div>
                </div>

                {{-- Details --}}
                <div class="w-full md:w-3/5 lg:w-2/3 p-8 lg:p-12">
                    {{-- Category + Format --}}
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="badge bg-brand-gold/10 text-brand-gold">
                            <i class="fas fa-tag text-[9px]"></i>
                            {{ $book['category']['name'] ?? 'Uncategorized' }}
                        </span>
                        @if(isset($book['format']))
                        <span class="badge bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-gray-300">
                            <i class="fas fa-{{ $book['format'] === 'digital' ? 'tablet-alt' : 'book' }} text-[9px]"></i>
                            {{ ucfirst($book['format']) }}
                        </span>
                        @endif
                        @if($book['is_available'] ?? false)
                        <span class="badge bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400">
                            <i class="fas fa-check-circle text-[9px]"></i>
                            In Stock
                        </span>
                        @else
                        <span class="badge bg-red-50 dark:bg-red-900/20 text-red-500">
                            <i class="fas fa-times-circle text-[9px]"></i>
                            Out of Stock
                        </span>
                        @endif
                    </div>

                    <h1 class="font-serif text-3xl md:text-4xl font-bold text-gray-900 dark:text-white leading-tight tracking-tight mb-2">{{ $book['title'] }}</h1>
                    <p class="text-base text-gray-500 dark:text-gray-400 mb-6">
                        by <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $book['author']['name'] ?? 'Unknown Author' }}</span>
                    </p>

                    {{-- Price --}}
                    <div class="flex items-baseline gap-3 mb-6 pb-6 border-b border-gray-100 dark:border-white/5">
                        <span class="text-4xl font-bold text-gray-900 dark:text-white font-serif">${{ number_format($book['price'] ?? 0, 2) }}</span>
                        <span class="text-sm text-gray-400 line-through">${{ number_format(($book['price'] ?? 0) * 1.2, 2) }}</span>
                        <span class="badge bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 text-xs">Save 17%</span>
                    </div>

                    {{-- Description --}}
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed text-sm mb-8">{{ $book['description'] ?? 'No description available.' }}</p>

                    {{-- Meta --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-8 text-xs">
                        <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                            <span class="block text-gray-400 uppercase tracking-wider mb-1">ISBN</span>
                            <span class="font-medium text-gray-700 dark:text-gray-300">{{ $book['isbn'] ?? 'N/A' }}</span>
                        </div>
                        <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                            <span class="block text-gray-400 uppercase tracking-wider mb-1">Pages</span>
                            <span class="font-medium text-gray-700 dark:text-gray-300">{{ $book['pages'] ?? 'N/A' }}</span>
                        </div>
                        <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3">
                            <span class="block text-gray-400 uppercase tracking-wider mb-1">Language</span>
                            <span class="font-medium text-gray-700 dark:text-gray-300">{{ $book['language'] ?? 'English' }}</span>
                        </div>
                    </div>

                    {{-- Add to cart form --}}
                    <form action="/cart/add" method="POST" class="flex gap-3">
                        @csrf
                        <input type="hidden" name="book_id" value="{{ $book['id'] }}">
                        <input type="hidden" name="title" value="{{ $book['title'] }}">
                        <input type="hidden" name="price" value="{{ $book['price'] }}">
                        <input type="hidden" name="cover_image" value="{{ $coverUrl }}">

                        <div class="flex items-center border border-gray-200 dark:border-white/10 rounded-xl overflow-hidden">
                            <button type="button" onclick="changeQty(-1)" class="w-10 h-12 flex items-center justify-center text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5 transition-colors text-lg font-bold">−</button>
                            <input type="number" id="quantity" name="quantity" value="1" min="1" max="99" class="w-12 h-12 border-0 text-center text-sm font-semibold text-gray-900 dark:text-white bg-transparent focus:ring-0 focus:outline-none">
                            <button type="button" onclick="changeQty(1)" class="w-10 h-12 flex items-center justify-center text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5 transition-colors text-lg font-bold">+</button>
                        </div>

                        <button
                            type="submit"
                            class="flex-1 bg-brand-gold hover:bg-brand-gold-light text-brand-ink font-bold py-3 px-6 rounded-xl transition-all duration-200 flex items-center justify-center gap-2 shadow-lg shadow-brand-gold/20 hover:-translate-y-0.5 {{ !($book['is_available'] ?? true) ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ !($book['is_available'] ?? true) ? 'disabled' : '' }}
                        >
                            <i class="fas fa-shopping-bag text-sm"></i>
                            {{ ($book['is_available'] ?? true) ? 'Add to Cart' : 'Out of Stock' }}
                        </button>
                    </form>

                    {{-- Trust badges --}}
                    <div class="mt-6 flex flex-wrap gap-5 text-xs text-gray-400">
                        <span class="flex items-center gap-1.5"><i class="fas fa-shield-alt text-brand-gold"></i> Secure checkout</span>
                        <span class="flex items-center gap-1.5"><i class="fas fa-undo text-brand-gold"></i> Easy returns</span>
                        <span class="flex items-center gap-1.5"><i class="fas fa-truck text-brand-gold"></i> Fast delivery</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function changeQty(delta) {
    const input = document.getElementById('quantity');
    const current = parseInt(input.value) || 1;
    input.value = Math.max(1, current + delta);
}
</script>
@endsection

@extends('layouts.app')

@section('title', $book['title'] . ' | PageTurn Books')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-6">
        <a href="/catalog" class="text-gray-500 hover:text-brand-forest flex items-center gap-1 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Catalog
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="flex flex-col md:flex-row">
            <!-- Book Cover -->
            <div class="w-full md:w-1/3 bg-gray-50 p-8 flex justify-center items-center">
                <img src="{{ $book['cover_image'] ?? 'https://via.placeholder.com/400x600?text=No+Cover' }}" alt="{{ $book['title'] }}" class="rounded-lg shadow-xl max-w-full h-auto object-cover max-h-[500px]">
            </div>
            
            <!-- Book Details -->
            <div class="w-full md:w-2/3 p-8 lg:p-12">
                <div class="uppercase tracking-wide text-sm text-brand-forest font-bold mb-1">
                    {{ $book['category']['name'] ?? 'Uncategorized' }}
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight mb-2">{{ $book['title'] }}</h1>
                <p class="text-xl text-gray-600 mb-6">By <span class="font-medium text-brand-brown">{{ $book['author']['name'] ?? 'Unknown Author' }}</span></p>
                
                <div class="flex items-center gap-4 mb-8 pb-8 border-b border-gray-100">
                    <span class="text-4xl font-bold text-gray-900">${{ number_format($book['price'] ?? 0, 2) }}</span>
                    @if(($book['stock_quantity'] ?? 0) > 0)
                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">In Stock ({{ $book['stock_quantity'] }})</span>
                    @else
                        <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">Out of Stock</span>
                    @endif
                </div>
                
                <div class="prose prose-sm sm:prose text-gray-600 mb-8 max-w-none">
                    <p>{{ $book['description'] ?? 'No description available for this book.' }}</p>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-8 text-sm text-gray-600">
                    <div>
                        <span class="block font-medium text-gray-900">ISBN</span>
                        {{ $book['isbn'] ?? 'N/A' }}
                    </div>
                    <div>
                        <span class="block font-medium text-gray-900">Published Date</span>
                        {{ isset($book['published_date']) ? \Carbon\Carbon::parse($book['published_date'])->format('F j, Y') : 'N/A' }}
                    </div>
                </div>
                
                <form action="/cart/add" method="POST" class="flex gap-4">
                    @csrf
                    <input type="hidden" name="book_id" value="{{ $book['id'] }}">
                    <input type="hidden" name="title" value="{{ $book['title'] }}">
                    <input type="hidden" name="price" value="{{ $book['price'] }}">
                    <input type="hidden" name="cover_image" value="{{ $book['cover_image'] ?? '' }}">
                    
                    <div class="w-24">
                        <label for="quantity" class="sr-only">Quantity</label>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $book['stock_quantity'] ?? 10 }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-forest focus:ring focus:ring-brand-forest focus:ring-opacity-50 py-3 text-center">
                    </div>
                    
                    <button type="submit" class="flex-grow btn-primary py-3 text-lg flex justify-center items-center gap-2" {{ ($book['stock_quantity'] ?? 0) <= 0 ? 'disabled' : '' }}>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        {{ ($book['stock_quantity'] ?? 0) > 0 ? 'Add to Cart' : 'Out of Stock' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'PageTurn Books - Your Next Great Read Awaits')

@section('content')
<!-- Hero Section -->
<div class="relative bg-brand-brown text-brand-cream overflow-hidden">
    <div class="absolute inset-0 bg-cover bg-center opacity-30" style="background-image: url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?q=80&w=2070&auto=format&fit=crop');"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32 flex flex-col items-center text-center">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold tracking-tight mb-6">
            Discover Worlds Hidden in Pages
        </h1>
        <p class="mt-4 text-xl md:text-2xl max-w-2xl text-brand-cream opacity-90 mb-10">
            From thrilling mysteries to heartwarming romances, find your next favorite book at PageTurn Books.
        </p>
        <div class="flex gap-4">
            <a href="/catalog" class="bg-brand-forest hover:bg-green-700 text-white font-semibold py-3 px-8 rounded-full shadow-lg transition-transform transform hover:scale-105">
                Browse Catalog
            </a>
            <a href="/categories" class="bg-transparent border-2 border-brand-cream hover:bg-brand-cream hover:text-brand-brown text-brand-cream font-semibold py-3 px-8 rounded-full shadow-lg transition-colors">
                Explore Categories
            </a>
        </div>
    </div>
</div>

<!-- Featured Books -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Featured Books</h2>
            <p class="mt-2 text-gray-600">Hand-picked recommendations just for you.</p>
        </div>
        <a href="/catalog" class="text-brand-forest font-medium hover:underline flex items-center gap-1">
            View All
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </a>
    </div>

    @if(empty($featuredBooks))
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        Unable to load featured books at this time. Please check back later.
                    </p>
                </div>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($featuredBooks as $book)
            <div class="card group flex flex-col h-full">
                <div class="relative pb-[140%] overflow-hidden bg-gray-200">
                    <img src="{{ $book['cover_image'] ?? 'https://via.placeholder.com/400x600?text=No+Cover' }}" alt="{{ $book['title'] }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                </div>
                <div class="p-5 flex flex-col flex-grow">
                    <h3 class="text-lg font-bold text-gray-900 mb-1 line-clamp-1"><a href="/catalog/{{ $book['id'] }}" class="hover:text-brand-forest">{{ $book['title'] }}</a></h3>
                    <p class="text-sm text-gray-500 mb-3">{{ $book['author']['name'] ?? 'Unknown Author' }}</p>
                    <div class="mt-auto flex justify-between items-center">
                        <span class="text-xl font-bold text-brand-brown">${{ number_format($book['price'] ?? 0, 2) }}</span>
                        <a href="/catalog/{{ $book['id'] }}" class="text-brand-forest p-2 rounded-full hover:bg-green-50 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Newsletter Section -->
<div class="bg-brand-cream border-t border-b border-gray-200 py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Stay in the Loop</h2>
        <p class="text-gray-600 mb-8">Subscribe to our newsletter to get updates on new releases, special offers, and book recommendations.</p>
        <form class="flex flex-col sm:flex-row gap-2 justify-center max-w-md mx-auto">
            <input type="email" placeholder="Enter your email" class="flex-grow rounded-md border-gray-300 shadow-sm focus:border-brand-forest focus:ring focus:ring-brand-forest focus:ring-opacity-50 px-4 py-3" required>
            <button type="submit" class="btn-primary py-3">Subscribe</button>
        </form>
    </div>
</div>
@endsection

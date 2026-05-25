@extends('layouts.app')

@section('title', 'Browse Catalog | PageTurn Books')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Breadcrumbs & Search -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <h1 class="text-3xl font-bold text-gray-900">Book Catalog</h1>
        
        <form action="/catalog" method="GET" class="w-full md:w-1/3 relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title, author, or ISBN..." class="w-full pl-10 pr-4 py-2 border-gray-300 rounded-full shadow-sm focus:border-brand-forest focus:ring focus:ring-brand-forest focus:ring-opacity-50">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <button type="submit" class="hidden">Search</button>
        </form>
    </div>

    <div class="flex flex-col md:flex-row gap-8">
        <!-- Sidebar Filters -->
        <aside class="w-full md:w-64 flex-shrink-0">
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 sticky top-24">
                <h3 class="font-bold text-lg text-gray-900 mb-4 border-b pb-2">Filters</h3>
                
                <form action="/catalog" method="GET">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    
                    <!-- Example static filters. Real implementation would pull categories/authors from API -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category ID</label>
                        <input type="number" name="category_id" value="{{ request('category_id') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-forest focus:ring focus:ring-brand-forest focus:ring-opacity-50" placeholder="e.g. 1">
                    </div>
                    
                    <button type="submit" class="w-full btn-secondary">Apply Filters</button>
                    @if(request()->hasAny(['search', 'category_id', 'author_id']))
                        <a href="/catalog" class="block text-center mt-2 text-sm text-gray-500 hover:text-gray-700">Clear All</a>
                    @endif
                </form>
            </div>
        </aside>

        <!-- Main Grid -->
        <div class="flex-grow">
            @if(empty($books) || empty($books['data']))
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No books found</h3>
                    <p class="text-gray-500">We couldn't find anything matching your criteria. Try adjusting your filters or search term.</p>
                    <a href="/catalog" class="mt-4 inline-block text-brand-forest font-medium hover:underline">Clear Filters</a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($books['data'] as $book)
                    <div class="card group flex flex-col h-full">
                        <div class="relative pb-[140%] overflow-hidden bg-gray-100">
                            <img src="{{ $book['cover_image'] ?? 'https://via.placeholder.com/400x600?text=No+Cover' }}" alt="{{ $book['title'] }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        </div>
                        <div class="p-4 flex flex-col flex-grow">
                            <h3 class="text-md font-bold text-gray-900 mb-1 line-clamp-2"><a href="/catalog/{{ $book['id'] }}" class="hover:text-brand-forest">{{ $book['title'] }}</a></h3>
                            <p class="text-sm text-gray-500 mb-3">{{ $book['author']['name'] ?? 'Unknown Author' }}</p>
                            <div class="mt-auto flex justify-between items-center">
                                <span class="text-lg font-bold text-brand-brown">${{ number_format($book['price'] ?? 0, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Pagination placeholder (since we get a simplified API response without links) -->
                @if(isset($books['next_page_url']) || isset($books['prev_page_url']))
                <div class="mt-10 flex justify-center gap-2">
                    @if($books['prev_page_url'])
                        <a href="?page={{ $books['current_page'] - 1 }}" class="px-4 py-2 border rounded text-gray-600 hover:bg-gray-50">Previous</a>
                    @endif
                    @if($books['next_page_url'])
                        <a href="?page={{ $books['current_page'] + 1 }}" class="px-4 py-2 border rounded text-gray-600 hover:bg-gray-50">Next</a>
                    @endif
                </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection

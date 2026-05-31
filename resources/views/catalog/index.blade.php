@extends('layouts.app')

@section('title', 'Browse Catalog | PageTurn Books')

@section('styles')
<style>
.catalog-card {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    border: 1px solid rgba(0,0,0,0.05);
    box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    transition: transform 0.28s ease, box-shadow 0.28s ease;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    height: 100%;
}
.dark .catalog-card {
    background: #1E1E2A;
    border-color: rgba(255,255,255,0.05);
}
.catalog-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 16px 40px rgba(0,0,0,0.1);
}
.dark .catalog-card:hover { box-shadow: 0 16px 40px rgba(0,0,0,0.45); }

.cover-wrap {
    position: relative;
    padding-bottom: 140%;
    overflow: hidden;
    background: #f0ece8;
}
.dark .cover-wrap { background: #2a2a38; }
.cover-wrap img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}
.catalog-card:hover .cover-wrap img { transform: scale(1.06); }

.cover-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.55) 0%, transparent 55%);
    opacity: 0;
    transition: opacity 0.28s;
    display: flex;
    align-items: flex-end;
    padding: 12px;
}
.catalog-card:hover .cover-overlay { opacity: 1; }
</style>
@endsection

@section('content')
<div class="bg-brand-cream dark:bg-brand-ink-2 min-h-screen">

    {{-- Page header --}}
    <div class="bg-white dark:bg-brand-ink border-b border-gray-100 dark:border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <p class="text-xs font-bold tracking-[3px] uppercase text-brand-gold mb-1">Discover</p>
                    <h1 class="font-serif text-4xl font-bold text-gray-900 dark:text-white tracking-tight">Book Catalog</h1>
                </div>
                {{-- Search --}}
                <form action="/catalog" method="GET" class="w-full md:w-80">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search title, author, ISBN…"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 text-gray-900 dark:text-white placeholder-gray-400 text-sm focus:outline-none focus:border-brand-gold focus:ring-1 focus:ring-brand-gold transition-colors"
                        >
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex flex-col lg:flex-row gap-8">

            {{-- Sidebar Filters --}}
            <aside class="w-full lg:w-56 flex-shrink-0">
                <div class="bg-white dark:bg-brand-ink-3 rounded-2xl border border-gray-100 dark:border-white/5 p-5 sticky top-24">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="font-semibold text-gray-900 dark:text-white text-sm">Filters</h3>
                        @if(request()->hasAny(['search', 'category_id', 'author_id']))
                            <a href="/catalog" class="text-xs text-brand-gold hover:text-brand-gold-light font-medium">Clear all</a>
                        @endif
                    </div>

                    <form action="/catalog" method="GET" id="filter-form">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif

                        {{-- Categories --}}
                        <div class="mb-5">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Category</p>
                            <div class="space-y-1.5">
                                @php
                                $staticCategories = [
                                    ['id' => 1, 'name' => 'Fiction'],
                                    ['id' => 2, 'name' => 'Non-Fiction'],
                                    ['id' => 3, 'name' => 'Science & Tech'],
                                    ['id' => 4, 'name' => 'Children'],
                                    ['id' => 5, 'name' => 'Academic'],
                                    ['id' => 6, 'name' => 'Comics & Manga'],
                                ];
                                @endphp
                                @foreach($staticCategories as $cat)
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                    <input
                                        type="radio"
                                        name="category_id"
                                        value="{{ $cat['id'] }}"
                                        {{ request('category_id') == $cat['id'] ? 'checked' : '' }}
                                        class="w-3.5 h-3.5 accent-brand-gold"
                                        onchange="document.getElementById('filter-form').submit()"
                                    >
                                    <span class="text-sm text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white transition-colors">{{ $cat['name'] }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit" class="w-full text-sm font-semibold bg-brand-gold hover:bg-brand-gold-light text-brand-ink py-2.5 rounded-xl transition-colors">
                            Apply
                        </button>
                    </form>
                </div>
            </aside>

            {{-- Main Grid --}}
            <div class="flex-grow">
                @if(empty($books) || empty($books['data']))
                    <div class="bg-white dark:bg-brand-ink-3 rounded-2xl border border-gray-100 dark:border-white/5 p-16 text-center">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-search text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">No books found</h3>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Try a different search term or clear your filters.</p>
                        <a href="/catalog" class="mt-4 inline-block text-brand-gold hover:text-brand-gold-light text-sm font-medium">Clear Filters →</a>
                    </div>
                @else
                    {{-- Results count --}}
                    <div class="flex items-center justify-between mb-5">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $books['total'] ?? count($books['data']) }}</span> books found
                        </p>
                    </div>

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
                    @endphp

                    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-5">
                        @foreach($books['data'] as $book)
                        @php
                            $isbn = $book['isbn'] ?? '';
                            $coverUrl = $book['cover_image_url'] ?? ($isbnCovers[$isbn] ?? null) ?? 'https://images.unsplash.com/photo-1621351183012-e2f9972dd9bf?w=300&h=430&fit=crop&q=80';
                            $category = $book['category']['name'] ?? 'Book';
                        @endphp
                        <div class="catalog-card">
                            <div class="cover-wrap">
                                <img
                                    src="{{ $coverUrl }}"
                                    alt="{{ $book['title'] }}"
                                    loading="lazy"
                                    onerror="this.src='https://images.unsplash.com/photo-1532012197267-da84d127e765?w=300&h=420&fit=crop&q=70'"
                                >
                                <div class="cover-overlay">
                                    <a href="/catalog/{{ $book['id'] }}" class="w-full text-center text-white text-xs font-semibold py-2 px-3 rounded-lg bg-white/15 backdrop-blur-sm hover:bg-white/25 transition-all">
                                        View Details →
                                    </a>
                                </div>
                            </div>
                            <div class="p-4 flex flex-col flex-grow">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-brand-gold mb-1.5">{{ $category }}</span>
                                <h3 class="font-semibold text-gray-900 dark:text-white text-sm leading-snug line-clamp-2 mb-1 flex-grow">
                                    <a href="/catalog/{{ $book['id'] }}" class="hover:text-brand-gold dark:hover:text-brand-gold transition-colors">{{ $book['title'] }}</a>
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">{{ $book['author']['name'] ?? 'Unknown' }}</p>
                                <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-white/5">
                                    <span class="font-bold text-gray-900 dark:text-white text-sm">${{ number_format($book['price'] ?? 0, 2) }}</span>
                                    <form action="/cart/add" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="book_id" value="{{ $book['id'] }}">
                                        <input type="hidden" name="title" value="{{ $book['title'] }}">
                                        <input type="hidden" name="price" value="{{ $book['price'] ?? 0 }}">
                                        <input type="hidden" name="cover_image" value="{{ $coverUrl }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-brand-gold/10 hover:bg-brand-gold text-brand-gold hover:text-brand-ink transition-all duration-200" title="Add to Cart">
                                            <i class="fas fa-plus text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if(isset($books['last_page']) && $books['last_page'] > 1)
                    <div class="mt-10 flex justify-center gap-2">
                        @if($books['prev_page_url'])
                            <a href="?page={{ $books['current_page'] - 1 }}{{ request('search') ? '&search='.request('search') : '' }}{{ request('category_id') ? '&category_id='.request('category_id') : '' }}"
                               class="px-4 py-2 rounded-xl border border-gray-200 dark:border-white/10 text-sm text-gray-600 dark:text-gray-300 hover:border-brand-gold hover:text-brand-gold transition-colors">
                                ← Previous
                            </a>
                        @endif
                        <span class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">Page {{ $books['current_page'] }} of {{ $books['last_page'] }}</span>
                        @if($books['next_page_url'])
                            <a href="?page={{ $books['current_page'] + 1 }}{{ request('search') ? '&search='.request('search') : '' }}{{ request('category_id') ? '&category_id='.request('category_id') : '' }}"
                               class="px-4 py-2 rounded-xl border border-gray-200 dark:border-white/10 text-sm text-gray-600 dark:text-gray-300 hover:border-brand-gold hover:text-brand-gold transition-colors">
                                Next →
                            </a>
                        @endif
                    </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

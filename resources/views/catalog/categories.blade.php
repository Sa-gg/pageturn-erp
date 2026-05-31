@extends('layouts.app')

@section('title', 'Browse by Category | PageTurn Books')

@section('content')
<div class="bg-brand-cream dark:bg-brand-ink-2 min-h-screen">

    {{-- Header --}}
    <div class="bg-white dark:bg-brand-ink border-b border-gray-100 dark:border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <p class="text-xs font-bold tracking-[3px] uppercase text-brand-gold mb-2">Browse</p>
            <h1 class="font-serif text-4xl font-bold text-gray-900 dark:text-white tracking-tight">Categories</h1>
            <p class="mt-2 text-gray-500 dark:text-gray-400">Find books by genre, topic, or interest.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        @php
        $iconMap = [
            'Fiction'                => ['icon' => 'fas fa-feather-alt', 'bg' => 'bg-violet-500', 'light' => 'bg-violet-50 dark:bg-violet-900/20', 'text' => 'text-violet-600 dark:text-violet-300', 'border' => 'hover:border-violet-300 dark:hover:border-violet-700'],
            'Non-Fiction'            => ['icon' => 'fas fa-landmark',    'bg' => 'bg-sky-500',    'light' => 'bg-sky-50 dark:bg-sky-900/20',    'text' => 'text-sky-600 dark:text-sky-300',    'border' => 'hover:border-sky-300 dark:hover:border-sky-700'],
            'Science & Technology'   => ['icon' => 'fas fa-laptop-code', 'bg' => 'bg-emerald-500','light' => 'bg-emerald-50 dark:bg-emerald-900/20','text' => 'text-emerald-600 dark:text-emerald-300','border' => 'hover:border-emerald-300 dark:hover:border-emerald-700'],
            'Children & Young Adult' => ['icon' => 'fas fa-child',       'bg' => 'bg-pink-500',   'light' => 'bg-pink-50 dark:bg-pink-900/20',   'text' => 'text-pink-600 dark:text-pink-300',   'border' => 'hover:border-pink-300 dark:hover:border-pink-700'],
            'Comics & Manga'         => ['icon' => 'fas fa-dragon',      'bg' => 'bg-orange-500', 'light' => 'bg-orange-50 dark:bg-orange-900/20','text' => 'text-orange-600 dark:text-orange-300','border' => 'hover:border-orange-300 dark:hover:border-orange-700'],
            'Academic & Textbooks'   => ['icon' => 'fas fa-graduation-cap','bg' => 'bg-indigo-500','light' => 'bg-indigo-50 dark:bg-indigo-900/20','text' => 'text-indigo-600 dark:text-indigo-300','border' => 'hover:border-indigo-300 dark:hover:border-indigo-700'],
        ];
        $default = ['icon' => 'fas fa-book', 'bg' => 'bg-gray-500', 'light' => 'bg-gray-50 dark:bg-white/5', 'text' => 'text-gray-600 dark:text-gray-300', 'border' => 'hover:border-gray-300'];
        @endphp

        @if(empty($categories))
            <div class="text-center py-20">
                <div class="w-16 h-16 bg-gray-100 dark:bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-folder-open text-gray-400 text-2xl"></i>
                </div>
                <p class="text-gray-500 dark:text-gray-400">No categories available. <a href="/catalog" class="text-brand-gold hover:underline">Browse all books →</a></p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($categories as $cat)
                @php
                    $style = $iconMap[$cat['name']] ?? $default;
                @endphp
                <a href="/catalog?category_id={{ $cat['id'] }}"
                   class="group flex items-center gap-5 bg-white dark:bg-brand-ink-3 rounded-2xl p-6 border border-gray-100 dark:border-white/5 {{ $style['border'] }} transition-all duration-300 hover:-translate-y-1 hover:shadow-lg dark:hover:shadow-black/30">
                    <div class="w-14 h-14 rounded-2xl {{ $style['bg'] }} bg-opacity-10 dark:bg-opacity-20 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform duration-300">
                        <i class="{{ $style['icon'] }} {{ $style['text'] }} text-2xl"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h2 class="font-serif font-bold text-gray-900 dark:text-white text-lg leading-snug">{{ $cat['name'] }}</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-1">{{ $cat['description'] ?? '' }}</p>
                        <div class="mt-2 flex items-center gap-1.5">
                            <span class="inline-flex items-center gap-1 text-xs font-semibold {{ $style['text'] }}">
                                <i class="fas fa-book text-[10px]"></i>
                                {{ $cat['books_count'] ?? 0 }} {{ ($cat['books_count'] ?? 0) == 1 ? 'book' : 'books' }}
                            </span>
                        </div>
                    </div>
                    <i class="fas fa-arrow-right text-gray-300 dark:text-white/20 group-hover:text-brand-gold group-hover:translate-x-1 transition-all duration-200"></i>
                </a>
                @endforeach
            </div>

            {{-- CTA --}}
            <div class="mt-14 text-center">
                <a href="/catalog" class="inline-flex items-center gap-2 bg-brand-gold hover:bg-brand-gold-light text-brand-ink font-bold px-8 py-4 rounded-xl transition-all duration-200 shadow-lg shadow-brand-gold/20 hover:-translate-y-0.5">
                    <i class="fas fa-th-large text-sm"></i>
                    View All Books
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

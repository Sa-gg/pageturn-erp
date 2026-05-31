@extends('layouts.app')

@section('title', 'PageTurn Books — Discover Your Next Great Read')

@section('styles')
<style>
/* ===== Hero ===== */
.hero-section {
    min-height: 92vh;
    background: #0D0D14;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
}

.hero-bg {
    position: absolute;
    inset: 0;
    background-image: url('https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=1600&q=80&fit=crop');
    background-size: cover;
    background-position: center 30%;
    opacity: 0.18;
    transition: opacity 0.5s;
}

.hero-gradient {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        135deg,
        rgba(13,13,20,0.97) 0%,
        rgba(13,13,20,0.80) 50%,
        rgba(212,168,83,0.05) 100%
    );
}

.hero-grain {
    position: absolute;
    inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
    opacity: 0.4;
    pointer-events: none;
}

/* Floating gold orbs */
.orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    pointer-events: none;
}
.orb-1 { width: 400px; height: 400px; background: rgba(212,168,83,0.06); top: -100px; right: -80px; animation: floatOrb 12s ease-in-out infinite; }
.orb-2 { width: 300px; height: 300px; background: rgba(34,139,34,0.05); bottom: 0; left: 10%; animation: floatOrb 16s ease-in-out infinite reverse; }

@keyframes floatOrb {
    0%, 100% { transform: translate(0, 0); }
    50% { transform: translate(20px, -30px); }
}

/* Gold accent line */
.gold-line {
    width: 48px;
    height: 3px;
    background: linear-gradient(90deg, #D4A853, #E8C878);
    border-radius: 2px;
    margin-bottom: 1.5rem;
}

/* Hero badge */
.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 14px;
    background: rgba(212,168,83,0.1);
    border: 1px solid rgba(212,168,83,0.25);
    border-radius: 50px;
    color: #D4A853;
    font-size: 0.72rem;
    font-weight: 600;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    margin-bottom: 1.5rem;
    backdrop-filter: blur(8px);
}

/* Book card */
.book-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 24px rgba(0,0,0,0.06);
    border: 1px solid rgba(0,0,0,0.04);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
}
.dark .book-card {
    background: #1E1E2A;
    border-color: rgba(255,255,255,0.05);
    box-shadow: 0 4px 24px rgba(0,0,0,0.3);
}
.book-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 48px rgba(0,0,0,0.12);
}
.dark .book-card:hover {
    box-shadow: 0 16px 48px rgba(0,0,0,0.5);
}

.book-cover-wrap {
    position: relative;
    padding-bottom: 145%;
    overflow: hidden;
    background: #e8e4de;
}
.dark .book-cover-wrap {
    background: #2a2a38;
}
.book-cover-wrap img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}
.book-card:hover .book-cover-wrap img {
    transform: scale(1.06);
}
.book-cover-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.5) 0%, transparent 50%);
    opacity: 0;
    transition: opacity 0.3s;
    display: flex;
    align-items: flex-end;
    padding: 14px;
}
.book-card:hover .book-cover-overlay {
    opacity: 1;
}

/* Genre tag */
.genre-tag {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 0.65rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

/* Stats strip */
.stats-strip {
    background: #fff;
    border-bottom: 1px solid rgba(0,0,0,0.05);
}
.dark .stats-strip {
    background: #16161F;
    border-color: rgba(255,255,255,0.04);
}

/* Category card */
.cat-card {
    border-radius: 14px;
    padding: 24px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    border: 1px solid transparent;
    transition: all 0.25s ease;
    cursor: pointer;
    text-decoration: none;
}
.cat-card:hover {
    border-color: rgba(212,168,83,0.3);
    transform: translateY(-3px);
}

/* Section heading */
.section-eyebrow {
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 2.5px;
    text-transform: uppercase;
    color: #D4A853;
    margin-bottom: 0.5rem;
}

/* Scroll reveal */
.reveal {
    opacity: 0;
    transform: translateY(28px);
    transition: opacity 0.65s ease, transform 0.65s ease;
}
.reveal.visible {
    opacity: 1;
    transform: none;
}

/* Light mode hero book image */
.hero-book-float {
    position: relative;
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0) rotate(-2deg); }
    50% { transform: translateY(-14px) rotate(-2deg); }
}

/* CTA banner */
.cta-banner {
    position: relative;
    overflow: hidden;
    background: #0D0D14;
}
.cta-banner::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: url('https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=1400&q=70&fit=crop');
    background-size: cover;
    background-position: center;
    opacity: 0.12;
}
.cta-banner::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(13,13,20,0.9) 0%, rgba(34,139,34,0.1) 100%);
}
</style>
@endsection

@section('content')

@php
    $userRole = data_get(session('user'), 'role');
    $isLoggedIn = (bool) session('token');
    $isAdminUser = in_array($userRole, ['admin', 'super_admin', 'catalog_admin', 'orders_admin', 'inventory_admin', 'finance_admin', 'staff']);
@endphp

{{-- ===== HERO ===== --}}
<section class="hero-section">
    <div class="hero-bg"></div>
    <div class="hero-gradient"></div>
    <div class="hero-grain"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full py-20 lg:py-32">
        <div class="grid lg:grid-cols-2 gap-16 items-center">

            {{-- Left: Text --}}
            <div>
                <div class="hero-badge">
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-brand-gold animate-pulse"></span>
                    Premium Curated Bookstore
                </div>

                <div class="gold-line"></div>

                <h1 class="font-serif text-5xl lg:text-6xl xl:text-7xl font-bold text-white leading-[1.08] tracking-tight mb-6">
                    Stories that<br>
                    <span style="background: linear-gradient(135deg, #D4A853, #E8C878); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                        shape you.
                    </span>
                </h1>

                <p class="text-gray-400 text-lg leading-relaxed mb-10 max-w-lg font-light">
                    From timeless classics to debut novels — browse 10,000+ titles curated by readers, for readers.
                </p>

                <div class="flex flex-wrap gap-4">
                    <a href="/catalog" id="hero-browse-btn" class="inline-flex items-center gap-2.5 bg-brand-gold hover:bg-brand-gold-light text-brand-ink font-semibold px-7 py-3.5 rounded-xl transition-all duration-200 shadow-lg shadow-brand-gold/20 hover:shadow-brand-gold/40 hover:-translate-y-0.5">
                        <i class="fas fa-book-open text-sm"></i>
                        Browse Collection
                    </a>
                    @if($isLoggedIn)
                        <a href="/profile" id="hero-account-btn" class="inline-flex items-center gap-2.5 border border-white/15 hover:border-white/30 text-white/80 hover:text-white font-medium px-7 py-3.5 rounded-xl transition-all duration-200 hover:-translate-y-0.5 backdrop-blur-sm">
                            <i class="fas fa-user-circle text-sm"></i>
                            My Account
                        </a>
                    @else
                        <a href="/register" id="hero-signup-btn" class="inline-flex items-center gap-2.5 border border-white/15 hover:border-white/30 text-white/80 hover:text-white font-medium px-7 py-3.5 rounded-xl transition-all duration-200 hover:-translate-y-0.5 backdrop-blur-sm">
                            <i class="fas fa-user-plus text-sm"></i>
                            Create Account
                        </a>
                    @endif
                </div>

                {{-- Trust row --}}
                <div class="mt-12 flex items-center gap-6">
                    <div class="flex -space-x-2">
                        <img src="https://i.pravatar.cc/32?img=1" class="w-8 h-8 rounded-full border-2 border-brand-ink" alt="reader">
                        <img src="https://i.pravatar.cc/32?img=5" class="w-8 h-8 rounded-full border-2 border-brand-ink" alt="reader">
                        <img src="https://i.pravatar.cc/32?img=8" class="w-8 h-8 rounded-full border-2 border-brand-ink" alt="reader">
                        <img src="https://i.pravatar.cc/32?img=12" class="w-8 h-8 rounded-full border-2 border-brand-ink" alt="reader">
                    </div>
                    <div class="text-sm text-gray-400">
                        <span class="text-white font-semibold">5,000+</span> happy readers
                        <div class="flex mt-0.5">
                            <i class="fas fa-star text-brand-gold text-xs"></i>
                            <i class="fas fa-star text-brand-gold text-xs"></i>
                            <i class="fas fa-star text-brand-gold text-xs"></i>
                            <i class="fas fa-star text-brand-gold text-xs"></i>
                            <i class="fas fa-star text-brand-gold text-xs"></i>
                            <span class="text-gray-500 ml-1 text-xs">4.9/5</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: Floating Book Stack --}}
            <div class="hidden lg:flex justify-center items-center relative">
                <div class="relative w-80 h-96">
                    {{-- Back book --}}
                    <div class="absolute -right-8 top-8 w-52 h-72 rounded-lg shadow-2xl overflow-hidden opacity-60 rotate-6" style="transform: rotate(8deg) translateY(10px);">
                        <img src="https://covers.openlibrary.org/b/isbn/9780743273565-L.jpg" class="w-full h-full object-cover" alt="book" onerror="this.src='https://images.unsplash.com/photo-1589998059171-988d887df646?w=300&h=400&fit=crop'">
                    </div>
                    {{-- Middle book --}}
                    <div class="absolute left-4 top-4 w-52 h-72 rounded-lg shadow-2xl overflow-hidden opacity-80" style="transform: rotate(-4deg);">
                        <img src="https://covers.openlibrary.org/b/isbn/9780316769174-L.jpg" class="w-full h-full object-cover" alt="book" onerror="this.src='https://images.unsplash.com/photo-1532012197267-da84d127e765?w=300&h=400&fit=crop'">
                    </div>
                    {{-- Front book (floating) --}}
                    <div class="hero-book-float absolute left-12 top-0 w-56 h-76 rounded-xl shadow-2xl overflow-hidden" style="box-shadow: 0 30px 80px rgba(0,0,0,0.6), 0 0 0 1px rgba(255,255,255,0.05);">
                        <img src="https://covers.openlibrary.org/b/olid/OL61370540M-L.jpg" class="w-full h-full object-cover" alt="Shadow Slave" onerror="this.src='https://images.unsplash.com/photo-1621351183012-e2f9972dd9bf?w=300&h=430&fit=crop'">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                    </div>
                    {{-- Glow --}}
                    <div class="absolute inset-0 pointer-events-none" style="background: radial-gradient(circle at 60% 50%, rgba(212,168,83,0.12) 0%, transparent 70%);"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Scroll indicator --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 z-10 animate-bounce">
        <span class="text-gray-600 text-xs tracking-widest uppercase">Scroll</span>
        <i class="fas fa-chevron-down text-gray-600 text-xs"></i>
    </div>
</section>

{{-- ===== STATS STRIP ===== --}}
<div class="stats-strip">
    <div class="max-w-4xl mx-auto px-4 py-6 grid grid-cols-2 md:grid-cols-4 gap-0 divide-x divide-gray-100 dark:divide-white/5">
        <div class="text-center px-6 py-2">
            <div class="text-2xl font-serif font-bold text-gray-900 dark:text-white">10,000<span class="text-brand-gold">+</span></div>
            <div class="text-xs text-gray-500 mt-1 uppercase tracking-wider">Titles</div>
        </div>
        <div class="text-center px-6 py-2">
            <div class="text-2xl font-serif font-bold text-gray-900 dark:text-white">500<span class="text-brand-gold">+</span></div>
            <div class="text-xs text-gray-500 mt-1 uppercase tracking-wider">Authors</div>
        </div>
        <div class="text-center px-6 py-2">
            <div class="text-2xl font-serif font-bold text-gray-900 dark:text-white">5,000<span class="text-brand-gold">+</span></div>
            <div class="text-xs text-gray-500 mt-1 uppercase tracking-wider">Readers</div>
        </div>
        <div class="text-center px-6 py-2">
            <div class="text-2xl font-serif font-bold text-gray-900 dark:text-white">4.9<span class="text-brand-gold">★</span></div>
            <div class="text-xs text-gray-500 mt-1 uppercase tracking-wider">Rating</div>
        </div>
    </div>
</div>

{{-- ===== FEATURED BOOKS ===== --}}
<section class="py-20 px-4 bg-brand-cream dark:bg-brand-ink-2">
    <div class="max-w-7xl mx-auto">
        <div class="reveal flex justify-between items-end mb-12">
            <div>
                <p class="section-eyebrow">Hand-picked</p>
                <h2 class="font-serif text-3xl md:text-4xl font-bold text-gray-900 dark:text-white tracking-tight">Featured Reads</h2>
            </div>
            <a href="/catalog" class="text-sm text-brand-gold hover:text-brand-gold-light font-medium transition-colors flex items-center gap-1.5">
                View all <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>

        @php
        $bookCovers = [
            1 => 'https://covers.openlibrary.org/b/isbn/9780747532699-L.jpg', // Harry Potter
            2 => 'https://covers.openlibrary.org/b/isbn/9780451524935-L.jpg', // 1984
            3 => 'https://covers.openlibrary.org/b/isbn/9780132350884-L.jpg', // Clean Code
            4 => 'https://covers.openlibrary.org/b/isbn/9780062316097-L.jpg', // Sapiens
            5 => 'https://covers.openlibrary.org/b/isbn/9780394800011-L.jpg', // Cat in Hat
            6 => 'https://covers.openlibrary.org/b/isbn/9781569319017-L.jpg', // One Piece
            7 => 'https://covers.openlibrary.org/b/isbn/9780451526342-L.jpg', // Animal Farm
            8 => 'https://covers.openlibrary.org/b/isbn/9780137081073-L.jpg', // Clean Coder
        ];
        $genreColors = [
            'Fiction'    => 'bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-300',
            'Non-Fiction'=> 'bg-sky-100 text-sky-700 dark:bg-sky-900/30 dark:text-sky-300',
            'Science & Technology' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300',
            'Children & Young Adult' => 'bg-pink-100 text-pink-700 dark:bg-pink-900/30 dark:text-pink-300',
            'Comics & Manga' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300',
        ];
        @endphp

        @if(empty($featuredBooks))
            <div class="text-center py-16">
                <div class="w-16 h-16 bg-gray-100 dark:bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-book-open text-gray-400 text-2xl"></i>
                </div>
                <p class="text-gray-500 dark:text-gray-400">No featured books available right now. <a href="/catalog" class="text-brand-gold hover:underline">Browse the catalog →</a></p>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5 lg:gap-7">
                @foreach($featuredBooks as $i => $book)
                @php
                    $coverUrl = $bookCovers[$book['id']] ?? 'https://images.unsplash.com/photo-1589998059171-988d887df646?w=300&h=450&fit=crop';
                    $categoryName = $book['category']['name'] ?? 'Fiction';
                    $genreClass = $genreColors[$categoryName] ?? 'bg-gray-100 text-gray-600 dark:bg-white/5 dark:text-gray-300';
                @endphp
                <div class="reveal book-card" style="transition-delay: {{ $i * 80 }}ms">
                    <div class="book-cover-wrap">
                        <img
                            src="{{ $coverUrl }}"
                            alt="{{ $book['title'] }}"
                            onerror="this.src='https://images.unsplash.com/photo-1621351183012-e2f9972dd9bf?w=300&h=430&fit=crop&q=80'"
                            loading="lazy"
                        >
                        <div class="book-cover-overlay">
                            <a href="/catalog/{{ $book['id'] }}" class="w-full text-center text-white text-xs font-semibold py-2 px-3 rounded-lg bg-white/15 backdrop-blur-sm hover:bg-white/25 transition-all">
                                View Details
                            </a>
                        </div>
                    </div>
                    <div class="p-4">
                        <span class="genre-tag {{ $genreClass }} mb-2 inline-block">{{ $categoryName }}</span>
                        <h3 class="font-semibold text-gray-900 dark:text-white text-sm leading-snug line-clamp-2 mb-1">
                            <a href="/catalog/{{ $book['id'] }}" class="hover:text-brand-gold dark:hover:text-brand-gold transition-colors">{{ $book['title'] }}</a>
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">{{ $book['author']['name'] ?? 'Unknown' }}</p>
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-gray-900 dark:text-white text-sm">${{ number_format($book['price'] ?? 0, 2) }}</span>
                            <a href="/cart?add={{ $book['id'] }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-brand-gold/10 hover:bg-brand-gold text-brand-gold hover:text-brand-ink transition-all duration-200">
                                <i class="fas fa-plus text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

{{-- ===== CATEGORIES GRID ===== --}}
<section class="py-20 px-4 bg-white dark:bg-brand-ink border-t border-gray-100 dark:border-white/5">
    <div class="max-w-7xl mx-auto">
        <div class="reveal text-center mb-14">
            <p class="section-eyebrow">Browse by</p>
            <h2 class="font-serif text-3xl md:text-4xl font-bold text-gray-900 dark:text-white tracking-tight">Categories</h2>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            @php
            $categories = [
                ['name' => 'Fiction', 'icon' => 'fas fa-feather-alt', 'desc' => 'Novels & literary fiction', 'bg' => 'bg-violet-50 dark:bg-violet-900/20', 'icon_color' => 'text-violet-600 dark:text-violet-400', 'icon_bg' => 'bg-violet-100 dark:bg-violet-900/30'],
                ['name' => 'Non-Fiction', 'icon' => 'fas fa-landmark', 'desc' => 'History, science & self-help', 'bg' => 'bg-sky-50 dark:bg-sky-900/20', 'icon_color' => 'text-sky-600 dark:text-sky-400', 'icon_bg' => 'bg-sky-100 dark:bg-sky-900/30'],
                ['name' => 'Science & Tech', 'icon' => 'fas fa-laptop-code', 'desc' => 'Engineering & computing', 'bg' => 'bg-emerald-50 dark:bg-emerald-900/20', 'icon_color' => 'text-emerald-600 dark:text-emerald-400', 'icon_bg' => 'bg-emerald-100 dark:bg-emerald-900/30'],
                ['name' => 'Children', 'icon' => 'fas fa-child', 'desc' => 'Books for all ages', 'bg' => 'bg-pink-50 dark:bg-pink-900/20', 'icon_color' => 'text-pink-600 dark:text-pink-400', 'icon_bg' => 'bg-pink-100 dark:bg-pink-900/30'],
                ['name' => 'Comics & Manga', 'icon' => 'fas fa-dragon', 'desc' => 'Graphic novels & manga', 'bg' => 'bg-orange-50 dark:bg-orange-900/20', 'icon_color' => 'text-orange-600 dark:text-orange-400', 'icon_bg' => 'bg-orange-100 dark:bg-orange-900/30'],
                ['name' => 'All Books', 'icon' => 'fas fa-layer-group', 'desc' => 'See the full collection', 'bg' => 'bg-gray-50 dark:bg-white/5', 'icon_color' => 'text-gray-700 dark:text-gray-300', 'icon_bg' => 'bg-gray-100 dark:bg-white/10'],
            ];
            @endphp

            @foreach($categories as $i => $cat)
            <a href="/catalog" class="cat-card {{ $cat['bg'] }} reveal" style="transition-delay: {{ $i * 60 }}ms">
                <div class="w-11 h-11 rounded-xl {{ $cat['icon_bg'] }} flex items-center justify-center flex-shrink-0">
                    <i class="{{ $cat['icon'] }} {{ $cat['icon_color'] }} text-lg"></i>
                </div>
                <div>
                    <div class="font-semibold text-gray-900 dark:text-white text-sm">{{ $cat['name'] }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $cat['desc'] }}</div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== EDITORIAL BANNER — Why PageTurn ===== --}}
<section class="py-20 px-4 bg-brand-cream dark:bg-brand-ink-2 border-t border-gray-100 dark:border-white/5">
    <div class="max-w-7xl mx-auto">
        <div class="reveal text-center mb-14">
            <p class="section-eyebrow">Why us</p>
            <h2 class="font-serif text-3xl md:text-4xl font-bold text-gray-900 dark:text-white tracking-tight">Built for readers</h2>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            @php
            $features = [
                ['icon' => 'fas fa-search', 'color' => 'text-brand-gold', 'bg' => 'bg-brand-gold/10', 'title' => 'Smart Discovery', 'desc' => 'Advanced filters, genre tags, author search. Find exactly what you want — or discover something unexpected.'],
                ['icon' => 'fas fa-shield-alt', 'color' => 'text-emerald-500', 'bg' => 'bg-emerald-50 dark:bg-emerald-900/20', 'title' => 'Secure Checkout', 'desc' => 'Token-based auth, encrypted payments, and instant order confirmation. Shop with complete confidence.'],
                ['icon' => 'fas fa-truck-fast', 'color' => 'text-sky-500', 'bg' => 'bg-sky-50 dark:bg-sky-900/20', 'title' => 'Order Tracking', 'desc' => 'Real-time updates from confirmation to delivery. You\'ll always know exactly where your books are.'],
            ];
            @endphp

            @foreach($features as $i => $f)
            <div class="reveal bg-white dark:bg-brand-ink-3 rounded-2xl p-8 border border-gray-100 dark:border-white/5 hover:border-brand-gold/20 dark:hover:border-brand-gold/20 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl dark:hover:shadow-black/30" style="transition-delay: {{ $i * 100 }}ms">
                <div class="w-12 h-12 {{ $f['bg'] }} rounded-xl flex items-center justify-center mb-5">
                    <i class="{{ $f['icon'] }} {{ $f['color'] }} text-xl"></i>
                </div>
                <h3 class="font-serif text-lg font-bold text-gray-900 dark:text-white mb-2">{{ $f['title'] }}</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">{{ $f['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== CTA BANNER ===== --}}
<section class="cta-banner py-24 px-4">
    <div class="relative z-10 max-w-3xl mx-auto text-center">
        <p class="section-eyebrow mb-3">Limited time</p>
        <h2 class="font-serif text-4xl md:text-5xl font-bold text-white mb-4 tracking-tight">
            Start reading<br><span style="background: linear-gradient(135deg, #D4A853, #E8C878); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">for free today.</span>
        </h2>
        <p class="text-gray-400 text-lg mb-10 font-light">Create your account, browse 10,000+ titles and place your first order in minutes.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            @if($isLoggedIn)
                <a href="/profile" id="cta-account-btn" class="inline-flex items-center justify-center gap-2 bg-brand-gold hover:bg-brand-gold-light text-brand-ink font-bold px-8 py-4 rounded-xl transition-all duration-200 shadow-xl shadow-brand-gold/20 hover:-translate-y-0.5">
                    <i class="fas fa-user-circle"></i>
                    View My Account
                </a>
            @else
                <a href="/register" id="cta-register-btn" class="inline-flex items-center justify-center gap-2 bg-brand-gold hover:bg-brand-gold-light text-brand-ink font-bold px-8 py-4 rounded-xl transition-all duration-200 shadow-xl shadow-brand-gold/20 hover:-translate-y-0.5">
                    <i class="fas fa-user-plus text-sm"></i>
                    Create Free Account
                </a>
            @endif
            <a href="/catalog" class="inline-flex items-center justify-center gap-2 border border-white/15 hover:border-white/30 text-white/80 hover:text-white font-medium px-8 py-4 rounded-xl transition-all duration-200 hover:-translate-y-0.5 backdrop-blur-sm">
                Browse Collection
            </a>
        </div>
    </div>
</section>

{{-- ===== NEWSLETTER ===== --}}
<section class="py-16 px-4 bg-white dark:bg-brand-ink border-t border-gray-100 dark:border-white/5">
    <div class="max-w-xl mx-auto text-center">
        <div class="w-10 h-10 bg-brand-gold/10 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-envelope text-brand-gold text-sm"></i>
        </div>
        <h3 class="font-serif text-2xl font-bold text-gray-900 dark:text-white mb-2">Join the reading list</h3>
        <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">New arrivals, curated picks, and reading inspiration. Unsubscribe anytime.</p>
        <form id="newsletter-form" class="flex gap-2" onsubmit="handleNewsletter(event)">
            <input
                id="newsletter-email"
                type="email"
                placeholder="your@email.com"
                required
                class="flex-1 border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:border-brand-gold focus:ring-1 focus:ring-brand-gold transition-colors"
            >
            <button type="submit" class="bg-brand-gold hover:bg-brand-gold-light text-brand-ink font-semibold px-5 py-3 rounded-xl text-sm transition-all duration-200 whitespace-nowrap">
                Subscribe
            </button>
        </form>
    </div>
</section>

@endsection

@section('scripts')
<script>
// Scroll reveal
const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            revealObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

// Newsletter
function handleNewsletter(e) {
    e.preventDefault();
    const email = document.getElementById('newsletter-email').value;
    if (window.showToast) {
        window.showToast(`🎉 You're on the list! Welcome, ${email.split('@')[0]}.`, 'success');
    }
    document.getElementById('newsletter-form').reset();
}
</script>
@endsection

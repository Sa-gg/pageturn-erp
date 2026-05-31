@php
    $brandHref = $href ?? url('/');
    $brandName = $name ?? 'PageTurn';
    $brandSubline = $subline ?? 'Curated Reads';
    $brandClass = $class ?? '';
    $compact = $compact ?? false;
    $variant = $variant ?? 'dark';
    $wordmarkClass = $variant === 'light' ? 'text-gray-900 dark:text-white' : 'text-white dark:text-white';
    $sublineClass = $variant === 'light' ? 'text-brand-gold/90' : 'text-brand-gold/85';
@endphp

<a href="{{ $brandHref }}" class="inline-flex items-center gap-3 group {{ $brandClass }}">
    <span class="relative flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-forest via-brand-ink to-brand-gold shadow-lg shadow-brand-forest/20 ring-1 ring-white/10 transition-transform duration-300 group-hover:-rotate-6 group-hover:scale-105">
        <span class="absolute inset-0 rounded-2xl bg-white/5"></span>
        <svg class="relative h-6 w-6 text-white" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M5 6.5C5 5.12 6.12 4 7.5 4H12v16H7.5A2.5 2.5 0 0 1 5 17.5v-11Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
            <path d="M19 6.5C19 5.12 17.88 4 16.5 4H12v16h4.5A2.5 2.5 0 0 0 19 17.5v-11Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
            <path d="M12 6v14" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M8.4 8.25h2.2M8.4 11h2.2M13.4 8.25h2.2M13.4 11h2.2" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
        </svg>
    </span>

    <span class="flex flex-col leading-none">
        <span class="text-[0.68rem] font-semibold uppercase tracking-[0.34em] {{ $sublineClass }}">{{ $brandSubline }}</span>
        <span class="mt-1 font-display text-xl font-extrabold tracking-tight {{ $wordmarkClass }}">
            {{ $brandName }}<span class="text-brand-gold">.</span>
        </span>
    </span>
</a>
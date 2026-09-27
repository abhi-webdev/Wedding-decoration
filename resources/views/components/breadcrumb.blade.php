@props(['items' => []])

@php
    // Defensively filter out any item that is 'Home' to prevent duplicate Home entries
    $filteredItems = collect($items)->filter(function($item) {
        if (!is_array($item)) return false;
        $label = trim(strtolower($item['label'] ?? ''));
        return !empty($label) && $label !== 'home';
    })->values()->all();
@endphp

<nav class="bg-brand-offwhite/90 border-b border-brand-light-border/80 py-2.5 sm:py-3 px-4 sm:px-6 lg:px-8 overflow-hidden" aria-label="Breadcrumb">
    <div class="max-w-7xl mx-auto flex items-center flex-wrap gap-1.5 sm:gap-2 text-xs text-brand-muted-brown">
        <a href="{{ route('home') }}" class="hover:text-brand-burgundy transition-colors flex items-center gap-1.5 font-medium shrink-0">
            <i class="fas fa-home text-[11px] text-brand-gold"></i>
            <span>Home</span>
        </a>
        
        @foreach($filteredItems as $item)
            <span class="text-brand-light-border font-bold shrink-0">/</span>
            @if(!empty($item['url']) && !$loop->last)
                <a href="{{ $item['url'] }}" class="hover:text-brand-burgundy transition-colors font-medium shrink-0">
                    {{ $item['label'] }}
                </a>
            @else
                <span class="text-brand-burgundy font-bold truncate max-w-[200px] sm:max-w-none" aria-current="page">
                    {{ $item['label'] }}
                </span>
            @endif
        @endforeach
    </div>
</nav>

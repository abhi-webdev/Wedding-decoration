@props(['items' => []])

<nav class="bg-brand-offwhite/80 border-b border-brand-light-border py-3.5 px-4 sm:px-6 lg:px-8" aria-label="Breadcrumb">
    <div class="max-w-7xl mx-auto flex items-center flex-wrap gap-2 text-xs text-brand-muted-brown">
        <a href="{{ route('home') }}" class="hover:text-brand-burgundy transition-colors flex items-center gap-1.5 font-medium">
            <i class="fas fa-home text-[11px] text-brand-gold"></i>
            <span>Home</span>
        </a>
        
        @foreach($items as $item)
            <span class="text-brand-light-border font-bold">/</span>
            @if(!empty($item['url']) && !$loop->last)
                <a href="{{ $item['url'] }}" class="hover:text-brand-burgundy transition-colors font-medium">
                    {{ $item['label'] }}
                </a>
            @else
                <span class="text-brand-burgundy font-bold truncate max-w-xs sm:max-w-md" aria-current="page">
                    {{ $item['label'] }}
                </span>
            @endif
        @endforeach
    </div>
</nav>

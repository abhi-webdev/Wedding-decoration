@props([
    'eyebrow' => null,
    'title' => '',
    'subtitle' => null,
    'align' => 'center', // 'center' or 'left'
    'dark' => false
])

<div class="mb-8 sm:mb-12 {{ $align === 'center' ? 'text-center max-w-3xl mx-auto' : 'text-left max-w-2xl' }}">
    @if($eyebrow)
        <span class="inline-flex items-center gap-1.5 sm:gap-2 text-[10px] sm:text-xs font-bold uppercase tracking-[0.15em] sm:tracking-[0.2em] {{ $dark ? 'text-brand-gold' : 'text-brand-burgundy' }} mb-1.5 sm:mb-2">
            <span class="w-4 sm:w-6 h-[1.5px] bg-brand-gold"></span>
            {{ $eyebrow }}
            <span class="w-4 sm:w-6 h-[1.5px] bg-brand-gold"></span>
        </span>
    @endif

    <h2 class="font-serif text-2xl sm:text-4xl lg:text-5xl font-bold {{ $dark ? 'text-brand-cream' : 'text-brand-charcoal' }} tracking-tight leading-tight">
        {{ $title }}
    </h2>

    @if($subtitle)
        <p class="mt-2 sm:mt-3 text-xs sm:text-sm lg:text-base {{ $dark ? 'text-brand-cream/80' : 'text-brand-muted-brown' }} leading-relaxed">
            {{ $subtitle }}
        </p>
    @endif
</div>

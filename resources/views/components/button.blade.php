@props([
    'variant' => 'primary', // 'primary', 'secondary', 'outline', 'gold'
    'size' => 'md', // 'sm', 'md', 'lg'
    'href' => null,
    'type' => 'button',
    'icon' => null,
    'iconPosition' => 'left'
])

@php
    $baseClasses = "inline-flex items-center justify-center font-semibold rounded transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2";
    
    $sizeClasses = [
        'sm' => 'px-3.5 py-1.5 text-xs',
        'md' => 'px-5 py-2.5 text-xs sm:text-sm uppercase tracking-wider',
        'lg' => 'px-7 py-3.5 text-sm sm:text-base font-bold uppercase tracking-wider',
    ][$size] ?? 'px-5 py-2.5 text-xs sm:text-sm uppercase tracking-wider';

    $variantClasses = [
        'primary' => 'bg-brand-burgundy text-white hover:bg-brand-deep-burgundy border border-brand-gold shadow-sm hover:shadow-gold-glow focus:ring-brand-burgundy',
        'secondary' => 'bg-white text-brand-charcoal hover:bg-brand-offwhite border border-brand-light-border shadow-sm focus:ring-brand-gold',
        'gold' => 'bg-gradient-to-r from-brand-gold-dark via-brand-gold to-brand-gold-light text-brand-deep-burgundy font-bold hover:opacity-95 shadow-md focus:ring-brand-gold',
        'outline' => 'bg-transparent text-brand-burgundy border border-brand-burgundy hover:bg-brand-burgundy hover:text-white focus:ring-brand-burgundy',
        'outline-white' => 'bg-transparent text-white border border-brand-gold hover:bg-brand-gold hover:text-brand-deep-burgundy focus:ring-brand-gold',
    ][$variant] ?? 'bg-brand-burgundy text-white hover:bg-brand-deep-burgundy border border-brand-gold';

    $classes = "{$baseClasses} {$sizeClasses} {$variantClasses}";
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon && $iconPosition === 'left')
            <i class="{{ $icon }} mr-2"></i>
        @endif
        {{ $slot }}
        @if($icon && $iconPosition === 'right')
            <i class="{{ $icon }} ml-2"></i>
        @endif
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon && $iconPosition === 'left')
            <i class="{{ $icon }} mr-2"></i>
        @endif
        {{ $slot }}
        @if($icon && $iconPosition === 'right')
            <i class="{{ $icon }} ml-2"></i>
        @endif
    </button>
@endif

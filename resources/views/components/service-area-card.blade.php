@props(['area'])

<div class="bg-white rounded-2xl p-5 border border-brand-light-border hover:border-brand-gold/60 shadow-soft-luxury hover:shadow-xl transition-all flex flex-col justify-between space-y-4">
    <div class="space-y-2">
        <div class="flex items-center justify-between">
            <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full {{ $area->state === 'Bihar' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }}">
                {{ $area->state }}
            </span>
            @if($area->is_primary)
                <span class="text-[10px] font-bold text-brand-gold bg-brand-burgundy px-2 py-0.5 rounded">
                    Primary Hub
                </span>
            @endif
        </div>

        <h3 class="font-serif text-lg font-bold text-brand-charcoal">
            {{ $area->name }}
        </h3>

        @if($area->district)
            <p class="text-[11px] text-brand-muted-brown">
                District: <strong>{{ $area->district }}</strong>, {{ $area->state }}
            </p>
        @endif

        <p class="text-xs text-brand-muted-brown leading-relaxed">
            {{ $area->description ?: 'Full wedding decoration, Mandap setup, stage lighting, and on-site crew dispatch.' }}
        </p>
    </div>

    <div class="pt-3 border-t border-brand-light-border flex items-center justify-between text-xs">
        <span class="text-emerald-700 font-semibold flex items-center gap-1.5 text-[11px]">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            {{ $area->status_note ?: 'Active Coverage' }}
        </span>
        <a href="{{ route('quote', ['city' => $area->name, 'state' => $area->state]) }}" class="font-bold text-brand-burgundy hover:underline">
            Book in {{ $area->name }} &rarr;
        </a>
    </div>
</div>

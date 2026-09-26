<!-- Top Announcement Bar -->
<div class="bg-brand-deep-burgundy text-brand-cream text-xs py-1.5 sm:py-2 px-3 sm:px-4 border-b border-brand-royal-rose/30 w-full overflow-hidden">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-1.5 sm:gap-2 text-center sm:text-left">
        <div class="flex items-center justify-center sm:justify-start gap-2 text-center sm:text-left">
            <span class="inline-block w-2 h-2 rounded-full bg-brand-gold animate-pulse shrink-0"></span>
            <span class="font-medium tracking-wide text-[11px] sm:text-xs">
                {{ $settings['announcement_text'] ?? 'Wedding Season Bookings Open — Serving Siwan, Gopalganj, Chapra & Nearby Areas' }}
            </span>
        </div>
        <div class="flex items-center justify-center gap-3 sm:gap-4 text-[10px] sm:text-xs">
            <a href="tel:{{ $settings['contact_phone'] ?? '+919876543210' }}" class="flex items-center gap-1.5 text-brand-gold-light hover:text-white transition-colors whitespace-nowrap">
                <i class="fas fa-phone-alt text-[9px] sm:text-[10px]"></i>
                <span class="font-medium">Call Us: {{ $settings['contact_phone'] ?? '+91 98765 43210' }}</span>
            </a>
            <span class="text-brand-royal-rose/60">|</span>
            <span class="text-brand-cream/80 flex items-center gap-1 whitespace-nowrap">
                <i class="fas fa-map-marker-alt text-brand-gold text-[9px] sm:text-[10px]"></i>
                Siwan, Bihar
            </span>
        </div>
    </div>
</div>

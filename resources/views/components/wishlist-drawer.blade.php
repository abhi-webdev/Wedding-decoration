<!-- Wishlist Drawer Backdrop & Container -->
<div id="wishlist-backdrop" class="hidden fixed inset-0 bg-brand-charcoal/70 backdrop-blur-sm z-50 transition-opacity" onclick="toggleWishlistDrawer(false)"></div>

<div id="wishlist-drawer" class="fixed top-0 right-0 bottom-0 w-full max-w-md bg-brand-cream border-l-2 border-brand-gold z-50 shadow-2xl transform translate-x-full transition-transform duration-300 flex flex-col justify-between pointer-events-none invisible" role="dialog" aria-modal="true" aria-label="Shortlisted Decorations">
    
    <!-- Drawer Header -->
    <div class="p-5 bg-brand-deep-burgundy text-brand-cream flex items-center justify-between border-b border-brand-royal-rose">
        <div class="flex items-center gap-2.5">
            <i class="fas fa-heart text-brand-gold"></i>
            <h3 class="font-serif text-lg font-bold text-white">
                Shortlisted Decorations
            </h3>
            <span class="wishlist-count-badge bg-brand-gold text-brand-deep-burgundy text-xs font-bold px-2 py-0.5 rounded-full ml-1">
                0
            </span>
        </div>
        <button type="button" onclick="toggleWishlistDrawer(false)" class="text-brand-cream/80 hover:text-white p-1 rounded-md" aria-label="Close drawer">
            <i class="fas fa-times text-lg"></i>
        </button>
    </div>

    <!-- Drawer Content / Items List -->
    <div class="p-5 flex-1 overflow-y-auto space-y-3">
        <div id="wishlist-empty-msg" class="text-center py-16 text-brand-muted-brown space-y-3">
            <div class="w-16 h-16 mx-auto rounded-full bg-brand-burgundy/10 flex items-center justify-center text-brand-burgundy text-2xl">
                <i class="far fa-heart"></i>
            </div>
            <h4 class="font-serif text-lg font-bold text-brand-charcoal">No decorations shortlisted yet</h4>
            <p class="text-xs max-w-xs mx-auto leading-relaxed">
                Click the heart icon on any Jaimala stage, Mandap, or Haldi decoration to save your favorite designs here.
            </p>
            <div class="pt-2">
                <a href="{{ route('decorations.index') }}" onclick="toggleWishlistDrawer(false)" class="inline-block px-4 py-2 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy rounded hover:bg-brand-deep-burgundy transition-colors">
                    Browse All Designs
                </a>
            </div>
        </div>

        <div id="wishlist-items-list" class="space-y-3">
            <!-- Dynamically populated via Vanilla JS -->
        </div>
    </div>

    <!-- Drawer Footer Actions -->
    <div class="p-5 bg-white border-t border-brand-light-border space-y-2.5">
        <button type="button" onclick="toggleWishlistDrawer(false); openAvailabilityModal()" class="w-full py-3 px-4 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded border border-brand-gold shadow transition-all">
            <i class="fas fa-calendar-check mr-2 text-brand-gold"></i>
            Check Date For Shortlisted Designs
        </button>
        <a href="{{ route('quote') }}" onclick="toggleWishlistDrawer(false)" class="block w-full text-center py-2.5 px-4 text-xs font-semibold text-brand-charcoal bg-brand-offwhite hover:bg-brand-light-border/40 rounded border border-brand-light-border transition-colors">
            Request Quote For All Shortlisted
        </a>
    </div>
</div>

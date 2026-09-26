@props([
    'categories' => [],
    'selectedCategory' => null,
    'styles' => [],
    'colors' => [],
    'guestCapacities' => [],
])

@php
    $currentCategory = request('category', ($selectedCategory ? $selectedCategory->slug : 'all'));
    $currentPriceRange = request('price_range', '');
    $currentLocation = request('location', 'all');
    $currentStyle = request('style', 'all');
    $currentColor = request('color', 'all');
    $currentCapacity = request('guest_capacity', 'all');
    $currentSort = request('sort', 'featured');
    $currentSearch = request('search', '');
@endphp

<!-- Mobile Filter Backdrop & Modal Container -->
<div id="mobile-filter-backdrop" class="hidden fixed inset-0 bg-brand-charcoal/70 backdrop-blur-sm z-50 transition-opacity" onclick="toggleMobileFilter(false)"></div>

<div id="mobile-filter-drawer" class="fixed top-0 right-0 bottom-0 w-full max-w-sm bg-white z-50 shadow-2xl transform translate-x-full transition-transform duration-300 flex flex-col justify-between overflow-hidden" role="dialog" aria-modal="true" aria-label="Filter Decorations">
    
    <!-- Drawer Header -->
    <div class="p-4 bg-brand-deep-burgundy text-white flex items-center justify-between border-b border-brand-royal-rose">
        <div class="flex items-center gap-2">
            <i class="fas fa-sliders-h text-brand-gold"></i>
            <h3 class="font-serif text-lg font-bold">Filter Decorations</h3>
        </div>
        <button type="button" onclick="toggleMobileFilter(false)" class="text-brand-cream/80 hover:text-white p-1 rounded-md" aria-label="Close filter menu">
            <i class="fas fa-times text-lg"></i>
        </button>
    </div>

    <!-- Form Content -->
    <div class="p-5 flex-1 overflow-y-auto space-y-6">
        <form action="{{ route('decorations.index') }}" method="GET" id="mobile-filter-form" class="space-y-6">
            <input type="hidden" name="sort" value="{{ $currentSort }}">

            <!-- 1. Search Input -->
            <div>
                <label for="mobile-search-input" class="block text-xs font-bold uppercase text-brand-charcoal mb-1.5">Search Keyword</label>
                <input 
                    type="text" 
                    name="search" 
                    id="mobile-search-input"
                    value="{{ $currentSearch }}" 
                    placeholder="Search Jaimala, Mandap, Haldi..." 
                    class="w-full px-3 py-2 text-sm bg-brand-offwhite border border-brand-light-border rounded-lg focus:outline-none focus:border-brand-burgundy"
                >
            </div>

            <!-- 2. Ceremony / Category -->
            <div>
                <label for="mobile-category-select" class="block text-xs font-bold uppercase text-brand-charcoal mb-1.5">Ceremony Category</label>
                <select name="category" id="mobile-category-select" class="w-full px-3 py-2 text-sm bg-brand-offwhite border border-brand-light-border rounded-lg focus:outline-none focus:border-brand-burgundy">
                    <option value="all" {{ $currentCategory === 'all' || empty($currentCategory) ? 'selected' : '' }}>All Ceremonies</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}" {{ $currentCategory == $cat->slug || $currentCategory == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }} ({{ $cat->decorations_count }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- 3. Price Budget -->
            <div>
                <label for="mobile-price-select" class="block text-xs font-bold uppercase text-brand-charcoal mb-1.5">Price Budget</label>
                <select name="price_range" id="mobile-price-select" class="w-full px-3 py-2 text-sm bg-brand-offwhite border border-brand-light-border rounded-lg focus:outline-none focus:border-brand-burgundy">
                    <option value="" {{ empty($currentPriceRange) ? 'selected' : '' }}>Any Price</option>
                    <option value="under_50k" {{ $currentPriceRange === 'under_50k' ? 'selected' : '' }}>Under ₹50,000</option>
                    <option value="50k_80k" {{ $currentPriceRange === '50k_80k' ? 'selected' : '' }}>₹50,000 – ₹80,000</option>
                    <option value="80k_120k" {{ $currentPriceRange === '80k_120k' ? 'selected' : '' }}>₹80,000 – ₹1,20,000</option>
                    <option value="above_120k" {{ $currentPriceRange === 'above_120k' ? 'selected' : '' }}>₹1,20,000 &amp; Above</option>
                </select>
            </div>

            <!-- 4. Location -->
            <div>
                <label for="mobile-location-select" class="block text-xs font-bold uppercase text-brand-charcoal mb-1.5">Location</label>
                <select name="location" id="mobile-location-select" class="w-full px-3 py-2 text-sm bg-brand-offwhite border border-brand-light-border rounded-lg focus:outline-none focus:border-brand-burgundy">
                    <option value="all" {{ $currentLocation === 'all' ? 'selected' : '' }}>All Locations</option>
                    <optgroup label="Bihar (Primary Service)">
                        <option value="Siwan" {{ $currentLocation === 'Siwan' ? 'selected' : '' }}>Siwan</option>
                        <option value="Mairwa" {{ $currentLocation === 'Mairwa' ? 'selected' : '' }}>Mairwa</option>
                        <option value="Gopalganj" {{ $currentLocation === 'Gopalganj' ? 'selected' : '' }}>Gopalganj</option>
                        <option value="Chapra / Saran" {{ $currentLocation === 'Chapra / Saran' || $currentLocation === 'Chapra' ? 'selected' : '' }}>Chapra / Saran</option>
                        <option value="bihar" {{ $currentLocation === 'bihar' ? 'selected' : '' }}>All Bihar Locations</option>
                    </optgroup>
                    <optgroup label="Nearby Uttar Pradesh">
                        <option value="Gorakhpur" {{ $currentLocation === 'Gorakhpur' ? 'selected' : '' }}>Gorakhpur (UP)</option>
                        <option value="Deoria" {{ $currentLocation === 'Deoria' ? 'selected' : '' }}>Deoria (UP)</option>
                        <option value="up" {{ $currentLocation === 'up' ? 'selected' : '' }}>All UP Border Areas</option>
                    </optgroup>
                </select>
            </div>

            <!-- 5. Style -->
            <div>
                <label for="mobile-style-select" class="block text-xs font-bold uppercase text-brand-charcoal mb-1.5">Decoration Style</label>
                <select name="style" id="mobile-style-select" class="w-full px-3 py-2 text-sm bg-brand-offwhite border border-brand-light-border rounded-lg focus:outline-none focus:border-brand-burgundy">
                    <option value="all" {{ $currentStyle === 'all' ? 'selected' : '' }}>All Styles</option>
                    @foreach($styles as $st)
                        <option value="{{ $st }}" {{ $currentStyle === $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
            </div>

            <!-- 6. Color -->
            <div>
                <label for="mobile-color-select" class="block text-xs font-bold uppercase text-brand-charcoal mb-1.5">Color Theme</label>
                <select name="color" id="mobile-color-select" class="w-full px-3 py-2 text-sm bg-brand-offwhite border border-brand-light-border rounded-lg focus:outline-none focus:border-brand-burgundy">
                    <option value="all" {{ $currentColor === 'all' ? 'selected' : '' }}>All Colors</option>
                    @foreach($colors as $cl)
                        <option value="{{ $cl }}" {{ $currentColor === $cl ? 'selected' : '' }}>{{ $cl }}</option>
                    @endforeach
                </select>
            </div>

            <!-- 7. Guest Capacity -->
            <div>
                <label for="mobile-capacity-select" class="block text-xs font-bold uppercase text-brand-charcoal mb-1.5">Guest Capacity</label>
                <select name="guest_capacity" id="mobile-capacity-select" class="w-full px-3 py-2 text-sm bg-brand-offwhite border border-brand-light-border rounded-lg focus:outline-none focus:border-brand-burgundy">
                    <option value="all" {{ $currentCapacity === 'all' ? 'selected' : '' }}>Any Capacity</option>
                    @foreach($guestCapacities as $cap)
                        <option value="{{ $cap }}" {{ $currentCapacity === $cap ? 'selected' : '' }}>{{ $cap }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 space-y-2">
                <button type="submit" class="w-full py-3 px-4 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-lg border border-brand-gold shadow">
                    Apply Filters
                </button>
                <a href="{{ route('decorations.index') }}" class="block w-full text-center py-2.5 px-4 text-xs font-semibold text-brand-charcoal bg-brand-offwhite rounded-lg border border-brand-light-border">
                    Clear All Filters
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleMobileFilter(open = true) {
        const drawer = document.getElementById('mobile-filter-drawer');
        const backdrop = document.getElementById('mobile-filter-backdrop');
        if (!drawer || !backdrop) return;

        if (open) {
            drawer.classList.remove('translate-x-full');
            backdrop.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        } else {
            drawer.classList.add('translate-x-full');
            backdrop.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }
</script>

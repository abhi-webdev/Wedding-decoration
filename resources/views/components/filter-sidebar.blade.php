@props([
    'categories' => [],
    'selectedCategory' => null,
    'styles' => [],
    'colors' => [],
    'guestCapacities' => [],
    'biharLocations' => [],
    'upLocations' => [],
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

<div class="bg-white rounded-2xl border border-brand-light-border p-6 shadow-soft-luxury space-y-6">
    <!-- Header & Clear All -->
    <div class="flex items-center justify-between pb-4 border-b border-brand-light-border">
        <div class="flex items-center gap-2">
            <i class="fas fa-filter text-brand-burgundy text-sm"></i>
            <h3 class="font-serif text-lg font-bold text-brand-charcoal">Filter Designs</h3>
        </div>
        @if(request()->hasAny(['category', 'search', 'price_range', 'min_price', 'max_price', 'location', 'style', 'color', 'guest_capacity']))
            <a href="{{ route('decorations.index') }}" class="text-xs font-semibold text-brand-royal-rose hover:text-brand-burgundy hover:underline flex items-center gap-1">
                <i class="fas fa-times-circle text-[10px]"></i> Clear All
            </a>
        @endif
    </div>

    <form action="{{ route('decorations.index') }}" method="GET" id="desktop-filter-form" class="space-y-6">
        <!-- Preserve Sort & Search -->
        <input type="hidden" name="sort" value="{{ $currentSort }}">

        <!-- 1. Search Box -->
        <div>
            <label for="desktop-search" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal mb-2">
                Search Decorations
            </label>
            <div class="relative">
                <input 
                    type="text" 
                    name="search" 
                    id="desktop-search"
                    value="{{ $currentSearch }}"
                    placeholder="e.g. Red Mandap, Royal Jaimala..." 
                    class="w-full pl-9 pr-3 py-2 text-xs bg-brand-offwhite border border-brand-light-border rounded-lg focus:outline-none focus:border-brand-burgundy focus:ring-1 focus:ring-brand-burgundy"
                >
                <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-xs"></i>
            </div>
        </div>

        <!-- 2. Ceremony Category -->
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal mb-2.5">
                Ceremony / Category
            </label>
            <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1">
                <label class="flex items-center justify-between text-xs p-1.5 rounded hover:bg-brand-offwhite cursor-pointer transition-colors {{ $currentCategory === 'all' || empty($currentCategory) ? 'text-brand-burgundy font-bold bg-brand-cream/60' : 'text-brand-charcoal' }}">
                    <div class="flex items-center gap-2">
                        <input type="radio" name="category" value="all" {{ $currentCategory === 'all' || empty($currentCategory) ? 'checked' : '' }} onchange="this.form.submit()" class="text-brand-burgundy focus:ring-brand-burgundy text-xs">
                        <span>All Ceremonies</span>
                    </div>
                </label>

                @foreach($categories as $cat)
                    <label class="flex items-center justify-between text-xs p-1.5 rounded hover:bg-brand-offwhite cursor-pointer transition-colors {{ $currentCategory == $cat->slug || $currentCategory == $cat->id ? 'text-brand-burgundy font-bold bg-brand-cream/60' : 'text-brand-charcoal' }}">
                        <div class="flex items-center gap-2 truncate">
                            <input type="radio" name="category" value="{{ $cat->slug }}" {{ $currentCategory == $cat->slug || $currentCategory == $cat->id ? 'checked' : '' }} onchange="this.form.submit()" class="text-brand-burgundy focus:ring-brand-burgundy text-xs">
                            <span class="truncate">{{ $cat->name }}</span>
                        </div>
                        <span class="text-[11px] text-brand-muted-brown font-normal bg-brand-offwhite px-1.5 py-0.5 rounded">
                            {{ $cat->decorations_count }}
                        </span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- 3. Price Range Filter -->
        <div class="pt-4 border-t border-brand-light-border/80">
            <label class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal mb-2.5">
                Price Budget
            </label>
            <div class="space-y-1.5 text-xs text-brand-charcoal">
                <label class="flex items-center gap-2 p-1.5 rounded hover:bg-brand-offwhite cursor-pointer">
                    <input type="radio" name="price_range" value="" {{ empty($currentPriceRange) && !request('min_price') ? 'checked' : '' }} onchange="this.form.submit()" class="text-brand-burgundy">
                    <span>Any Price</span>
                </label>
                <label class="flex items-center gap-2 p-1.5 rounded hover:bg-brand-offwhite cursor-pointer">
                    <input type="radio" name="price_range" value="under_50k" {{ $currentPriceRange === 'under_50k' ? 'checked' : '' }} onchange="this.form.submit()" class="text-brand-burgundy">
                    <span>Under ₹50,000</span>
                </label>
                <label class="flex items-center gap-2 p-1.5 rounded hover:bg-brand-offwhite cursor-pointer">
                    <input type="radio" name="price_range" value="50k_80k" {{ $currentPriceRange === '50k_80k' ? 'checked' : '' }} onchange="this.form.submit()" class="text-brand-burgundy">
                    <span>₹50,000 – ₹80,000</span>
                </label>
                <label class="flex items-center gap-2 p-1.5 rounded hover:bg-brand-offwhite cursor-pointer">
                    <input type="radio" name="price_range" value="80k_120k" {{ $currentPriceRange === '80k_120k' ? 'checked' : '' }} onchange="this.form.submit()" class="text-brand-burgundy">
                    <span>₹80,000 – ₹1,20,000</span>
                </label>
                <label class="flex items-center gap-2 p-1.5 rounded hover:bg-brand-offwhite cursor-pointer">
                    <input type="radio" name="price_range" value="above_120k" {{ $currentPriceRange === 'above_120k' ? 'checked' : '' }} onchange="this.form.submit()" class="text-brand-burgundy">
                    <span>₹1,20,000 &amp; Above</span>
                </label>
            </div>
        </div>

        <!-- 4. Location Filter (Separated: Bihar vs Nearby UP) -->
        <div class="pt-4 border-t border-brand-light-border/80">
            <label for="desktop-location" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal mb-2">
                Service Location
            </label>
            <select name="location" id="desktop-location" onchange="this.form.submit()" class="w-full px-3 py-2 text-xs bg-brand-offwhite border border-brand-light-border rounded-lg text-brand-charcoal focus:outline-none focus:border-brand-burgundy">
                <option value="all" {{ $currentLocation === 'all' ? 'selected' : '' }}>All Service Areas</option>
                <optgroup label="Bihar (Primary Service Hub)">
                    <option value="Siwan" {{ $currentLocation === 'Siwan' ? 'selected' : '' }}>Siwan (Primary Hub)</option>
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

        <!-- 5. Style Filter -->
        <div class="pt-4 border-t border-brand-light-border/80">
            <label class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal mb-2">
                Decoration Style
            </label>
            <div class="flex flex-wrap gap-1.5">
                <a href="{{ request()->fullUrlWithQuery(['style' => 'all', 'page' => 1]) }}" class="px-2.5 py-1 text-[11px] rounded-full border transition-all {{ $currentStyle === 'all' || empty($currentStyle) ? 'bg-brand-burgundy text-white border-brand-burgundy font-bold' : 'bg-brand-offwhite text-brand-charcoal border-brand-light-border hover:border-brand-burgundy' }}">
                    All
                </a>
                @foreach($styles as $st)
                    <a href="{{ request()->fullUrlWithQuery(['style' => $st, 'page' => 1]) }}" class="px-2.5 py-1 text-[11px] rounded-full border transition-all {{ $currentStyle === $st ? 'bg-brand-burgundy text-white border-brand-burgundy font-bold' : 'bg-brand-offwhite text-brand-charcoal border-brand-light-border hover:border-brand-burgundy' }}">
                        {{ $st }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- 6. Color Palette Filter -->
        <div class="pt-4 border-t border-brand-light-border/80">
            <label class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal mb-2">
                Color Palette
            </label>
            <div class="flex flex-wrap gap-1.5">
                <a href="{{ request()->fullUrlWithQuery(['color' => 'all', 'page' => 1]) }}" class="px-2 py-0.5 text-[11px] rounded border {{ $currentColor === 'all' || empty($currentColor) ? 'bg-brand-burgundy text-white font-bold' : 'bg-brand-offwhite text-brand-charcoal border-brand-light-border' }}">
                    All Colors
                </a>
                @foreach($colors as $cl)
                    <a href="{{ request()->fullUrlWithQuery(['color' => $cl, 'page' => 1]) }}" class="px-2 py-0.5 text-[11px] rounded border {{ $currentColor === $cl ? 'bg-brand-burgundy text-white font-bold' : 'bg-brand-offwhite text-brand-charcoal border-brand-light-border hover:border-brand-burgundy' }}">
                        {{ $cl }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- 7. Guest Capacity -->
        <div class="pt-4 border-t border-brand-light-border/80">
            <label for="desktop-capacity" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal mb-2">
                Venue Guest Capacity
            </label>
            <select name="guest_capacity" id="desktop-capacity" onchange="this.form.submit()" class="w-full px-3 py-2 text-xs bg-brand-offwhite border border-brand-light-border rounded-lg text-brand-charcoal focus:outline-none focus:border-brand-burgundy">
                <option value="all" {{ $currentCapacity === 'all' ? 'selected' : '' }}>Any Capacity</option>
                @foreach($guestCapacities as $cap)
                    <option value="{{ $cap }}" {{ $currentCapacity === $cap ? 'selected' : '' }}>{{ $cap }}</option>
                @endforeach
            </select>
        </div>

        <!-- Submit & Reset Buttons -->
        <div class="pt-4 border-t border-brand-light-border space-y-2">
            <button type="submit" class="w-full py-2.5 px-4 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-lg border border-brand-gold shadow-sm transition-all">
                <i class="fas fa-check mr-1.5 text-brand-gold"></i> Apply Filters
            </button>
            <a href="{{ route('decorations.index') }}" class="block w-full text-center py-2 px-4 text-xs font-semibold text-brand-charcoal bg-brand-offwhite hover:bg-brand-light-border/40 rounded-lg border border-brand-light-border transition-colors">
                Reset All Filters
            </a>
        </div>
    </form>
</div>

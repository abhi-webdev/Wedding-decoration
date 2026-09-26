<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;
use App\Models\Decoration;
use App\Models\Offer;
use App\Models\GalleryCategory;
use App\Models\GalleryItem;
use App\Models\Faq;
use App\Models\ServiceArea;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Phase5Seeder extends Seeder
{
    public function run()
    {
        // ------------------------------------------------------------------
        // 1. SEED PACKAGES
        // ------------------------------------------------------------------
        $packagesData = [
            [
                'name' => 'Royal Jaimala Stage Package',
                'slug' => 'royal-jaimala-package',
                'badge' => 'Most Popular',
                'tagline' => 'Majestic floral backdrop with custom royal couple sofa and warm ambient lighting',
                'short_description' => 'A complete ceremonial stage setup crafted with fresh red and yellow marigold, exotic roses, brass urli lamps, and royal velvet bride-groom seating.',
                'description' => 'The Jaimala stage is the centerpiece of North Indian and Bihari weddings. Our Royal Jaimala Package includes a 24x12 ft reinforced royal stage, intricate circular floral arch with fresh Dutch roses, marigold latkans, plush velvet Maharani sofa chairs, 12 focus stage par lights, and elevated photo-friendly bridal entrance pathway.',
                'guest_capacity' => '300–800 Guests',
                'duration' => '6–8 Hours Ceremony',
                'starting_price' => 45000,
                'base_price' => 45000.00,
                'discount_price' => 39999.00,
                'image_url' => 'images/decorations/jaimala-stage-01.jpg',
                'image' => 'images/decorations/jaimala-stage-01.jpg',
                'is_featured' => true,
                'is_active' => true,
                'display_order' => 1,
                'sort_order' => 1,
                'included_ceremonies' => ['Varmala / Jaimala Ceremony', 'Photo Sessions with Guests', 'Couple Seating'],
                'highlights' => ['24x12 ft Grand Floral Stage', 'Royal Velvet Couple Throne', '12 Spot & Ambient LED Lights', 'Traditional Brass Urli Accents', 'Fresh Flower Garlands']
            ],
            [
                'name' => 'Traditional Vivah Mandap Package',
                'slug' => 'traditional-mandap-package',
                'badge' => 'Vedic Sacred Setup',
                'tagline' => 'Auspicious four-pillar Vedic mandap adorned with fresh marigold and mango leaves',
                'short_description' => 'Handcrafted sacred wedding pavilion for sacred Phere, Kanyadan, and Sindoor Daan rituals with traditional wooden havan kund seating.',
                'description' => 'Crafted according to traditional Bihari and Maithil wedding customs. Features four heavy floral pillars wound with golden marigold garlands, sacred Kalash setups, authentic brass havan kund with smoke exhaust arrangement, customized seating for bride, groom, parents, and panditji, plus sacred Toran entrance.',
                'guest_capacity' => 'Traditional Family & Guests',
                'duration' => 'Overnight / 10–12 Hours',
                'starting_price' => 65000,
                'base_price' => 65000.00,
                'discount_price' => 58000.00,
                'image_url' => 'images/decorations/mandap-traditional-01.jpg',
                'image' => 'images/decorations/mandap-traditional-01.jpg',
                'is_featured' => true,
                'is_active' => true,
                'display_order' => 2,
                'sort_order' => 2,
                'included_ceremonies' => ['Saat Phere / Saptapadi', 'Kanyadan', 'Sindoor Daan', 'Havan Puja'],
                'highlights' => ['4-Pillar Heavy Marigold Mandap', 'Brass Havan Kund & Setup', 'Traditional Bajot & Patta Seating', 'Mango Leaves & Banana Stem Toran', 'Sacred Kalash Accents']
            ],
            [
                'name' => 'Haldi & Mehendi Celebration Combo',
                'slug' => 'haldi-mehendi-combo-package',
                'badge' => 'Festive Vibrant Combo',
                'tagline' => 'Bright yellow marigold photobooth, jhula backdrop, and colorful Rajasthani canopy',
                'short_description' => 'Two complete ritual setups featuring cheerful yellow floral curtains, wooden swing photobooth, low floor diwan seating, and vibrant festive cushions.',
                'description' => 'Combine both pre-wedding celebrations seamlessly. For Haldi: a stunning 16x10 ft yellow-and-orange marigold backdrop with brass urli for floral shower and wooden swing. For Mehendi: vibrant draped canopy, colorful bolster cushions, umbrella photobooth props, and fairy light canopy.',
                'guest_capacity' => '100–300 Guests',
                'duration' => 'Full Day (2 Ceremonies)',
                'starting_price' => 35000,
                'base_price' => 35000.00,
                'discount_price' => 29999.00,
                'image_url' => 'images/decorations/haldi-decor-01.jpg',
                'image' => 'images/decorations/haldi-decor-01.jpg',
                'is_featured' => true,
                'is_active' => true,
                'display_order' => 3,
                'sort_order' => 3,
                'included_ceremonies' => ['Haldi Rasam', 'Tel Baan', 'Mehendi / Henna Ceremony', 'Folk Sangeet'],
                'highlights' => ['Decorated Wooden Floral Jhula', 'Brass Urli for Flower Shower', 'Vibrant Yellow & Orange Marigold Wall', 'Low Diwan Seating with Gaddi', 'Decorative Umbrella Props']
            ],
            [
                'name' => 'Grand Sangeet & Musical Night Package',
                'slug' => 'grand-sangeet-package',
                'badge' => 'High Energy & Glam',
                'tagline' => 'High-intensity fairy-light stage with dance floor trusses and gold metallic arches',
                'short_description' => 'Glamorous evening stage setup designed for family dance performances, DJ setup framing, and dynamic warm-white lighting.',
                'description' => 'Designed for unforgettable music nights. Includes a durable wooden performance stage, geometric gold frames intertwined with white carnations and orchids, crystal chandeliers, warm Edison bulb curtains, and focused moving head spotlights.',
                'guest_capacity' => '250–600 Guests',
                'duration' => '6 Hours Evening',
                'starting_price' => 55000,
                'base_price' => 55000.00,
                'discount_price' => 49000.00,
                'image_url' => 'images/decorations/sangeet-stage-01.jpg',
                'image' => 'images/decorations/sangeet-stage-01.jpg',
                'is_featured' => false,
                'is_active' => true,
                'display_order' => 4,
                'sort_order' => 4,
                'included_ceremonies' => ['Family Dance Performances', 'Sangeet Night', 'Ring Ceremony / Sagai'],
                'highlights' => ['Reinforced Dance Stage', 'Geometric Gold Arches', 'Edison Bulb Cascades & Fairy Curtain', 'Moving Head Stage Lights', 'Crystal Chandelier Centerpieces']
            ],
            [
                'name' => 'Royal Wedding Reception Setup',
                'slug' => 'royal-reception-package',
                'badge' => 'Modern Luxury',
                'tagline' => 'Contemporary floral walls, crystal candelabras, and elegant burgundy-gold arches',
                'short_description' => 'A sophisticated reception stage with white liliums, blush hydrangeas, gold lattice panels, and red carpet VIP entry tunnel.',
                'description' => 'Impress your wedding guests with a breathtaking reception stage. Features 30x14 ft expansive floral backdrop, gold-embossed jaali panels, crystal standing candelabras, plush Maharaja 3-seater sofa, and 60-foot entrance walkway lined with Roman floral pillars.',
                'guest_capacity' => '500–1500 Guests',
                'duration' => '8 Hours Evening',
                'starting_price' => 75000,
                'base_price' => 75000.00,
                'discount_price' => 68000.00,
                'image_url' => 'images/decorations/reception-stage-01.jpg',
                'image' => 'images/decorations/reception-stage-01.jpg',
                'is_featured' => true,
                'is_active' => true,
                'display_order' => 5,
                'sort_order' => 5,
                'included_ceremonies' => ['Grand Reception', 'VIP Guest Felicitations', 'Cake Cutting Ceremony'],
                'highlights' => ['30-Foot Expansive Stage', 'Gold Moroccan Jaali Backdrops', '60-Foot Floral Entrance Walkway', 'Crystal Candelabras & Centerpieces', 'Red Velvet Carpet Runner']
            ],
            [
                'name' => 'Complete 3-Day Bihar Vivah Package',
                'slug' => 'complete-bihar-vivah-package',
                'badge' => 'All-Inclusive Value',
                'tagline' => 'End-to-end decor for Tilak, Haldi, Mehendi, Sangeet, Jaimala, Mandap, and Reception',
                'short_description' => 'Our flagship full-wedding package covering every ritual over 3 days with dedicated on-site event crew in Siwan, Patna, or Gorakhpur.',
                'description' => 'Leave every decoration requirement in expert hands. Includes full outdoor/hall transformation: Tilak backdrop, Haldi setup with floral swing, Mehendi canopy, Grand Sangeet stage, Jaimala stage, Vedic 4-pillar Mandap, complete venue lighting, Baraat swagat entrance gate, and photo zones.',
                'guest_capacity' => 'Unlimited Venue Capacity',
                'duration' => '3 Full Days & Nights',
                'starting_price' => 165000,
                'base_price' => 165000.00,
                'discount_price' => 145000.00,
                'image_url' => 'images/decorations/mandap-traditional-01.jpg',
                'image' => 'images/decorations/mandap-traditional-01.jpg',
                'is_featured' => true,
                'is_active' => true,
                'display_order' => 6,
                'sort_order' => 6,
                'included_ceremonies' => ['Day 1: Tilak & Sagai', 'Day 2: Haldi & Mehendi', 'Day 2 Night: Sangeet', 'Day 3: Baraat, Jaimala & Mandap', 'Day 4: Reception'],
                'highlights' => ['Complete 3-Day Decor Coverage', 'Dedicated 12-Member Setup Crew', 'Baraat Swagat Toran & Fog Machine', 'Mandap + Jaimala + Haldi + Reception', 'VIP Family Dining & Photobooth']
            ],
            [
                'name' => 'Tilak & Sagai Intimate Setup',
                'slug' => 'tilak-sagai-package',
                'badge' => 'Intimate & Elegant',
                'tagline' => 'Traditional marigold and brass urli backdrop for sacred pre-wedding shagun rituals',
                'short_description' => 'A tasteful and culturally rooted decoration for home or banquet Tilak and Sagai (engagement) ceremonies.',
                'description' => 'Features neat floral ring backdrop, traditional red and gold fabric drapes, brass thali presentation stands, decorative low seating for groom and family elders, and subtle warm lighting.',
                'guest_capacity' => '80–200 Guests',
                'duration' => '5 Hours',
                'starting_price' => 28000,
                'base_price' => 28000.00,
                'discount_price' => 24500.00,
                'image_url' => 'images/decorations/jaimala-stage-01.jpg',
                'image' => 'images/decorations/jaimala-stage-01.jpg',
                'is_featured' => false,
                'is_active' => true,
                'display_order' => 7,
                'sort_order' => 7,
                'included_ceremonies' => ['Tilak Samaroh', 'Sagai / Engagement', 'Godh Bharai'],
                'highlights' => ['Traditional Shagun Table Setup', 'Brass Urli with Floating Diyas', 'Floral Ring Backdrop', 'Carpeted Ritual Platform', 'Warm Focus Spotlights']
            ],
            [
                'name' => 'Royal Heritage Pandal & Entrance Package',
                'slug' => 'royal-pandal-entrance-package',
                'badge' => 'Grand Entrance',
                'tagline' => 'Majestic temple-style entrance archway with 100-foot fabric ceiling canopy and lighting',
                'short_description' => 'Transform your open lawn or marriage hall entrance into a royal palace walkway with Mughal arches and heavy marigold work.',
                'description' => 'Welcome Baraat and guests with regal majesty. Includes 24-foot high royal gate with traditional elephant statues, 100-foot floral walkway, fabric ceiling canopy with fairy lighting, 8 Roman pillars topped with flower cascades, and entrance cold pyro / smoke entry arrangement.',
                'guest_capacity' => 'Full Venue Entry',
                'duration' => 'Full Event Day',
                'starting_price' => 85000,
                'base_price' => 85000.00,
                'discount_price' => 76000.00,
                'image_url' => 'images/decorations/reception-stage-01.jpg',
                'image' => 'images/decorations/reception-stage-01.jpg',
                'is_featured' => false,
                'is_active' => true,
                'display_order' => 8,
                'sort_order' => 8,
                'included_ceremonies' => ['Baraat Welcome', 'Guest Reception', 'Red Carpet Bridal Entry'],
                'highlights' => ['24-Foot Royal Swagat Gate', '100-Foot Fairy Light Fabric Walkway', '8 Floral Roman Pillars', 'Cold Pyro Machine Setup', 'Mughal Style Entrance Arches']
            ],
        ];

        foreach ($packagesData as $pkgInfo) {
            $pkg = Package::updateOrCreate(['slug' => $pkgInfo['slug']], $pkgInfo);
            
            // Associate random decorations if available
            $decs = Decoration::take(2)->pluck('id');
            if ($decs->isNotEmpty()) {
                $pkg->decorations()->syncWithoutDetaching($decs->mapWithKeys(fn($id) => [$id => ['quantity' => 1]]));
            }
        }

        // ------------------------------------------------------------------
        // 2. SEED OFFERS
        // ------------------------------------------------------------------
        $offersData = [
            [
                'title' => 'Wedding Season Special 2026',
                'slug' => 'wedding-season-special',
                'subtitle' => 'Save 15% on Jaimala + Mandap Combo Bookings',
                'short_description' => 'Book your wedding date in Siwan, Gopalganj, Patna, or Gorakhpur and get flat 15% discount on all premium floral packages.',
                'description' => 'Make your wedding grand while optimizing your budget. When you book both Jaimala Stage and Vedic Mandap decoration together, you automatically receive a 15% discount on the total estimated invoice. Includes free VIP entrance carpet runner.',
                'highlight_badge' => 'Limited Seasonal Offer',
                'discount_text' => '15% OFF',
                'discount_type' => 'percentage',
                'discount_value' => 15.00,
                'coupon_code' => 'UTSAV15',
                'valid_from' => Carbon::now()->subDays(10),
                'valid_until' => Carbon::now()->addMonths(6),
                'terms' => 'Applicable for wedding dates in 2026. Minimum booking amount ₹50,000. Cannot be combined with other festival voucher codes.',
                'cta_text' => 'Claim Offer via Quote',
                'cta_link' => '/quote',
                'image_url' => 'images/decorations/jaimala-stage-01.jpg',
                'image' => 'images/decorations/jaimala-stage-01.jpg',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Haldi & Mehendi Combo Saver',
                'slug' => 'haldi-mehendi-combo-offer',
                'subtitle' => 'Flat ₹5,000 Discount on Pre-Wedding Rituals',
                'short_description' => 'Celebrate your Haldi & Mehendi rituals in vibrant style with ₹5,000 off on our combined floral swing & canopy setup.',
                'description' => 'Includes customized yellow marigold photobooth, wooden swing with fresh flowers, traditional brass urli for flower bath, and colorful Rajasthani Mehendi backdrop.',
                'highlight_badge' => 'Pre-Wedding Saver',
                'discount_text' => '₹5,000 OFF',
                'discount_type' => 'fixed',
                'discount_value' => 5000.00,
                'coupon_code' => 'HALDI5K',
                'valid_from' => Carbon::now()->subDays(5),
                'valid_until' => Carbon::now()->addMonths(4),
                'terms' => 'Valid for bookings having both Haldi and Mehendi events booked with Aditya Utsav.',
                'cta_text' => 'Get a Quote',
                'cta_link' => '/quote',
                'image_url' => 'images/decorations/haldi-decor-01.jpg',
                'image' => 'images/decorations/haldi-decor-01.jpg',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Complete Vivah Full-Venue Privilege',
                'slug' => 'complete-vivah-privilege-offer',
                'subtitle' => 'Flat ₹15,000 OFF on 3-Day Full Wedding Coverage',
                'short_description' => 'Book end-to-end 3-day wedding decoration and receive comprehensive discount plus complimentary cold pyro entrance setup.',
                'description' => 'Our most comprehensive package privilege. Covers complete lawn/hall decor from Tilak to Reception. Includes free smoke fog machine entry and 4 cold pyro sparkles for the couple entry.',
                'highlight_badge' => 'Grand Value',
                'discount_text' => '₹15,000 OFF',
                'discount_type' => 'fixed',
                'discount_value' => 15000.00,
                'coupon_code' => 'ROYAL15K',
                'valid_from' => Carbon::now()->subDays(1),
                'valid_until' => Carbon::now()->addMonths(8),
                'terms' => 'Applies exclusively to Complete 3-Day Bihar Vivah Package bookings in Bihar Core and Eastern UP districts.',
                'cta_text' => 'Request Package Quote',
                'cta_link' => '/quote',
                'image_url' => 'images/decorations/mandap-traditional-01.jpg',
                'image' => 'images/decorations/mandap-traditional-01.jpg',
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($offersData as $off) {
            Offer::updateOrCreate(['slug' => $off['slug']], $off);
        }

        // ------------------------------------------------------------------
        // 3. SEED GALLERY CATEGORIES & 30+ GALLERY ITEMS
        // ------------------------------------------------------------------
        $galleryCats = [
            ['name' => 'Jaimala Stages', 'slug' => 'jaimala', 'description' => 'Grand bridal and groom exchange stages with floral arches & royal seating', 'sort_order' => 1],
            ['name' => 'Vedic Mandap', 'slug' => 'mandap', 'description' => 'Sacred 4-pillar Vedic pavilions for Saat Phere & traditional rituals', 'sort_order' => 2],
            ['name' => 'Haldi Ceremony', 'slug' => 'haldi', 'description' => 'Bright yellow marigold setups, brass urlis, and floral jhula setups', 'sort_order' => 3],
            ['name' => 'Mehendi Nights', 'slug' => 'mehendi', 'description' => 'Vibrant bohemian & Rajasthani draped canopies with festive bolsters', 'sort_order' => 4],
            ['name' => 'Grand Sangeet', 'slug' => 'sangeet', 'description' => 'Edison bulb backdrops, performance dance floors, and gold arches', 'sort_order' => 5],
            ['name' => 'Royal Reception', 'slug' => 'reception', 'description' => 'Contemporary luxury backdrops with crystal candelabras and lush florals', 'sort_order' => 6],
            ['name' => 'Grand Entrances', 'slug' => 'entrance', 'description' => 'Royal palace gate designs, flower tunnels, and carpet walkways', 'sort_order' => 7],
            ['name' => 'Pandal & Lighting', 'slug' => 'pandal-lighting', 'description' => 'Complete venue transformation, ceiling draping, and illumination', 'sort_order' => 8],
        ];

        $catModelMap = [];
        foreach ($galleryCats as $gc) {
            $catModelMap[$gc['slug']] = GalleryCategory::updateOrCreate(['slug' => $gc['slug']], $gc);
        }

        $galleryPhotos = [
            // Jaimala
            ['title' => 'Royal Marigold & Rose Jaimala Stage', 'slug' => 'royal-marigold-rose-jaimala-01', 'category' => 'jaimala', 'image' => 'images/decorations/jaimala-stage-01.jpg', 'location' => 'Siwan, Bihar', 'caption' => '24ft grand arch stage decorated with fresh Marigold and Red Roses for a royal wedding in Siwan.'],
            ['title' => 'Golden Velvet Maharani Jaimala Setup', 'slug' => 'golden-velvet-jaimala-02', 'category' => 'jaimala', 'image' => 'images/decorations/jaimala-stage-01.jpg', 'location' => 'Patna, Bihar', 'caption' => 'Gold laser-cut arches paired with red velvet throne and warm fairy light waterfall.'],
            ['title' => 'Pastel Floral Circular Varmala Backdrop', 'slug' => 'pastel-floral-varmala-03', 'category' => 'jaimala', 'image' => 'images/decorations/jaimala-stage-01.jpg', 'location' => 'Gopalganj, Bihar', 'caption' => 'Modern circular ring design with blush roses, hydrangeas, and brass diyas.'],
            ['title' => 'Traditional Bihar Vivah Jaimala Stage', 'slug' => 'traditional-bihar-jaimala-04', 'category' => 'jaimala', 'image' => 'images/decorations/jaimala-stage-01.jpg', 'location' => 'Chapra, Bihar', 'caption' => 'Authentic Bihari floral motifs with brass urli lanterns and plush royal sofa.'],

            // Mandap
            ['title' => 'Traditional 4-Pillar Marigold Mandap', 'slug' => 'traditional-4-pillar-mandap-01', 'category' => 'mandap', 'image' => 'images/decorations/mandap-traditional-01.jpg', 'location' => 'Mairwa, Bihar', 'caption' => 'Vedic sacred pavilion with mango leaves, banana stems, and heavy yellow Genda Phool garlands.'],
            ['title' => 'Temple Arch Vedic Vivah Mandap', 'slug' => 'temple-arch-mandap-02', 'category' => 'mandap', 'image' => 'images/decorations/mandap-traditional-01.jpg', 'location' => 'Siwan, Bihar', 'caption' => 'Carved temple pillars with authentic brass Havan Kund and traditional Bajot seating.'],
            ['title' => 'Red & Gold Royal Phere Mandap', 'slug' => 'red-gold-mandap-03', 'category' => 'mandap', 'image' => 'images/decorations/mandap-traditional-01.jpg', 'location' => 'Gorakhpur, Uttar Pradesh', 'caption' => 'Lawn mandap canopy with crystal chandeliers and auspicious Kalash arrangements.'],
            ['title' => 'Dome Floral Canopy Mandap Setup', 'slug' => 'dome-floral-mandap-04', 'category' => 'mandap', 'image' => 'images/decorations/mandap-traditional-01.jpg', 'location' => 'Patna, Bihar', 'caption' => 'Overnight Phere pavilion decorated with fresh tuberose (Rajnigandha) and marigold strings.'],

            // Haldi
            ['title' => 'Sunflower & Genda Phool Haldi Backdrop', 'slug' => 'sunflower-genda-haldi-01', 'category' => 'haldi', 'image' => 'images/decorations/haldi-decor-01.jpg', 'location' => 'Siwan, Bihar', 'caption' => 'Vibrant yellow curtain backdrop with brass urli and fresh marigold flower shower bath.'],
            ['title' => 'Floral Swing (Jhula) Haldi Photobooth', 'slug' => 'floral-swing-haldi-02', 'category' => 'haldi', 'image' => 'images/decorations/haldi-decor-01.jpg', 'location' => 'Gopalganj, Bihar', 'caption' => 'Decorated wooden swing adorned with marigold tassels for family photography.'],
            ['title' => 'Traditional Courtyard Haldi Rasam Setup', 'slug' => 'traditional-courtyard-haldi-03', 'category' => 'haldi', 'image' => 'images/decorations/haldi-decor-01.jpg', 'location' => 'Mairwa, Bihar', 'caption' => 'Low gaddi seating, matka props, and cheerful festive drapes for daytime ceremony.'],
            ['title' => 'Marigold Canopy & Urli Haldi Decor', 'slug' => 'marigold-canopy-haldi-04', 'category' => 'haldi', 'image' => 'images/decorations/haldi-decor-01.jpg', 'location' => 'Deoria, Uttar Pradesh', 'caption' => 'Outdoor garden Haldi ceremony setup with hand-woven marigold mats and umbrellas.'],

            // Mehendi
            ['title' => 'Colorful Rajasthani Mehendi Canopy', 'slug' => 'colorful-mehendi-canopy-01', 'category' => 'mehendi', 'image' => 'images/decorations/haldi-decor-01.jpg', 'location' => 'Patna, Bihar', 'caption' => 'Multi-colored fabric tent with embroidered bolsters and brass lamps for bridal mehendi.'],
            ['title' => 'Bohemian Floral Mehendi Lounge', 'slug' => 'bohemian-mehendi-lounge-02', 'category' => 'mehendi', 'image' => 'images/decorations/haldi-decor-01.jpg', 'location' => 'Siwan, Bihar', 'caption' => 'Cozy floor seating arrangement with fairy lighting and personalized wooden signage.'],
            ['title' => 'Garden Umbrella Mehendi Setup', 'slug' => 'garden-umbrella-mehendi-03', 'category' => 'mehendi', 'image' => 'images/decorations/haldi-decor-01.jpg', 'location' => 'Chapra, Bihar', 'caption' => 'Kite and mirror-work parasols with fresh floral table centerpieces.'],
            ['title' => 'Evening Mehendi & Folk Sangeet Decor', 'slug' => 'evening-mehendi-sangeet-04', 'category' => 'mehendi', 'image' => 'images/decorations/haldi-decor-01.jpg', 'location' => 'Gopalganj, Bihar', 'caption' => 'Intimate garden lighting with candle lanterns and traditional dholak setup.'],

            // Sangeet
            ['title' => 'Edison Bulb Curtain Sangeet Stage', 'slug' => 'edison-bulb-sangeet-01', 'category' => 'sangeet', 'image' => 'images/decorations/sangeet-stage-01.jpg', 'location' => 'Siwan, Bihar', 'caption' => 'Warm glow bulb cascades with gold geometric frames for family dance performances.'],
            ['title' => 'Crystal Chandelier Sangeet Performance Stage', 'slug' => 'crystal-chandelier-sangeet-02', 'category' => 'sangeet', 'image' => 'images/decorations/sangeet-stage-01.jpg', 'location' => 'Patna, Bihar', 'caption' => 'Reinforced wooden stage with moving head spot lighting and floral borders.'],
            ['title' => 'Bollywood Glam Musical Night Setup', 'slug' => 'bollywood-glam-sangeet-03', 'category' => 'sangeet', 'image' => 'images/decorations/sangeet-stage-01.jpg', 'location' => 'Gorakhpur, Uttar Pradesh', 'caption' => 'Gold metallic backdrop framing LED DJ console and dance floor.'],
            ['title' => 'Intimate Sangeet & Ring Ceremony Stage', 'slug' => 'intimate-sangeet-sagai-04', 'category' => 'sangeet', 'image' => 'images/decorations/sangeet-stage-01.jpg', 'location' => 'Mairwa, Bihar', 'caption' => 'White floral cascades with velvet seating for engagement & ring exchange.'],

            // Reception
            ['title' => 'Grand Floral Wall Wedding Reception', 'slug' => 'grand-floral-wall-reception-01', 'category' => 'reception', 'image' => 'images/decorations/reception-stage-01.jpg', 'location' => 'Patna, Bihar', 'caption' => '30ft wide floral wall with white liliums, carnations, and gold filigree arches.'],
            ['title' => 'Mughal Jaali & Candelabra Reception Stage', 'slug' => 'mughal-jaali-reception-02', 'category' => 'reception', 'image' => 'images/decorations/reception-stage-01.jpg', 'location' => 'Siwan, Bihar', 'caption' => 'Gold laser-cut jaali screens with 5-arm crystal candelabras and royal Maharaja sofa.'],
            ['title' => 'Burgundy & Gold Velvet Reception Setup', 'slug' => 'burgundy-gold-reception-03', 'category' => 'reception', 'image' => 'images/decorations/reception-stage-01.jpg', 'location' => 'Gopalganj, Bihar', 'caption' => 'Regal burgundy drapery with cascading orchids and warm ambient spot lighting.'],
            ['title' => 'Outdoor Lawn Banquet Reception Stage', 'slug' => 'outdoor-lawn-reception-04', 'category' => 'reception', 'image' => 'images/decorations/reception-stage-01.jpg', 'location' => 'Chapra, Bihar', 'caption' => 'Elevated lawn stage with red carpet runway and illuminated floral borders.'],

            // Entrances
            ['title' => 'Royal Swagat Toran & Palace Gate', 'slug' => 'royal-swagat-toran-01', 'category' => 'entrance', 'image' => 'images/decorations/reception-stage-01.jpg', 'location' => 'Siwan, Bihar', 'caption' => '24ft high grand entrance arch decorated with marigold garlands and royal elephant statues.'],
            ['title' => '100-Foot Floral Walkway Tunnel', 'slug' => '100ft-floral-walkway-02', 'category' => 'entrance', 'image' => 'images/decorations/reception-stage-01.jpg', 'location' => 'Patna, Bihar', 'caption' => 'Illuminated fairy light tunnel lined with fresh rose bouquets and red carpet runner.'],
            ['title' => 'Traditional Brass Urli Swagat Entrance', 'slug' => 'brass-urli-swagat-03', 'category' => 'entrance', 'image' => 'images/decorations/jaimala-stage-01.jpg', 'location' => 'Mairwa, Bihar', 'caption' => 'Water urlis with floating rose petals and fragrant marigold torans at venue doorway.'],
            ['title' => 'Roman Floral Pillar Entrance Pathway', 'slug' => 'roman-floral-pillar-entrance-04', 'category' => 'entrance', 'image' => 'images/decorations/reception-stage-01.jpg', 'location' => 'Gorakhpur, Uttar Pradesh', 'caption' => 'White fluted pillars with cascading floral urns for bridal and baraat procession.'],

            // Pandal & Lighting
            ['title' => 'Traditional Fabric Ceiling Pandal Canopy', 'slug' => 'traditional-fabric-pandal-01', 'category' => 'pandal-lighting', 'image' => 'images/decorations/mandap-traditional-01.jpg', 'location' => 'Siwan, Bihar', 'caption' => 'Warm gold and maroon ceiling draping with central chandelier for banquet hall.'],
            ['title' => 'Lawn Tree Fairy Light Illumination', 'slug' => 'lawn-tree-fairy-light-02', 'category' => 'pandal-lighting', 'image' => 'images/decorations/sangeet-stage-01.jpg', 'location' => 'Patna, Bihar', 'caption' => 'Complete outdoor garden lighting with tree wraps, pathway bollards, and focus pars.'],
            ['title' => 'VIP Family Dining & Buffet Decor', 'slug' => 'vip-dining-buffet-decor-03', 'category' => 'pandal-lighting', 'image' => 'images/decorations/reception-stage-01.jpg', 'location' => 'Gopalganj, Bihar', 'caption' => 'Decorated buffet counters with floral runners, chafing dish covers, and ambient lights.'],
            ['title' => 'Traditional Shamiana Pandal Setup', 'slug' => 'traditional-shamiana-pandal-04', 'category' => 'pandal-lighting', 'image' => 'images/decorations/mandap-traditional-01.jpg', 'location' => 'Deoria, Uttar Pradesh', 'caption' => 'Weather-resistant festive shamiana tent with waterproof lining for grand Bihar weddings.'],
        ];

        foreach ($galleryPhotos as $idx => $gp) {
            $cat = $catModelMap[$gp['category']] ?? null;
            GalleryItem::updateOrCreate(
                ['slug' => $gp['slug']],
                [
                    'gallery_category_id' => $cat?->id,
                    'title' => $gp['title'],
                    'slug' => $gp['slug'],
                    'category' => $gp['category'],
                    'event_type' => ucfirst($gp['category']),
                    'image_url' => $gp['image'],
                    'image' => $gp['image'],
                    'location' => $gp['location'],
                    'caption' => $gp['caption'],
                    'description' => $gp['caption'],
                    'is_featured' => ($idx % 3 === 0),
                    'is_active' => true,
                    'display_order' => $idx + 1,
                    'sort_order' => $idx + 1,
                ]
            );
        }

        // ------------------------------------------------------------------
        // 4. SEED FAQS (14+ categorized)
        // ------------------------------------------------------------------
        $faqsData = [
            // Booking
            ['category' => 'Booking', 'question' => 'How far in advance should I book my wedding decoration with Aditya Utsav?', 'answer' => 'For peak wedding dates (Sawa / Lagan season in November–February and April–June), we recommend submitting your booking request 4 to 8 weeks in advance to secure crew and inventory for your preferred date.'],
            ['category' => 'Booking', 'question' => 'How do I know if my wedding date is available?', 'answer' => 'You can check real-time availability on any decoration page or the Book Now availability checker. Once you submit a booking request, our Siwan operations manager calls within 2–4 hours to confirm scheduling.'],
            ['category' => 'Booking', 'question' => 'Can I customize an existing decoration package?', 'answer' => 'Yes, absolutely! All our packages can be tailored to match your specific color theme, flower preferences (e.g. all-marigold or rose-orchid mix), stage dimensions, and ceremony requirements.'],

            // Decorations
            ['category' => 'Decorations', 'question' => 'Do you use fresh natural flowers or artificial flower setups?', 'answer' => 'We offer both options based on your preference. Our traditional Vedic Mandap and Jaimala setups predominantly use fresh seasonal marigold, Dutch roses, and Rajnigandha. For high-ceiling stages and multi-day canopies, we combine high-grade silk florals with fresh flower accents.'],
            ['category' => 'Decorations', 'question' => 'What time does the decoration crew arrive at the venue on event day?', 'answer' => 'Our fabrication crew arrives 4 to 6 hours before the ceremony start time for stage erection and lighting tests. For grand full-venue weddings, setup begins the night before or early morning.'],

            // Pricing
            ['category' => 'Pricing', 'question' => 'Are decoration prices inclusive of transportation and labor charges?', 'answer' => 'Our listed starting prices include full setup labor, materials, and local transportation within Siwan, Mairwa, and Gopalganj. For extended districts in Bihar and Eastern UP, nominal travel allowances apply and are transparently itemized in your quote.'],
            ['category' => 'Pricing', 'question' => 'Is there any hidden cost after receiving the quotation?', 'answer' => 'No. Aditya Utsav follows transparent, upfront pricing. The digital quotation provided before token advance covers stage, flowers, seating, lighting, and teardown.'],

            // Locations
            ['category' => 'Locations', 'question' => 'Which cities and districts do you serve in Bihar?', 'answer' => 'Our primary hub is located in Siwan. We regularly provide complete wedding decoration services across Siwan, Mairwa, Gopalganj, Chapra (Saran), Patna, Muzaffarpur, and surrounding Bihar districts.'],
            ['category' => 'Locations', 'question' => 'Do you take wedding decoration orders in Uttar Pradesh?', 'answer' => 'Yes. We cater to nearby Eastern Uttar Pradesh districts including Gorakhpur, Deoria, Bhatpar Rani, and Salempur with dedicated transport and crew.'],

            // Cancellation & Rescheduling
            ['category' => 'Cancellation', 'question' => 'What is your cancellation and date rescheduling policy?', 'answer' => 'You can submit a cancellation or reschedule request directly from your Customer Account dashboard. Date changes requested at least 14 days before the event can be accommodated based on date availability without forfeiture of token advances.'],
            ['category' => 'Cancellation', 'question' => 'What happens if our wedding date changes due to family reasons or muhurat change?', 'answer' => 'Simply click "Request Reschedule" in your My Bookings area and choose your new requested date. Our team will verify slot availability and reassign crew without needing to cancel your booking.'],

            // Payments
            ['category' => 'Payments', 'question' => 'What is the payment structure for wedding decoration?', 'answer' => 'We typically work on a standard 3-tier milestone structure: a nominal token advance upon date locking, 50% upon material staging on event morning, and the remaining balance post-ceremony completion.'],

            // General
            ['category' => 'General', 'question' => 'Can you work with both outdoor lawn venues and indoor marriage halls?', 'answer' => 'Yes. We have customized staging, trussing, and weather-proof canopy systems designed specifically for outdoor lawns, community halls, hotel banquets, and family home courtyards.'],
            ['category' => 'General', 'question' => 'Can we provide our own reference photo from Pinterest or Instagram?', 'answer' => 'Yes! You can upload reference images directly through our "Get a Quote" form. Our design team will analyze the setup and provide a realistic custom quotation.'],
        ];

        foreach ($faqsData as $idx => $faq) {
            Faq::updateOrCreate(
                ['question' => $faq['question']],
                [
                    'answer' => $faq['answer'],
                    'category' => $faq['category'],
                    'is_featured' => ($idx < 6),
                    'is_active' => true,
                    'display_order' => $idx + 1,
                    'sort_order' => $idx + 1,
                ]
            );
        }

        // ------------------------------------------------------------------
        // 5. SEED SERVICE AREAS (Bihar vs Uttar Pradesh)
        // ------------------------------------------------------------------
        $areasData = [
            // Bihar Core
            ['name' => 'Siwan', 'slug' => 'siwan', 'state' => 'Bihar', 'district' => 'Siwan', 'category' => 'Bihar Core', 'status_note' => 'Main Office & Crew Hub', 'description' => 'Our primary operations hub. Full decoration inventory, 4-hour setup response, and dedicated local manager.', 'is_primary' => true, 'is_active' => true, 'sort_order' => 1],
            ['name' => 'Mairwa', 'slug' => 'mairwa', 'state' => 'Bihar', 'district' => 'Siwan', 'category' => 'Bihar Core', 'status_note' => 'Full Team & Transport', 'description' => 'Core service territory with zero extra transit charges. Comprehensive Mandap and Jaimala setups.', 'is_primary' => true, 'is_active' => true, 'sort_order' => 2],
            ['name' => 'Gopalganj', 'slug' => 'gopalganj', 'state' => 'Bihar', 'district' => 'Gopalganj', 'category' => 'Bihar Core', 'status_note' => 'Full Team Available', 'description' => 'Regular daily service covering Gopalganj town, Thawe, Hathwa, and Barauli marriage halls and lawns.', 'is_primary' => true, 'is_active' => true, 'sort_order' => 3],
            ['name' => 'Chapra (Saran)', 'slug' => 'chapra-saran', 'state' => 'Bihar', 'district' => 'Saran', 'category' => 'Bihar Core', 'status_note' => 'Full Team Available', 'description' => 'Serving Chapra city, Sonpur, Marhaura, and Ekma banquet venues with complete staging and lighting.', 'is_primary' => true, 'is_active' => true, 'sort_order' => 4],

            // Bihar Extended
            ['name' => 'Patna', 'slug' => 'patna', 'state' => 'Bihar', 'district' => 'Patna', 'category' => 'Bihar Extended', 'status_note' => 'Premium Weddings Hub', 'description' => 'Catering to grand hotel banquets, luxury resort lawns, and destination weddings across Patna and Danapur.', 'is_primary' => false, 'is_active' => true, 'sort_order' => 5],
            ['name' => 'Muzaffarpur', 'slug' => 'muzaffarpur', 'state' => 'Bihar', 'district' => 'Muzaffarpur', 'category' => 'Bihar Extended', 'status_note' => 'By Prior Reservation', 'description' => 'Full event decoration coverage for large marriage grounds and reception venues across North Bihar.', 'is_primary' => false, 'is_active' => true, 'sort_order' => 6],
            ['name' => 'Darbhanga', 'slug' => 'darbhanga', 'state' => 'Bihar', 'district' => 'Darbhanga', 'category' => 'Bihar Extended', 'status_note' => 'By Prior Reservation', 'description' => 'Specialized traditional Maithil Vivah mandaps and grand reception stages in Mithila region.', 'is_primary' => false, 'is_active' => true, 'sort_order' => 7],

            // Nearby Uttar Pradesh (Strictly labelled state = Uttar Pradesh)
            ['name' => 'Gorakhpur', 'slug' => 'gorakhpur', 'state' => 'Uttar Pradesh', 'district' => 'Gorakhpur', 'category' => 'Nearby Uttar Pradesh', 'status_note' => 'Eastern UP Hub', 'description' => 'Serving Gorakhpur marriage lawns, banquet hotels, and wedding venues with dedicated transport.', 'is_primary' => true, 'is_active' => true, 'sort_order' => 8],
            ['name' => 'Deoria', 'slug' => 'deoria', 'state' => 'Uttar Pradesh', 'district' => 'Deoria', 'category' => 'Nearby Uttar Pradesh', 'status_note' => 'Regular UP Coverage', 'description' => 'Full decoration setups for Deoria city, Bhatni, and surrounding marriage gardens.', 'is_primary' => true, 'is_active' => true, 'sort_order' => 9],
            ['name' => 'Bhatpar Rani', 'slug' => 'bhatpar-rani', 'state' => 'Uttar Pradesh', 'district' => 'Deoria', 'category' => 'Nearby Uttar Pradesh', 'status_note' => 'Fast Border Transit', 'description' => 'Direct border area coverage from Siwan with quick crew turnaround and zero delay.', 'is_primary' => false, 'is_active' => true, 'sort_order' => 10],
            ['name' => 'Salempur', 'slug' => 'salempur', 'state' => 'Uttar Pradesh', 'district' => 'Deoria', 'category' => 'Nearby Uttar Pradesh', 'status_note' => 'Full Team Available', 'description' => 'Serving Salempur, Lar, and nearby UP town wedding venues with traditional and modern themes.', 'is_primary' => false, 'is_active' => true, 'sort_order' => 11],
        ];

        foreach ($areasData as $ad) {
            ServiceArea::updateOrCreate(['slug' => $ad['slug']], $ad);
        }
    }
}

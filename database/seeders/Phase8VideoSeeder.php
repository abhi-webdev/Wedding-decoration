<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WeddingVideo;
use App\Models\Category;

class Phase8VideoSeeder extends Seeder
{
    public function run()
    {
        $categories = Category::all()->keyBy('slug');

        $videos = [
            [
                'title' => 'Traditional Bihar Jaimala Stage Transformation',
                'slug' => 'traditional-bihar-jaimala-stage-transformation',
                'short_description' => 'Royal floral arch, golden sofa seating, and cold pyro entry setup for a grand Jaimala ceremony in Siwan.',
                'description' => 'Step inside this breathtaking royal Jaimala stage setup executed by Aditya Utsav in Siwan, Bihar. Featuring cascading fresh Rajnigandha, red rose garlands, crystal chandeliers, warm ambient lighting, and dedicated varmala pyros.',
                'video_path' => null,
                'thumbnail_path' => null,
                'video_type' => 'reel',
                'event_type' => 'jaimala',
                'category_id' => $categories->get('jaimala')?->id,
                'location' => 'Siwan, Bihar',
                'duration' => '00:18',
                'is_featured' => true,
                'is_homepage' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Vedic Vivah Mandap with Fresh Marigold Setup',
                'slug' => 'vedic-vivah-mandap-fresh-marigold-setup',
                'short_description' => 'Sacred 4-pillar Vedic Mandap with fresh marigold hangings, brass havan kund, and traditional Kalash setup.',
                'description' => 'Authentic North Indian Vedic Vivah Mandap created for Saat Phere in Gopalganj. Built using traditional yellow and orange Genda phool garlands, sacred canopy drapes, and ceremonial wooden seating for family elders.',
                'video_path' => null,
                'thumbnail_path' => null,
                'video_type' => 'reel',
                'event_type' => 'mandap',
                'category_id' => $categories->get('wedding-mandap')?->id,
                'location' => 'Gopalganj, Bihar',
                'duration' => '00:24',
                'is_featured' => true,
                'is_homepage' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Joyful Yellow Haldi Courtyard & Urli Decor',
                'slug' => 'joyful-yellow-haldi-courtyard-urli-decor',
                'short_description' => 'Vibrant yellow marigold backdrop, traditional brass urli for bride/groom, and colorful festive cushions.',
                'description' => 'A lively and photogenic Haldi decoration setup in Mairwa. Designed with traditional floral curtains, fresh sunflower accents, brass vessels, and comfortable seating for family and friends.',
                'video_path' => null,
                'thumbnail_path' => null,
                'video_type' => 'reel',
                'event_type' => 'haldi',
                'category_id' => $categories->get('haldi')?->id,
                'location' => 'Mairwa, Bihar',
                'duration' => '00:15',
                'is_featured' => true,
                'is_homepage' => true,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'Floral Jhula & Courtyard Mehendi Setup',
                'slug' => 'floral-jhula-courtyard-mehendi-setup',
                'short_description' => 'Decorated wooden jhula, pastel pink & green drapes, and traditional Rajasthani umbrella accents in Chapra.',
                'description' => 'Complete Mehendi celebration ambiance crafted with festive floral swings, colorful tent canopies, genda phool torans, and cozy floor seating with embroidered bolsters.',
                'video_path' => null,
                'thumbnail_path' => null,
                'video_type' => 'reel',
                'event_type' => 'mehendi',
                'category_id' => $categories->get('mehendi')?->id,
                'location' => 'Chapra, Bihar',
                'duration' => '00:20',
                'is_featured' => true,
                'is_homepage' => true,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'title' => 'Royal Sangeet Night Musical Stage & Lighting',
                'slug' => 'royal-sangeet-night-musical-stage-lighting',
                'short_description' => 'Dynamic stage lighting, LED backdrop wall, acoustic decor, and family dance performance area.',
                'description' => 'High-energy Sangeet ceremony stage setup featuring moving heads, warm truss lights, floral side wings, and a custom royal backdrop in Patna.',
                'video_path' => null,
                'thumbnail_path' => null,
                'video_type' => 'event_highlight',
                'event_type' => 'sangeet',
                'category_id' => $categories->get('sangeet')?->id,
                'location' => 'Patna, Bihar',
                'duration' => '00:22',
                'is_featured' => false,
                'is_homepage' => true,
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'title' => 'Grand Floral Reception Stage & Guest Swagat Gate',
                'slug' => 'grand-floral-reception-stage-guest-swagat-gate',
                'short_description' => 'Luxury royal palace theme reception backdrop with white florals, golden pillars, and chandelier canopies.',
                'description' => 'An opulent wedding reception venue transformation in Siwan with an entrance tunnel illuminated by fairy lights, royal flower wall backdrop, and luxurious stage lounge.',
                'video_path' => null,
                'thumbnail_path' => null,
                'video_type' => 'portfolio',
                'event_type' => 'reception',
                'category_id' => $categories->get('reception')?->id,
                'location' => 'Siwan, Bihar',
                'duration' => '00:28',
                'is_featured' => true,
                'is_homepage' => true,
                'is_active' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($videos as $videoData) {
            WeddingVideo::updateOrCreate(['slug' => $videoData['slug']], $videoData);
        }

        
    }
}

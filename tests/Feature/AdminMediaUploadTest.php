<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Decoration;
use App\Models\Package;
use App\Models\Offer;
use App\Models\WeddingVideo;
use App\Models\GalleryItem;
use App\Models\GalleryCategory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminMediaUploadTest extends TestCase
{
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::firstOrCreate(
            ['email' => 'admin_upload_test@adityautsav.test'],
            [
                'name' => 'Admin Upload Tester',
                'password' => Hash::make('password123'),
                'role' => 'super_admin',
                'is_active' => true,
            ]
        );
    }

    /**
     * Test decoration store with uploaded primary image and gallery images.
     */
    public function test_decoration_store_with_file_upload()
    {
        $category = Category::first() ?? Category::create([
            'name' => 'Test Stage Category',
            'slug' => 'test-stage-category-' . Str::random(5),
            'is_active' => true,
        ]);

        $primaryImage = UploadedFile::fake()->image('wedding_stage.jpg', 800, 600);
        $galleryImage1 = UploadedFile::fake()->image('detail_1.jpg', 600, 400);

        $payload = [
            'name' => 'Grand Royal Mandap Test ' . Str::random(4),
            'category_id' => $category->id,
            'price' => 75000,
            'is_featured' => true,
            'is_active' => true,
            'image' => $primaryImage,
            'gallery_images' => [$galleryImage1],
            'description' => 'A grand mandap setup with floral work.',
        ];

        $response = $this->actingAs($this->admin)->post('/admin/decorations', $payload);
        $decoration = Decoration::where('name', $payload['name'])->first();
        $this->assertNotNull($decoration);
        $response->assertRedirect(route('admin.decorations.edit', $decoration->id));
        $this->assertNotNull($decoration->primary_image);
        $this->assertStringStartsWith('uploads/decorations/', $decoration->primary_image);
        $this->assertTrue(File::exists(public_path($decoration->primary_image)));

        // Clean up created file
        if (File::exists(public_path($decoration->primary_image))) {
            File::delete(public_path($decoration->primary_image));
        }
    }

    /**
     * Test category store and edit with file upload & image preservation.
     */
    public function test_category_file_upload_and_preservation()
    {
        $image = UploadedFile::fake()->image('category_banner.jpg', 600, 400);

        $payload = [
            'name' => 'Haldi Decor Test ' . Str::random(4),
            'description' => 'Bright yellow marigold decor.',
            'image' => $image,
            'is_active' => true,
        ];

        $response = $this->actingAs($this->admin)->post('/admin/categories', $payload);
        $response->assertRedirect('/admin/categories');

        $category = Category::where('name', $payload['name'])->first();
        $this->assertNotNull($category);
        $this->assertNotNull($category->image_url);
        $this->assertStringStartsWith('uploads/categories/', $category->image_url);
        $savedPath = $category->image_url;
        $this->assertTrue(File::exists(public_path($savedPath)));

        // Update category WITHOUT providing a new image -> existing image must be preserved
        $updatePayload = [
            'name' => $category->name . ' Updated',
            'description' => 'Updated description.',
            'is_active' => true,
        ];

        $updateResponse = $this->actingAs($this->admin)->put("/admin/categories/{$category->id}", $updatePayload);
        $updateResponse->assertRedirect('/admin/categories');

        $category->refresh();
        $this->assertEquals($savedPath, $category->image_url);

        // Clean up
        if (File::exists(public_path($savedPath))) {
            File::delete(public_path($savedPath));
        }
    }

    /**
     * Test package store with featured image upload.
     */
    public function test_package_file_upload()
    {
        $image = UploadedFile::fake()->image('package_deluxe.jpg', 600, 400);

        $payload = [
            'name' => 'Royal Emerald Package ' . Str::random(4),
            'tier' => 'Luxury',
            'base_price' => 120000,
            'is_active' => true,
            'image' => $image,
            'description' => 'Complete wedding luxury package.',
        ];

        $response = $this->actingAs($this->admin)->post('/admin/packages', $payload);
        $response->assertRedirect('/admin/packages');

        $package = Package::where('name', $payload['name'])->first();
        $this->assertNotNull($package);
        $this->assertNotNull($package->image);
        $this->assertStringStartsWith('uploads/packages/', $package->image);
        $this->assertTrue(File::exists(public_path($package->image)));

        // Clean up
        if (File::exists(public_path($package->image))) {
            File::delete(public_path($package->image));
        }
    }

    /**
     * Test offer banner file upload.
     */
    public function test_offer_banner_upload()
    {
        $banner = UploadedFile::fake()->image('festive_banner.jpg', 1200, 400);

        $payload = [
            'title' => 'Shubh Vivah Offer ' . Str::random(4),
            'discount_type' => 'percentage',
            'discount_value' => 15,
            'valid_from' => '2027-01-01',
            'valid_until' => '2027-03-31',
            'image' => $banner,
            'is_active' => true,
        ];

        $response = $this->actingAs($this->admin)->post('/admin/offers', $payload);
        $response->assertRedirect('/admin/offers');

        $offer = Offer::where('title', $payload['title'])->first();
        $this->assertNotNull($offer);
        $this->assertNotNull($offer->image);
        $this->assertStringStartsWith('uploads/offers/', $offer->image);
        $this->assertTrue(File::exists(public_path($offer->image)));

        // Clean up
        if (File::exists(public_path($offer->image))) {
            File::delete(public_path($offer->image));
        }
    }

    /**
     * Test gallery item upload.
     */
    public function test_gallery_file_upload()
    {
        $galCat = GalleryCategory::first() ?? GalleryCategory::create([
            'name' => 'Stage Decorations',
            'slug' => 'stage-decorations-' . Str::random(4),
            'is_active' => true,
        ]);

        $photo = UploadedFile::fake()->image('reception_stage.jpg', 900, 600);

        $payload = [
            'title' => 'Grand Entrance Gateway ' . Str::random(4),
            'gallery_category_id' => $galCat->id,
            'location' => 'Patna, Bihar',
            'event_type' => 'Reception',
            'image' => $photo,
            'is_active' => true,
        ];

        $response = $this->actingAs($this->admin)->post('/admin/gallery', $payload);
        $response->assertRedirect('/admin/gallery');

        $item = GalleryItem::where('title', $payload['title'])->first();
        $this->assertNotNull($item);
        $this->assertNotNull($item->image_path);
        $this->assertStringStartsWith('uploads/gallery/', $item->image_path);
        $this->assertTrue(File::exists(public_path($item->image_path)));

        // Clean up
        if (File::exists(public_path($item->image_path))) {
            File::delete(public_path($item->image_path));
        }
    }

    /**
     * Test video and thumbnail upload.
     */
    public function test_wedding_video_upload()
    {
        $videoFile = UploadedFile::fake()->create('cinematic_highlight.mp4', 1024, 'video/mp4');
        $thumbnail = UploadedFile::fake()->image('video_thumb.jpg', 640, 360);

        $payload = [
            'title' => 'Patna Palace Royal Highlight ' . Str::random(4),
            'video_type' => 'event_highlight',
            'event_type' => 'sangeet',
            'location' => 'Patna, Bihar',
            'video_file' => $videoFile,
            'thumbnail_file' => $thumbnail,
            'is_active' => true,
        ];

        $response = $this->actingAs($this->admin)->post('/admin/videos', $payload);
        $response->assertRedirect('/admin/videos');

        $video = WeddingVideo::where('title', $payload['title'])->first();
        $this->assertNotNull($video);
        $this->assertNotNull($video->video_path);
        $this->assertNotNull($video->thumbnail_path);
        $this->assertStringStartsWith('uploads/videos/', $video->video_path);
        $this->assertStringStartsWith('uploads/video-thumbnails/', $video->thumbnail_path);
        $this->assertTrue(File::exists(public_path($video->video_path)));
        $this->assertTrue(File::exists(public_path($video->thumbnail_path)));

        // Clean up
        if (File::exists(public_path($video->video_path))) {
            File::delete(public_path($video->video_path));
        }
        if (File::exists(public_path($video->thumbnail_path))) {
            File::delete(public_path($video->thumbnail_path));
        }
    }

    /**
     * Test validation rejects oversized image files (> 5MB).
     */
    public function test_image_file_validation_rejects_oversized_file()
    {
        // 6MB image (over 5120KB limit)
        $oversizedImage = UploadedFile::fake()->create('huge_image.jpg', 6000, 'image/jpeg');

        $category = Category::first() ?? Category::create([
            'name' => 'Validation Category',
            'slug' => 'val-cat-' . Str::random(5),
            'is_active' => true,
        ]);

        $payload = [
            'name' => 'Huge Decoration Test ' . Str::random(4),
            'category_id' => $category->id,
            'description' => 'Test description',
            'price' => 50000,
            'image' => $oversizedImage,
        ];

        $response = $this->actingAs($this->admin)->post('/admin/decorations', $payload);
        $response->assertSessionHasErrors(['image']);
    }

    /**
     * Test admin media library index and file listing.
     */
    public function test_admin_media_library_page_and_folder_filtering()
    {
        $response = $this->actingAs($this->admin)->get('/admin/media');
        $response->assertStatus(200);
        $response->assertSee('Media Library');
        $response->assertSee('Total Files');

        // Test with folder filter
        $responseFiltered = $this->actingAs($this->admin)->get('/admin/media?folder=decorations');
        $responseFiltered->assertStatus(200);
    }
}

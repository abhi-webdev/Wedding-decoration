<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('tagline')->nullable();
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->string('icon_name')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_featured')->default(true);
            $table->timestamps();
        });

        Schema::create('decorations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('tagline')->nullable();
            $table->text('description')->nullable();
            $table->string('primary_image');
            $table->string('location')->default('Siwan, Bihar');
            $table->unsignedInteger('starting_price')->default(50000);
            $table->string('price_unit')->default('starting from');
            $table->decimal('rating', 2, 1)->default(4.9);
            $table->unsignedInteger('reviews_count')->default(18);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_trending')->default(false);
            $table->boolean('is_available')->default(true);
            $table->string('color_theme')->nullable();
            $table->json('features')->nullable();
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });

        Schema::create('decoration_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('decoration_id')->constrained('decorations')->onDelete('cascade');
            $table->string('image_url');
            $table->string('caption')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });

        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('badge')->nullable();
            $table->string('tagline')->nullable();
            $table->text('description')->nullable();
            $table->json('included_ceremonies')->nullable();
            $table->json('highlights')->nullable();
            $table->unsignedInteger('starting_price');
            $table->string('image_url')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });

        Schema::create('service_areas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('state')->default('Bihar');
            $table->string('district')->nullable();
            $table->string('category')->default('Bihar Core'); // 'Bihar Core', 'Bihar Extended', 'Nearby Uttar Pradesh'
            $table->string('status_note')->default('Full Team Available');
            $table->boolean('is_primary')->default(false);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('city');
            $table->string('event_type');
            $table->unsignedTinyInteger('rating')->default(5);
            $table->text('review_text');
            $table->string('avatar_initials')->nullable();
            $table->string('event_date_formatted')->nullable();
            $table->boolean('is_featured')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });

        Schema::create('gallery_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category'); // 'Jaimala', 'Mandap', 'Haldi', 'Mehendi', 'Sangeet', 'Reception', 'Baraat', 'Entrance'
            $table->string('image_url');
            $table->string('location')->default('Siwan, Bihar');
            $table->string('caption')->nullable();
            $table->boolean('is_featured')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });

        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('highlight_badge')->nullable();
            $table->string('discount_text')->nullable();
            $table->string('valid_till')->nullable();
            $table->string('cta_text')->default('Get a Custom Quote');
            $table->string('cta_link')->default('/quote');
            $table->string('image_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->string('category')->default('General');
            $table->boolean('is_featured')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });

        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('site_settings');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('offers');
        Schema::dropIfExists('gallery_items');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('service_areas');
        Schema::dropIfExists('packages');
        Schema::dropIfExists('decoration_images');
        Schema::dropIfExists('decorations');
        Schema::dropIfExists('categories');
    }
};

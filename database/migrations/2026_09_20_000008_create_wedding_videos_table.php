<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for Phase 8 Wedding Videos & Reels.
     */
    public function up(): void
    {
        if (!Schema::hasTable('wedding_videos')) {
            Schema::create('wedding_videos', function (Blueprint $table) {
                $table->id();
                $table->string('title', 255);
                $table->string('slug', 255)->unique();
                $table->text('short_description')->nullable();
                $table->longText('description')->nullable();
                $table->string('video_path', 500)->nullable();
                $table->string('thumbnail_path', 500)->nullable();
                $table->string('video_type', 50)->default('reel'); // reel, short, event_highlight, portfolio, behind_the_scenes
                $table->string('event_type', 50)->default('general'); // jaimala, mandap, haldi, mehendi, sangeet, reception, general
                $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
                $table->string('location', 150)->default('Siwan, Bihar');
                $table->string('duration', 20)->default('00:20');
                $table->boolean('is_featured')->default(false);
                $table->boolean('is_homepage')->default(true);
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->unsignedBigInteger('views_count')->default(0);
                $table->timestamps();

                $table->index('slug');
                $table->index('video_type');
                $table->index('event_type');
                $table->index('is_homepage');
                $table->index('is_active');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wedding_videos');
    }
};

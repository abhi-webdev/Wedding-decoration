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
        // 1. Extend decorations table with catalog filter & detail fields
        Schema::table('decorations', function (Blueprint $table) {
            if (!Schema::hasColumn('decorations', 'base_price')) {
                $table->decimal('base_price', 10, 2)->nullable()->after('starting_price');
            }
            if (!Schema::hasColumn('decorations', 'discount_price')) {
                $table->decimal('discount_price', 10, 2)->nullable()->after('base_price');
            }
            if (!Schema::hasColumn('decorations', 'style')) {
                $table->string('style')->nullable()->default('Traditional')->after('color_theme');
            }
            if (!Schema::hasColumn('decorations', 'primary_color')) {
                $table->string('primary_color')->nullable()->after('style');
            }
            if (!Schema::hasColumn('decorations', 'guest_capacity')) {
                $table->string('guest_capacity')->nullable()->default('200-500 Guests')->after('primary_color');
            }
            if (!Schema::hasColumn('decorations', 'setup_time')) {
                $table->string('setup_time')->nullable()->default('4-6 Hours')->after('guest_capacity');
            }
            if (!Schema::hasColumn('decorations', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('is_available');
            }
            if (!Schema::hasColumn('decorations', 'short_description')) {
                $table->text('short_description')->nullable()->after('tagline');
            }
        });

        // 2. Extend decoration_images with alt_text
        Schema::table('decoration_images', function (Blueprint $table) {
            if (!Schema::hasColumn('decoration_images', 'alt_text')) {
                $table->string('alt_text')->nullable()->after('caption');
            }
        });

        // 3. Create decoration_items table
        if (!Schema::hasTable('decoration_items')) {
            Schema::create('decoration_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('decoration_id')->constrained('decorations')->onDelete('cascade');
                $table->string('name');
                $table->text('description')->nullable();
                $table->integer('quantity')->default(1);
                $table->timestamps();
            });
        }

        // 4. Create addons table
        if (!Schema::hasTable('addons')) {
            Schema::create('addons', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->decimal('price', 10, 2)->default(0.00);
                $table->string('image')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 5. Create decoration_addons pivot table
        if (!Schema::hasTable('decoration_addons')) {
            Schema::create('decoration_addons', function (Blueprint $table) {
                $table->id();
                $table->foreignId('decoration_id')->constrained('decorations')->onDelete('cascade');
                $table->foreignId('addon_id')->constrained('addons')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('decoration_addons');
        Schema::dropIfExists('addons');
        Schema::dropIfExists('decoration_items');

        Schema::table('decoration_images', function (Blueprint $table) {
            if (Schema::hasColumn('decoration_images', 'alt_text')) {
                $table->dropColumn('alt_text');
            }
        });

        Schema::table('decorations', function (Blueprint $table) {
            $cols = ['base_price', 'discount_price', 'style', 'primary_color', 'guest_capacity', 'setup_time', 'is_active', 'short_description'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('decorations', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

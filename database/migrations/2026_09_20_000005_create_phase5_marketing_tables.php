<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for Phase 5 Marketing & Public Content.
     */
    public function up()
    {
        // 1. Extend packages table if needed
        Schema::table('packages', function (Blueprint $table) {
            if (!Schema::hasColumn('packages', 'short_description')) {
                $table->text('short_description')->nullable()->after('tagline');
            }
            if (!Schema::hasColumn('packages', 'base_price')) {
                $table->decimal('base_price', 12, 2)->default(50000.00)->after('starting_price');
            }
            if (!Schema::hasColumn('packages', 'discount_price')) {
                $table->decimal('discount_price', 12, 2)->nullable()->after('base_price');
            }
            if (!Schema::hasColumn('packages', 'image')) {
                $table->string('image')->nullable()->after('image_url');
            }
            if (!Schema::hasColumn('packages', 'guest_capacity')) {
                $table->string('guest_capacity')->nullable()->after('highlights');
            }
            if (!Schema::hasColumn('packages', 'duration')) {
                $table->string('duration')->nullable()->after('guest_capacity');
            }
            if (!Schema::hasColumn('packages', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('is_featured');
            }
            if (!Schema::hasColumn('packages', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('display_order');
            }
        });

        // 2. Pivot table: package_decorations
        if (!Schema::hasTable('package_decorations')) {
            Schema::create('package_decorations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('package_id')->constrained('packages')->onDelete('cascade');
                $table->foreignId('decoration_id')->constrained('decorations')->onDelete('cascade');
                $table->integer('quantity')->default(1);
                $table->timestamps();
            });
        }

        // 3. Extend offers table
        Schema::table('offers', function (Blueprint $table) {
            if (!Schema::hasColumn('offers', 'slug')) {
                $table->string('slug')->nullable()->after('title');
            }
            if (!Schema::hasColumn('offers', 'short_description')) {
                $table->text('short_description')->nullable()->after('subtitle');
            }
            if (!Schema::hasColumn('offers', 'image')) {
                $table->string('image')->nullable()->after('image_url');
            }
            if (!Schema::hasColumn('offers', 'discount_type')) {
                $table->string('discount_type')->default('percentage')->after('discount_text'); // 'percentage', 'fixed'
            }
            if (!Schema::hasColumn('offers', 'discount_value')) {
                $table->decimal('discount_value', 12, 2)->default(10.00)->after('discount_type');
            }
            if (!Schema::hasColumn('offers', 'coupon_code')) {
                $table->string('coupon_code')->nullable()->after('discount_value');
            }
            if (!Schema::hasColumn('offers', 'valid_from')) {
                $table->date('valid_from')->nullable()->after('valid_till');
            }
            if (!Schema::hasColumn('offers', 'valid_until')) {
                $table->date('valid_until')->nullable()->after('valid_from');
            }
            if (!Schema::hasColumn('offers', 'terms')) {
                $table->text('terms')->nullable()->after('valid_until');
            }
            if (!Schema::hasColumn('offers', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('terms');
            }
            if (!Schema::hasColumn('offers', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('is_active');
            }
        });

        // 4. Gallery Categories table
        if (!Schema::hasTable('gallery_categories')) {
            Schema::create('gallery_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 5. Extend gallery_items table
        Schema::table('gallery_items', function (Blueprint $table) {
            if (!Schema::hasColumn('gallery_items', 'gallery_category_id')) {
                $table->foreignId('gallery_category_id')->nullable()->after('id')->constrained('gallery_categories')->nullOnDelete();
            }
            if (!Schema::hasColumn('gallery_items', 'slug')) {
                $table->string('slug')->nullable()->after('title');
            }
            if (!Schema::hasColumn('gallery_items', 'image')) {
                $table->string('image')->nullable()->after('image_url');
            }
            if (!Schema::hasColumn('gallery_items', 'description')) {
                $table->text('description')->nullable()->after('caption');
            }
            if (!Schema::hasColumn('gallery_items', 'event_type')) {
                $table->string('event_type')->nullable()->after('category');
            }
            if (!Schema::hasColumn('gallery_items', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('is_featured');
            }
            if (!Schema::hasColumn('gallery_items', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('display_order');
            }
        });

        // 6. Quote Requests table
        if (!Schema::hasTable('quote_requests')) {
            Schema::create('quote_requests', function (Blueprint $table) {
                $table->id();
                $table->string('quote_reference')->unique();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('customer_name');
                $table->string('customer_phone');
                $table->string('customer_email')->nullable();
                $table->string('event_type');
                $table->date('event_date');
                $table->unsignedInteger('guest_count')->nullable();
                $table->string('state')->default('Bihar');
                $table->string('city');
                $table->string('locality')->nullable();
                $table->string('venue_name')->nullable();
                $table->json('decoration_preference')->nullable();
                $table->string('budget_range')->nullable();
                $table->text('special_requirements')->nullable();
                $table->string('reference_image')->nullable();
                $table->string('status')->default('new'); // 'new', 'contacted', 'quoted', 'converted', 'closed'
                $table->timestamps();
            });
        }

        // 7. Contact Messages table
        if (!Schema::hasTable('contact_messages')) {
            Schema::create('contact_messages', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->string('subject')->nullable();
                $table->text('message');
                $table->string('status')->default('new'); // 'new', 'read', 'replied', 'closed'
                $table->timestamps();
            });
        }

        // 8. Extend faqs table
        Schema::table('faqs', function (Blueprint $table) {
            if (!Schema::hasColumn('faqs', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('is_featured');
            }
            if (!Schema::hasColumn('faqs', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('display_order');
            }
        });

        // 9. Extend service_areas table
        Schema::table('service_areas', function (Blueprint $table) {
            if (!Schema::hasColumn('service_areas', 'slug')) {
                $table->string('slug')->nullable()->after('name');
            }
            if (!Schema::hasColumn('service_areas', 'description')) {
                $table->text('description')->nullable()->after('district');
            }
            if (!Schema::hasColumn('service_areas', 'image')) {
                $table->string('image')->nullable()->after('description');
            }
            if (!Schema::hasColumn('service_areas', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('is_primary');
            }
            if (!Schema::hasColumn('service_areas', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('display_order');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('quote_requests');
        Schema::dropIfExists('package_decorations');
        Schema::dropIfExists('gallery_categories');
    }
};

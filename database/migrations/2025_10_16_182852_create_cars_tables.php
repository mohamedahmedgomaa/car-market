<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('cars')) {
            Schema::create('cars', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('seller_id');
                $table->unsignedBigInteger('brand_id');
                $table->unsignedBigInteger('model_id');
                $table->unsignedBigInteger('city_id');
                $table->unsignedBigInteger('governorate_id')->nullable();
                $table->unsignedBigInteger('country_id');
                $table->enum('type', ['car', 'motorcycle'])->default('car');
                $table->json('title');
                $table->json('description');
                $table->decimal('price', 10, 2);
                $table->decimal('old_price', 12, 2)->nullable();
                $table->decimal('sold_price', 12, 2)->nullable();
                $table->timestamp('sold_at')->nullable();
                $table->string('currency', 10)->default('EGP');
                $table->year('year');
                $table->integer('mileage');
                $table->enum('transmission', ['manual', 'automatic']);
                $table->enum('fuel_type', ['petrol', 'diesel', 'electric', 'hybrid', 'mild_hybrid', 'reev']);
                $table->enum('drivetrain', ['fwd', 'rwd', 'awd', '4wd']);
                $table->string('color')->nullable();
                $table->enum('condition', ['new', 'used']);
                $table->string('horsepower')->nullable();
                $table->string('cylinders')->nullable();
                $table->string('acceleration')->nullable();
                $table->string('weight')->nullable();
                $table->string('torque')->nullable();
                $table->string('engine_capacity')->nullable();
                $table->string('phone_number')->nullable();
                $table->string('whatsapp_number')->nullable();
                $table->string('youtube_url', 2048)->nullable();
                $table->string('instagram_url', 2048)->nullable();
                $table->string('model_3d_url', 500)->nullable();
                $table->json('exterior_360_images')->nullable();
                $table->string('interior_360_url', 2048)->nullable();
                $table->boolean('is_best_deal')->default(false);
                $table->boolean('is_import')->default(false);
                $table->boolean('is_featured')->default(false);
                $table->boolean('show_on_home')->default(false);
                $table->boolean('is_global_ad')->default(false);
                $table->timestamp('ad_expiry')->nullable();
                $table->decimal('featured_fee', 10, 2)->nullable();
                $table->enum('status', ['pending', 'approved', 'rejected', 'sold', 'archived'])->default('pending');
                $table->text('rejection_reason')->nullable();
                $table->unsignedBigInteger('views_count')->default(0);
                $table->unsignedBigInteger('whatsapp_clicks_count')->default(0);
                $table->unsignedBigInteger('phone_clicks_count')->default(0);
                $table->unsignedBigInteger('shares_count')->default(0);
                $table->timestamps();

                $table->foreign('governorate_id')->references('id')->on('governorates')->onDelete('set null');

                $table->index('created_at', 'idx_cars_created_at');
                $table->index('status', 'idx_cars_status');
                $table->index(['status', 'created_at'], 'idx_cars_status_created_at');
                $table->index(['brand_id', 'status'], 'idx_cars_brand_status');
                $table->index(['model_id', 'status'], 'idx_cars_model_status');
                $table->index(['city_id', 'status'], 'idx_cars_city_status');
                $table->index(['seller_id', 'status'], 'idx_cars_seller_status');
                $table->index('price', 'idx_cars_price');
                $table->index('year', 'idx_cars_year');
                $table->index('mileage', 'idx_cars_mileage');
                $table->index(['is_featured', 'status'], 'idx_cars_featured_status');
                $table->index(['is_best_deal', 'status'], 'idx_cars_best_deal_status');
                $table->index(['show_on_home', 'status'], 'idx_cars_show_on_home_status');
                $table->index(['type', 'status', 'show_on_home', 'created_at'], 'idx_cars_type_status_home');
                $table->index(['status', 'is_best_deal', 'price'], 'idx_cars_status_best_deal_price');
                $table->index(['status', 'price'], 'idx_cars_status_price');
                $table->index(['type', 'status', 'created_at'], 'idx_cars_type_status_created_at');
                $table->index(['type', 'status', 'price'], 'idx_cars_type_status_price');
                $table->index(['type', 'status', 'brand_id'], 'idx_cars_type_status_brand');
                $table->index(['type', 'status', 'is_best_deal'], 'idx_cars_type_status_best_deal');
            });
        }

        if (! Schema::hasTable('car_features')) {
            Schema::create('car_features', function (Blueprint $table) {
                $table->id();
                $table->json('name');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('car_feature_pivots')) {
            Schema::create('car_feature_pivots', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('car_id');
                $table->unsignedBigInteger('car_feature_id');
                $table->timestamps();

                $table->index(['car_id', 'car_feature_id'], 'idx_feature_car_pivot');
                $table->index('car_feature_id', 'idx_feature_pivot_feature_id');
            });
        }

        if (! Schema::hasTable('car_images')) {
            Schema::create('car_images', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('car_id');
                $table->string('path');
                $table->boolean('is_main')->default(false);
                $table->timestamps();

                $table->index(['car_id', 'is_main'], 'idx_car_images_car_main');
            });
        }

        if (! Schema::hasTable('favorite_cars')) {
            Schema::create('favorite_cars', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('car_id');
                $table->timestamps();

                $table->index(['car_id', 'user_id'], 'idx_fav_car_user');
                $table->index('user_id', 'idx_fav_user_id');
            });
        }

        if (! Schema::hasTable('car_price_histories')) {
            Schema::create('car_price_histories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('car_id')->index();
                $table->unsignedBigInteger('seller_id')->nullable()->index();
                $table->decimal('old_price', 12, 2);
                $table->decimal('new_price', 12, 2);
                $table->decimal('price_difference', 12, 2);
                $table->decimal('change_percentage', 6, 2)->default(0);
                $table->string('currency', 10)->default('EGP');
                $table->string('changed_by_type', 30)->default('seller');
                $table->unsignedBigInteger('changed_by_id')->nullable();
                $table->timestamps();

                $table->index(['car_id', 'created_at']);
                $table->index(['seller_id', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('car_price_histories');
        Schema::dropIfExists('favorite_cars');
        Schema::dropIfExists('car_images');
        Schema::dropIfExists('car_feature_pivots');
        Schema::dropIfExists('car_features');
        Schema::dropIfExists('cars');
    }
};

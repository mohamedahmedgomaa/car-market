<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
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
        });

        Schema::table('car_images', function (Blueprint $table) {
            $table->index(['car_id', 'is_main'], 'idx_car_images_car_main');
        });

        Schema::table('banners', function (Blueprint $table) {
            $table->index(['type', 'is_active'], 'idx_banners_type_active');
        });

        Schema::table('favorite_cars', function (Blueprint $table) {
            $table->index(['car_id', 'user_id'], 'idx_fav_car_user');
            $table->index('user_id', 'idx_fav_user_id');
        });

        Schema::table('car_feature_pivots', function (Blueprint $table) {
            $table->index(['car_id', 'car_feature_id'], 'idx_feature_car_pivot');
            $table->index('car_feature_id', 'idx_feature_pivot_feature_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropIndex('idx_cars_created_at');
            $table->dropIndex('idx_cars_status');
            $table->dropIndex('idx_cars_status_created_at');
            $table->dropIndex('idx_cars_brand_status');
            $table->dropIndex('idx_cars_model_status');
            $table->dropIndex('idx_cars_city_status');
            $table->dropIndex('idx_cars_seller_status');
            $table->dropIndex('idx_cars_price');
            $table->dropIndex('idx_cars_year');
            $table->dropIndex('idx_cars_mileage');
            $table->dropIndex('idx_cars_featured_status');
            $table->dropIndex('idx_cars_best_deal_status');
            $table->dropIndex('idx_cars_show_on_home_status');
            $table->dropIndex('idx_cars_type_status_home');
            $table->dropIndex('idx_cars_status_best_deal_price');
            $table->dropIndex('idx_cars_status_price');
        });

        Schema::table('car_images', function (Blueprint $table) {
            $table->dropIndex('idx_car_images_car_main');
        });

        Schema::table('banners', function (Blueprint $table) {
            $table->dropIndex('idx_banners_type_active');
        });

        Schema::table('favorite_cars', function (Blueprint $table) {
            $table->dropIndex('idx_fav_car_user');
            $table->dropIndex('idx_fav_user_id');
        });

        Schema::table('car_feature_pivots', function (Blueprint $table) {
            $table->dropIndex('idx_feature_car_pivot');
            $table->dropIndex('idx_feature_pivot_feature_id');
        });
    }
};

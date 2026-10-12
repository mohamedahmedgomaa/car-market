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
            $table->index(['type', 'status', 'created_at'], 'idx_cars_type_status_created_at');
            $table->index(['type', 'status', 'price'], 'idx_cars_type_status_price');
            $table->index(['type', 'status', 'brand_id'], 'idx_cars_type_status_brand');
            $table->index(['type', 'status', 'is_best_deal'], 'idx_cars_type_status_best_deal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropIndex('idx_cars_type_status_created_at');
            $table->dropIndex('idx_cars_type_status_price');
            $table->dropIndex('idx_cars_type_status_brand');
            $table->dropIndex('idx_cars_type_status_best_deal');
        });
    }
};

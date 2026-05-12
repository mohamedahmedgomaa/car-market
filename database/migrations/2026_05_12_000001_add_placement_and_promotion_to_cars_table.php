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
        Schema::table('cars', function (Blueprint $row) {
            $row->boolean('is_best_deal')->default(false)->after('whatsapp_number');
            $row->boolean('is_import')->default(false)->after('is_best_deal');
            $row->boolean('is_featured')->default(false)->after('is_import');
            $row->boolean('show_on_home')->default(false)->after('is_featured');
            $row->boolean('is_global_ad')->default(false)->after('show_on_home');
            $row->timestamp('ad_expiry')->nullable()->after('is_global_ad');
            $row->decimal('featured_fee', 10, 2)->nullable()->after('ad_expiry');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $row) {
            $row->dropColumn(['is_best_deal', 'is_import', 'is_featured', 'show_on_home', 'is_global_ad', 'ad_expiry', 'featured_fee']);
        });
    }
};

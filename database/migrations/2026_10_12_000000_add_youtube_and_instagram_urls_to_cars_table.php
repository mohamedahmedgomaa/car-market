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
            if (!Schema::hasColumn('cars', 'youtube_url')) {
                $table->string('youtube_url', 2048)->nullable()->after('whatsapp_number');
            }
            if (!Schema::hasColumn('cars', 'instagram_url')) {
                $table->string('instagram_url', 2048)->nullable()->after('youtube_url');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            if (Schema::hasColumn('cars', 'youtube_url')) {
                $table->dropColumn('youtube_url');
            }
            if (Schema::hasColumn('cars', 'instagram_url')) {
                $table->dropColumn('instagram_url');
            }
        });
    }
};

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
        if (Schema::hasTable('cars')) {
            Schema::table('cars', function (Blueprint $table) {
                if (!Schema::hasColumn('cars', 'exterior_360_images')) {
                    $table->json('exterior_360_images')->nullable()->after('model_3d_url');
                }
                if (!Schema::hasColumn('cars', 'interior_360_url')) {
                    $table->string('interior_360_url', 2048)->nullable()->after('exterior_360_images');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('cars')) {
            Schema::table('cars', function (Blueprint $table) {
                if (Schema::hasColumn('cars', 'interior_360_url')) {
                    $table->dropColumn('interior_360_url');
                }
                if (Schema::hasColumn('cars', 'exterior_360_images')) {
                    $table->dropColumn('exterior_360_images');
                }
            });
        }
    }
};

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
        if (Schema::hasTable('models') && !Schema::hasColumn('models', 'model_3d_url')) {
            Schema::table('models', function (Blueprint $table) {
                $table->string('model_3d_url', 500)->nullable()->after('type');
            });
        }

        if (Schema::hasTable('cars') && !Schema::hasColumn('cars', 'model_3d_url')) {
            Schema::table('cars', function (Blueprint $table) {
                $table->string('model_3d_url', 500)->nullable()->after('instagram_url');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('models') && Schema::hasColumn('models', 'model_3d_url')) {
            Schema::table('models', function (Blueprint $table) {
                $table->dropColumn('model_3d_url');
            });
        }

        if (Schema::hasTable('cars') && Schema::hasColumn('cars', 'model_3d_url')) {
            Schema::table('cars', function (Blueprint $table) {
                $table->dropColumn('model_3d_url');
            });
        }
    }
};

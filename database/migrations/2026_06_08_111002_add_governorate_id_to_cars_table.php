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
        if (!Schema::hasColumn('cars', 'governorate_id')) {
            Schema::table('cars', function (Blueprint $table) {
                $table->unsignedBigInteger('governorate_id')->nullable()->after('city_id');
                $table->foreign('governorate_id')->references('id')->on('governorates')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('cars', 'governorate_id')) {
            Schema::table('cars', function (Blueprint $table) {
                $table->dropForeign(['governorate_id']);
                $table->dropColumn('governorate_id');
            });
        }
    }
};

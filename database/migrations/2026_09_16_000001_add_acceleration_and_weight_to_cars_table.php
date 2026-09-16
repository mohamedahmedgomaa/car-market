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
        if (!Schema::hasColumn('cars', 'acceleration')) {
            Schema::table('cars', function (Blueprint $table) {
                $table->string('acceleration')->nullable()->after('cylinders');
            });
        }

        if (!Schema::hasColumn('cars', 'weight')) {
            Schema::table('cars', function (Blueprint $table) {
                $table->string('weight')->nullable()->after('acceleration');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            if (Schema::hasColumn('cars', 'acceleration')) {
                $table->dropColumn('acceleration');
            }
            if (Schema::hasColumn('cars', 'weight')) {
                $table->dropColumn('weight');
            }
        });
    }
};

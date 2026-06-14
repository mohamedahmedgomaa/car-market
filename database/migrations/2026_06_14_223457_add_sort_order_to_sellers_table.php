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
        if (!Schema::hasColumn('sellers', 'sort_order')) {
            Schema::table('sellers', function (Blueprint $table) {
                $table->integer('sort_order')->default(0)->after('is_active');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('sellers', 'sort_order')) {
            Schema::table('sellers', function (Blueprint $table) {
                $table->dropColumn('sort_order');
            });
        }
    }
};

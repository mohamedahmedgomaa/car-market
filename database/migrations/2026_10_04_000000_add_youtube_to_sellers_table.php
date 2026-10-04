<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sellers', function (Blueprint $table) {
            if (!Schema::hasColumn('sellers', 'youtube')) {
                $table->string('youtube')->nullable()->after('instagram');
            }
            $table->text('map_url')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('sellers', function (Blueprint $table) {
            if (Schema::hasColumn('sellers', 'youtube')) {
                $table->dropColumn('youtube');
            }
        });
    }
};

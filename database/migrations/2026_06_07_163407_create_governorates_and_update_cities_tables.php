<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('governorates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('country_id');
            $table->json('name');
            $table->timestamps();

            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->unsignedBigInteger('governorate_id')->nullable()->after('country_id');
            $table->foreign('governorate_id')->references('id')->on('governorates')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->dropForeign(['governorate_id']);
            $table->dropColumn('governorate_id');
        });

        Schema::dropIfExists('governorates');
    }
};

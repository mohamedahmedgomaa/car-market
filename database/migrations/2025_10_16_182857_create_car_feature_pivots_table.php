<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_feature_pivots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('car_id');
            $table->unsignedBigInteger('car_feature_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_feature_pivots');
    }
};

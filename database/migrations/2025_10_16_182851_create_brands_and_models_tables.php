<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('brands')) {
            Schema::create('brands', function (Blueprint $table) {
                $table->id();
                $table->json('name');
                $table->enum('type', ['car', 'motorcycle'])->default('car');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('models')) {
            Schema::create('models', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('brand_id');
                $table->json('name');
                $table->enum('type', ['car', 'motorcycle'])->default('car');
                $table->string('model_3d_url', 500)->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('models');
        Schema::dropIfExists('brands');
    }
};

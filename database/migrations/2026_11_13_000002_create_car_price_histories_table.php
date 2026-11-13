<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_price_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('car_id')->index();
            $table->unsignedBigInteger('seller_id')->nullable()->index();
            $table->decimal('old_price', 12, 2);
            $table->decimal('new_price', 12, 2);
            $table->decimal('price_difference', 12, 2);
            $table->decimal('change_percentage', 6, 2)->default(0);
            $table->string('currency', 10)->default('EGP');
            $table->string('changed_by_type', 30)->default('seller');
            $table->unsignedBigInteger('changed_by_id')->nullable();
            $table->timestamps();

            $table->index(['car_id', 'created_at']);
            $table->index(['seller_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_price_histories');
    }
};


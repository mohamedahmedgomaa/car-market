<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id');
            $table->unsignedBigInteger('brand_id');
            $table->unsignedBigInteger('model_id');
            $table->unsignedBigInteger('city_id');
            $table->unsignedBigInteger('country_id');
            $table->json('title');
            $table->json('description');
            $table->decimal('price', 10, 2);
            $table->year('year');
            $table->integer('mileage');
            $table->enum('transmission', ['manual','automatic']);
            $table->enum('fuel_type', ['petrol','diesel','electric','hybrid']);
            $table->enum('drivetrain', ['fwd','rwd','awd','4wd']);
            $table->string('color')->nullable();
            $table->enum('condition', ['new','used']);
            $table->enum('status', ['pending','approved','rejected']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};

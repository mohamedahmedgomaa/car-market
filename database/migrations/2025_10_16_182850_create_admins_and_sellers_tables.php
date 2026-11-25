<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('admins')) {
            Schema::create('admins', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->string('phone')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('sellers')) {
            Schema::create('sellers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->string('phone')->nullable();
                $table->json('store_name');
                $table->json('store_description')->nullable();
                $table->string('store_logo')->nullable();
                $table->string('cover_image')->nullable();
                $table->string('business_license')->nullable();
                $table->string('bank_account')->nullable();
                $table->string('tax_number')->nullable();
                $table->string('tax_card_image')->nullable();
                $table->boolean('is_verified');
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->string('tier')->default('none');
                $table->unsignedBigInteger('views_count')->default(0);
                $table->json('address')->nullable();
                $table->text('map_url')->nullable();
                $table->string('facebook')->nullable();
                $table->string('instagram')->nullable();
                $table->string('youtube')->nullable();
                $table->string('website')->nullable();
                $table->string('tiktok')->nullable();
                $table->unsignedBigInteger('governorate_id')->nullable();
                $table->unsignedBigInteger('city_id')->nullable();
                $table->timestamps();

                $table->foreign('governorate_id')->references('id')->on('governorates')->onDelete('set null');
                $table->foreign('city_id')->references('id')->on('cities')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sellers');
        Schema::dropIfExists('admins');
    }
};

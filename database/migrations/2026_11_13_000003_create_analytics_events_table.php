<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analytics_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id')->index();
            $table->unsignedBigInteger('car_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('event_type', 40)->index(); // car_view, profile_view, whatsapp_click, phone_click, share_click, map_click, social_click
            $table->string('ip_hash', 64)->nullable()->index();
            $table->string('device_type', 20)->nullable(); // mobile, tablet, desktop
            $table->string('user_agent', 255)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('created_at')->useCurrent()->index();

            $table->index(['seller_id', 'event_type', 'created_at'], 'idx_seller_event_date');
            $table->index(['car_id', 'event_type', 'created_at'], 'idx_car_event_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_events');
    }
};


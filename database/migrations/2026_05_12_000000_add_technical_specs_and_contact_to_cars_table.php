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
        Schema::table('cars', function (Blueprint $row) {
            $row->string('horsepower')->nullable()->after('condition');
            $row->string('torque')->nullable()->after('horsepower');
            $row->string('engine_capacity')->nullable()->after('torque');
            $row->string('phone_number')->nullable()->after('engine_capacity');
            $row->string('whatsapp_number')->nullable()->after('phone_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $row) {
            $row->dropColumn(['horsepower', 'torque', 'engine_capacity', 'phone_number', 'whatsapp_number']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For PostgreSQL, we need to update the check constraint for the fuel_type enum mapping.
        // Laravel's enum columns in Postgres are actually varchar columns with a CHECK constraint.
        
        DB::statement("ALTER TABLE cars DROP CONSTRAINT IF EXISTS cars_fuel_type_check");
        DB::statement("ALTER TABLE cars ADD CONSTRAINT cars_fuel_type_check CHECK (fuel_type IN ('petrol', 'diesel', 'electric', 'hybrid', 'mild_hybrid', 'reev'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE cars DROP CONSTRAINT IF EXISTS cars_fuel_type_check");
        DB::statement("ALTER TABLE cars ADD CONSTRAINT cars_fuel_type_check CHECK (fuel_type IN ('petrol', 'diesel', 'electric', 'hybrid'))");
    }
};

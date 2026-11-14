<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Reset3DModelsAndCleanArchetypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Cleaning generic 3D model URLs from models table...');

        $modelsAffected = DB::table('models')
            ->whereNotNull('model_3d_url')
            ->update(['model_3d_url' => null]);

        $this->command->info("Cleared model_3d_url from {$modelsAffected} models in 'models' table.");

        $this->command->info('Cleaning generic 3D model URLs from cars table...');

        $carsAffected = DB::table('cars')
            ->whereNotNull('model_3d_url')
            ->update(['model_3d_url' => null]);

        $this->command->info("Cleared model_3d_url from {$carsAffected} cars in 'cars' table.");

        $this->command->info('Done! 3D models will now only appear when explicitly uploaded for a specific car.');
    }
}


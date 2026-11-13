<?php

namespace Database\Seeders;

use App\Http\Modules\Cars\Models\Car;
use App\Http\Modules\Models\Models\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarModel3DSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Supercars / Mid-engine Spiders
        $sportsKeywords = [
            'ferrari', 'lamborghini', 'mclaren', 'r8', 'bugatti', 'pagani', 'spider', 'aventador',
            'huracan', '488', 'f8', 'roma', 'sf90', 'laferrari', 'gallardo', 'murcielago', 'viper',
            'corvette', 'countach', 'mc20', 'granturismo', 'grancabrio'
        ];

        // 2. Sports Coupes
        $coupeKeywords = [
            '911', '718', 'cayman', 'boxster', 'mustang', 'camaro', 'challenger', 'charger',
            'supra', 'amg gt', 'tt', 'lc500', 'rc-f', 'm2', 'm4', 'm8', 'coupe', 'cabrio',
            'convertible', 'gt-r', '370z', '350z', 'f-type'
        ];

        // 3. Electric & High-Tech Vehicles
        $evKeywords = [
            'tesla', 'model 3', 'model s', 'model x', 'model y', 'cybertruck', 'xiaomi', 'su7',
            'taycan', 'ioniq', 'ev6', 'id.3', 'id.4', 'id.6', 'e-tron', 'lucid', 'polestar',
            'zeekr', 'byd', 'han', 'seal', 'electric'
        ];

        // 4. SUVs, Crossovers, 4x4 & Pickups
        $suvKeywords = [
            'suv', '4x4', 'x1', 'x2', 'x3', 'x4', 'x5', 'x6', 'x7', 'x8', 'q2', 'q3', 'q5', 'q7', 'q8',
            'gla', 'glb', 'glc', 'gle', 'gls', 'g-class', 'g 63', 'g63', 'tucson', 'santa fe', 'creta',
            'bayon', 'palisade', 'venue', 'kona', 'sportage', 'sorento', 'seltos', 'telluride', 'xceed',
            'duster', 'stepway', 'koleos', 'kadjar', 'captur', 'qashqai', 'juke', 'x-trail', 'patrol',
            'pathfinder', 'murano', 'rogue', 'tiguan', 'touareg', 't-roc', 'taigo', 'rav4', 'land cruiser',
            'prado', 'fortuner', 'highlander', 'cherokee', 'grand cherokee', 'wrangler', 'renegade',
            'compass', 'defender', 'discovery', 'range rover', 'velar', 'evoque', 'cayenne', 'macan',
            'urus', 'bentayga', 'cullinan', 'levante', 'f-pace', 'e-pace', 'pajero', 'eclipse cross',
            'asx', 'outlander', 'xpander', 'd-max', 'hilux', 'tahoe', 'suburban', 'trax', 'traverse',
            'trailblazer', 'equinox', 'durango', 'yukon', 'acadia', 'terrain', 'explorer', 'expedition',
            'bronco', 'edge', 'escape', 'ecosport', '2008', '3008', '5008', 'c3 aircross', 'c5 aircross',
            'ateca', 'arona', 'tarraco', 'cx-3', 'cx-5', 'cx-9', 'cx-30', 'rx', 'nx', 'gx', 'lx', 'ux',
            'qx50', 'qx60', 'qx80', 'xt4', 'xt5', 'xt6', 'escalade', 'navigator', 'aviator', 'corsair',
            'nativa', 'pickup', 'truck', 'ram'
        ];

        // 5. Classic Cars
        $classicKeywords = [
            'e30', 'classic', 'vintage', '190e', 'w124', 'w123', 'retro'
        ];

        $models = Model::with('brand')->get();
        $stats = [
            'sports' => 0,
            'coupe' => 0,
            'ev' => 0,
            'suv' => 0,
            'classic' => 0,
            'sedan' => 0,
        ];

        foreach ($models as $m) {
            $enName = strtolower($m->getTranslation('name', 'en', false) ?: $m->name ?: '');
            $arName = strtolower($m->getTranslation('name', 'ar', false) ?: '');
            $brandName = strtolower($m->brand ? ($m->brand->getTranslation('name', 'en', false) ?: $m->brand->name ?: '') : '');
            $combined = $brandName . ' ' . $enName . ' ' . $arName;

            $assignedModel = '/models/sedan.glb'; // Default Archetype: Sedan
            $category = 'sedan';

            if ($this->matchesKeywords($combined, $sportsKeywords)) {
                $assignedModel = '/models/sports.glb';
                $category = 'sports';
            } elseif ($this->matchesKeywords($combined, $coupeKeywords)) {
                // Porsche & sports coupes
                $assignedModel = '/models/coupe.glb';
                $category = 'coupe';
            } elseif ($this->matchesKeywords($combined, $evKeywords)) {
                $assignedModel = '/models/ev.glb';
                $category = 'ev';
            } elseif ($this->matchesKeywords($combined, $suvKeywords)) {
                $assignedModel = '/models/suv.glb';
                $category = 'suv';
            } elseif ($this->matchesKeywords($combined, $classicKeywords)) {
                $assignedModel = '/models/classic.glb';
                $category = 'classic';
            }

            $m->model_3d_url = $assignedModel;
            $m->saveQuietly();
            $stats[$category]++;
        }

        $this->command->info("Updated 3D archetypes for all models:");
        foreach ($stats as $cat => $count) {
            $this->command->line(" - {$cat}: {$count} models");
        }

        // Synchronize all existing cars to their assigned model archetype
        $carsUpdated = DB::table('cars')
            ->join('models', 'cars.model_id', '=', 'models.id')
            ->whereNotNull('models.model_3d_url')
            ->update(['cars.model_3d_url' => DB::raw('models.model_3d_url')]);

        $this->command->info("Synchronized 3D URLs to {$carsUpdated} existing cars in the database!");
    }

    private function matchesKeywords(string $text, array $keywords): bool
    {
        foreach ($keywords as $k) {
            if (str_contains($text, $k)) {
                return true;
            }
        }
        return false;
    }
}

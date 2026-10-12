<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SeedMotorcyclesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:seed-motorcycles {count=1500 : Number of motorcycles to generate} {--batch=500 : Batch size for bulk insert}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed realistic motorcycles and scooters for marketplace testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ini_set('memory_limit', '-1');
        $totalTarget = (int) $this->argument('count');
        $batchSize = (int) $this->option('batch');

        $this->info("Preparing to seed {$totalTarget} motorcycles in batches of {$batchSize}...");

        DB::connection()->disableQueryLog();

        // 1. Fetch motorcycle models & brands
        $models = DB::table('models')
            ->join('brands', 'models.brand_id', '=', 'brands.id')
            ->where(function ($q) {
                $q->where('brands.type', 'motorcycle')
                  ->orWhere('brands.id', '>=', 98);
            })
            ->select(
                'brands.id as brand_id',
                'brands.name as brand_name',
                'models.id as model_id',
                'models.name as model_name'
            )
            ->get();

        if ($models->isEmpty()) {
            $this->error('No motorcycle models found! Falling back to all models.');
            $models = DB::table('models')
                ->join('brands', 'models.brand_id', '=', 'brands.id')
                ->select(
                    'brands.id as brand_id',
                    'brands.name as brand_name',
                    'models.id as model_id',
                    'models.name as model_name'
                )
                ->get();
        }

        $cities = DB::table('cities')->select('id', 'governorate_id')->get();
        $sellers = DB::table('sellers')->select('id', 'phone')->get();

        if ($cities->isEmpty() || $sellers->isEmpty()) {
            $this->error('Cities or Sellers missing! Please run seeders first.');
            return 1;
        }

        $motorcycleImages = [
            'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1558981359-219d6364c9c8?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1609630875171-b1321377ee65?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1558980664-769d59546b3d?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1558980394-4c7c9299fe96?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1591637333184-19aa84b3e01f?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1599819811279-d5ad9cccf838?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1571188654248-7a89213915f7?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1515777315835-281b94c9589f?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1525160354320-d8e92641c563?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1449426468159-d96dbf08f19f?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1547549082-6bc09f2049ae?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1558981420-87aa9dad1c89?auto=format&fit=crop&w=800&q=80',
        ];

        $modelsCount = count($models);
        $citiesCount = count($cities);
        $sellersCount = count($sellers);
        $imagesCount = count($motorcycleImages);

        $colors = ['Black', 'Red', 'Blue', 'White', 'Matte Grey', 'Green', 'Orange', 'Yellow'];
        $engineCapacities = ['125cc', '150cc', '200cc', '250cc', '300cc', '400cc', '650cc', '900cc', '1000cc'];

        $startTime = microtime(true);
        $insertedCount = 0;

        $bar = $this->output->createProgressBar($totalTarget);
        $bar->start();

        while ($insertedCount < $totalTarget) {
            $currentBatch = min($batchSize, $totalTarget - $insertedCount);
            $rows = [];
            $now = now()->subMinutes(rand(10, 20000))->toDateTimeString();

            for ($i = 0; $i < $currentBatch; $i++) {
                $idx = $insertedCount + $i;

                $m = $models[$idx % $modelsCount];
                $c = $cities[$idx % $citiesCount];
                $s = $sellers[$idx % $sellersCount];

                $bNameArr = json_decode($m->brand_name, true) ?? ['en' => 'Motorcycle', 'ar' => 'دراجة نارية'];
                $mNameArr = json_decode($m->model_name, true) ?? ['en' => 'Model', 'ar' => 'موديل'];

                $bEn = $bNameArr['en'] ?? ($bNameArr['ar'] ?? 'Motorcycle');
                $bAr = $bNameArr['ar'] ?? ($bNameArr['en'] ?? 'دراجة نارية');
                $mEn = $mNameArr['en'] ?? ($mNameArr['ar'] ?? 'Model');
                $mAr = $mNameArr['ar'] ?? ($mNameArr['en'] ?? 'موديل');

                $year = 2018 + ($idx % 8); // 2018 to 2025
                $isNew = ($year >= 2024 && ($idx % 3 === 0));
                $cond = $isNew ? 'new' : 'used';
                $mileage = $isNew ? 0 : (($idx * 83) % 35000 + 500);

                // Price: between 35,000 EGP and 650,000 EGP
                $isScooter = in_array(strtolower($bEn), ['vespa', 'sym', 'kymco', 'piaggio']);
                $basePrice = $isScooter ? 45000 : 65000;
                $price = $basePrice + (($idx * 179) % 450000);

                $engine = $engineCapacities[$idx % count($engineCapacities)];
                $trans = $isScooter ? 'automatic' : (($idx % 5 === 0) ? 'automatic' : 'manual');

                $title = json_encode([
                    'en' => "{$bEn} {$mEn} {$year}",
                    'ar' => ($isScooter ? 'سكوتر' : 'دراجة نارية') . " {$bAr} {$mAr} {$year}",
                ], JSON_UNESCAPED_UNICODE);

                $desc = json_encode([
                    'en' => "{$bEn} {$mEn} {$year} ({$engine}), {$cond} condition, well maintained with clean papers.",
                    'ar' => ($isScooter ? 'سكوتر' : 'دراجة نارية') . " {$bAr} {$mAr} موديل {$year} سعة {$engine} بحالة ممتازة ورخصة سارية.",
                ], JSON_UNESCAPED_UNICODE);

                $rows[] = [
                    'seller_id' => $s->id,
                    'brand_id' => $m->brand_id,
                    'model_id' => $m->model_id,
                    'city_id' => $c->id,
                    'governorate_id' => $c->governorate_id,
                    'country_id' => 1,
                    'type' => 'motorcycle',
                    'title' => $title,
                    'description' => $desc,
                    'price' => $price,
                    'currency' => 'EGP',
                    'year' => $year,
                    'mileage' => $mileage,
                    'transmission' => $trans,
                    'fuel_type' => ($idx % 12 === 0) ? 'electric' : 'petrol',
                    'drivetrain' => 'rwd',
                    'color' => $colors[$idx % count($colors)],
                    'condition' => $cond,
                    'horsepower' => (string) (15 + (($idx * 7) % 180)),
                    'torque' => (string) (12 + (($idx * 5) % 120)) . ' Nm',
                    'engine_capacity' => $engine,
                    'cylinders' => ($idx % 4 === 0) ? 2 : 1,
                    'acceleration' => number_format(3.2 + (($idx % 40) / 10), 1) . 's',
                    'weight' => (string) (110 + (($idx * 11) % 180)) . ' kg',
                    'phone_number' => $s->phone ?? '01012345678',
                    'whatsapp_number' => $s->phone ?? '01012345678',
                    'is_best_deal' => ($idx % 5 === 0),
                    'is_import' => ($idx % 4 === 0),
                    'is_featured' => ($idx % 7 === 0),
                    'show_on_home' => ($idx % 8 === 0),
                    'is_global_ad' => ($idx % 15 === 0),
                    'status' => 'approved',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // Insert chunk & attach images
            DB::transaction(function () use ($rows, $motorcycleImages, $imagesCount) {
                DB::table('cars')->insert($rows);

                $firstId = (int) DB::getPdo()->lastInsertId();
                $insertedChunkCount = count($rows);

                $imageRows = [];
                for ($j = 0; $j < $insertedChunkCount; $j++) {
                    $carId = $firstId + $j;
                    $createdAt = $rows[$j]['created_at'];
                    $imageRows[] = [
                        'car_id' => $carId,
                        'path' => $motorcycleImages[$carId % $imagesCount],
                        'is_main' => 1,
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ];
                }

                if (!empty($imageRows)) {
                    DB::table('car_images')->insert($imageRows);
                }
            });

            unset($rows);
            gc_collect_cycles();

            $insertedCount += $currentBatch;
            $bar->advance($currentBatch);
        }

        $bar->finish();
        $this->newLine();

        $elapsed = round(microtime(true) - $startTime, 2);
        $totalMotorcycles = DB::table('cars')->where('type', 'motorcycle')->count();

        $this->info("Successfully seeded {$totalTarget} motorcycles in {$elapsed} seconds!");
        $this->info("Total motorcycles in DB now: {$totalMotorcycles}");

        return 0;
    }
}

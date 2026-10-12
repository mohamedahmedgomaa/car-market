<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SeedMassiveCarsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:seed-massive-cars {count=100000 : Number of cars to generate} {--batch=1000 : Batch size for bulk insert}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed a massive number of cars and car images quickly for load and performance testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ini_set('memory_limit', '-1');
        $totalTarget = (int) $this->argument('count');
        $batchSize = (int) $this->option('batch');

        $this->info("Preparing to seed {$totalTarget} cars in batches of {$batchSize}...");

        DB::connection()->disableQueryLog();

        // 1. Fetch relations data in memory
        $models = DB::table('models')
            ->join('brands', 'models.brand_id', '=', 'brands.id')
            ->select(
                'brands.id as brand_id',
                'brands.name as brand_name',
                'models.id as model_id',
                'models.name as model_name'
            )
            ->get();

        if ($models->isEmpty()) {
            $this->error('No models or brands found in database! Please run seeders first.');
            return 1;
        }

        $cities = DB::table('cities')->select('id', 'governorate_id')->get();
        if ($cities->isEmpty()) {
            $this->error('No cities found in database! Please run seeders first.');
            return 1;
        }

        $sellers = DB::table('sellers')->select('id', 'phone')->get();
        if ($sellers->isEmpty()) {
            $this->error('No sellers found in database! Please run seeders first.');
            return 1;
        }

        $imagesPool = [
            'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1617814076367-b759c7d7e738?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1555215695-3004980ad54e?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1556189250-72ba954cfc2b?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1580273916550-e323be2ae537?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1614162692292-7ac56d7f7f1e?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1590362891991-f776e747a588?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1583121274602-3e2820c69888?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1603584173870-7f23fdae1b7a?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1541348263662-e0c8de4259ba?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1542282088-72c9c27ed0cd?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1541899481282-d53bffe3c35d?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1520031441872-265e4ff70366?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1563720223185-11003d516935?auto=format&fit=crop&w=800&q=80',
        ];

        $modelsCount = count($models);
        $citiesCount = count($cities);
        $sellersCount = count($sellers);
        $imagesCount = count($imagesPool);

        $transmissions = ['automatic', 'automatic', 'automatic', 'manual'];
        $fuelTypes = ['petrol', 'petrol', 'petrol', 'hybrid', 'electric', 'diesel'];
        $drivetrains = ['fwd', 'fwd', 'rwd', 'awd', '4wd'];
        $colors = ['White', 'Black', 'Silver', 'Grey', 'Navy Blue', 'Red', 'Bronze', 'Pearl'];
        $conditions = ['used', 'used', 'used', 'new'];

        $startTime = microtime(true);
        $insertedCount = 0;

        $bar = $this->output->createProgressBar($totalTarget);
        $bar->start();

        $now = now()->toDateTimeString();

        while ($insertedCount < $totalTarget) {
            $currentBatch = min($batchSize, $totalTarget - $insertedCount);
            $carRows = [];

            for ($i = 0; $i < $currentBatch; $i++) {
                $idx = $insertedCount + $i;

                $m = $models[$idx % $modelsCount];
                $c = $cities[$idx % $citiesCount];
                $s = $sellers[$idx % $sellersCount];

                $bNameArr = json_decode($m->brand_name, true) ?? ['en' => 'Brand', 'ar' => 'ماركة'];
                $mNameArr = json_decode($m->model_name, true) ?? ['en' => 'Model', 'ar' => 'موديل'];

                $bEn = $bNameArr['en'] ?? ($bNameArr['ar'] ?? 'Brand');
                $bAr = $bNameArr['ar'] ?? ($bNameArr['en'] ?? 'ماركة');
                $mEn = $mNameArr['en'] ?? ($mNameArr['ar'] ?? 'Model');
                $mAr = $mNameArr['ar'] ?? ($mNameArr['en'] ?? 'موديل');

                $year = 2016 + ($idx % 10); // 2016 to 2025
                $isNew = ($conditions[$idx % 4] === 'new' && $year >= 2024);
                $cond = $isNew ? 'new' : 'used';
                $mileage = $isNew ? 0 : (($idx * 173) % 160000 + 5000);

                $price = 450000 + (($idx * 317) % 7500000);

                $title = json_encode([
                    'en' => "{$bEn} {$mEn} {$year}",
                    'ar' => "{$bAr} {$mAr} {$year}",
                ], JSON_UNESCAPED_UNICODE);

                $desc = json_encode([
                    'en' => "{$bEn} {$mEn} {$year} in excellent condition, well maintained with full inspection report.",
                    'ar' => "سيارة {$bAr} {$mAr} موديل {$year} بحالة ممتازة فابريكا بالكامل مع تقرير فحص شامل.",
                ], JSON_UNESCAPED_UNICODE);

                $carRows[] = [
                    'seller_id' => $s->id,
                    'brand_id' => $m->brand_id,
                    'model_id' => $m->model_id,
                    'city_id' => $c->id,
                    'governorate_id' => $c->governorate_id,
                    'country_id' => 1,
                    'type' => 'car',
                    'title' => $title,
                    'description' => $desc,
                    'price' => $price,
                    'currency' => 'EGP',
                    'year' => $year,
                    'mileage' => $mileage,
                    'transmission' => $transmissions[$idx % 4],
                    'fuel_type' => $fuelTypes[$idx % 6],
                    'drivetrain' => $drivetrains[$idx % 5],
                    'color' => $colors[$idx % 8],
                    'condition' => $cond,
                    'horsepower' => (string) (110 + (($idx * 13) % 350)),
                    'torque' => (string) (150 + (($idx * 17) % 450)) . ' Nm',
                    'engine_capacity' => (string) (1200 + (($idx * 100) % 2500)) . 'cc',
                    'cylinders' => ($idx % 5 === 0) ? 6 : 4,
                    'acceleration' => number_format(5.5 + (($idx % 60) / 10), 1) . 's',
                    'weight' => (string) (1200 + (($idx * 23) % 1000)) . ' kg',
                    'phone_number' => $s->phone ?? '01012345678',
                    'whatsapp_number' => $s->phone ?? '01012345678',
                    'is_best_deal' => ($idx % 7 === 0),
                    'is_import' => ($idx % 5 === 0),
                    'is_featured' => ($idx % 12 === 0),
                    'show_on_home' => ($idx % 15 === 0),
                    'is_global_ad' => ($idx % 20 === 0),
                    'status' => ($idx % 50 === 0) ? 'pending' : 'approved',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // Insert chunk with transaction & attach images
            DB::transaction(function () use ($carRows, $imagesPool, $imagesCount, $now) {
                DB::table('cars')->insert($carRows);
                
                $firstId = (int) DB::getPdo()->lastInsertId();
                $insertedChunkCount = count($carRows);

                $imageRows = [];
                for ($j = 0; $j < $insertedChunkCount; $j++) {
                    $carId = $firstId + $j;
                    $imageRows[] = [
                        'car_id' => $carId,
                        'path' => $imagesPool[($carId) % $imagesCount],
                        'is_main' => 1,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                if (!empty($imageRows)) {
                    DB::table('car_images')->insert($imageRows);
                }
            });

            unset($carRows);
            gc_collect_cycles();

            $insertedCount += $currentBatch;
            $bar->advance($currentBatch);
        }

        $bar->finish();
        $this->newLine();

        $elapsed = round(microtime(true) - $startTime, 2);
        $totalCars = DB::table('cars')->count();
        $totalImages = DB::table('car_images')->count();

        $this->info("Successfully generated {$totalTarget} cars in {$elapsed} seconds!");
        $this->info("Total cars in DB: {$totalCars}");
        $this->info("Total car images in DB: {$totalImages}");

        return 0;
    }
}

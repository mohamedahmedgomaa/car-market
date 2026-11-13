<?php

namespace Database\Seeders;

use App\Http\Modules\Cars\Models\Car;
use App\Http\Modules\CarImages\Models\CarImage;
use App\Http\Modules\CarFeaturePivots\Models\CarFeaturePivot;
use App\Http\Modules\Cars\Models\CarPriceHistory;
use App\Http\Modules\FavoriteCars\Models\FavoriteCar;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CleanAndSeed3DCarsSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Cleaning existing dummy cars and related tables...');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        FavoriteCar::truncate();
        CarPriceHistory::truncate();
        CarFeaturePivot::truncate();
        CarImage::truncate();
        Car::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $showcaseCars = [
            // 1. Porsche 718 Cayman (Coupe) - Keeps ID 101123
            [
                'id' => 101123,
                'seller_id' => 1,
                'brand_id' => 39, // Porsche
                'model_id' => 353, // 718 Cayman
                'city_id' => 1,
                'country_id' => 1,
                'type' => 'car',
                'title' => [
                    'en' => 'Porsche 718 Cayman 2023',
                    'ar' => 'بورشه 718 كايمان 2023',
                ],
                'description' => [
                    'en' => 'Exceptional Porsche 718 Cayman in Carmine Red with full aerodynamics package, PDK dual-clutch transmission, sport exhaust, and verified factory inspection.',
                    'ar' => 'بورشه 718 كايمان بحالة الزيرو بلون أحمر قرمزي، باقة الديناميكية الهوائية، ناقل حركة PDK ثنائي التعشيق، عادم رياضي، وفحص فني معتمد بالكامل.',
                ],
                'price' => 4469049,
                'currency' => 'EGP',
                'year' => 2023,
                'mileage' => 12500,
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'drivetrain' => 'rwd',
                'color' => 'Carmine Red',
                'condition' => 'used',
                'horsepower' => 300,
                'cylinders' => 4,
                'engine_capacity' => 2000,
                'acceleration' => 4.7,
                'weight' => 1365,
                'model_3d_url' => '/models/coupe.glb',
                'is_featured' => 1,
                'show_on_home' => 1,
                'is_best_deal' => 1,
                'status' => 'approved',
                'main_image' => 'https://images.unsplash.com/photo-1614162692292-7ac56d7f7f1e?auto=format&fit=crop&w=1200&q=80',
            ],

            // 2. Porsche 911 GT3 RS (Coupe)
            [
                'id' => 101124,
                'seller_id' => 1,
                'brand_id' => 39, // Porsche
                'model_id' => 347, // 911
                'city_id' => 1,
                'country_id' => 1,
                'type' => 'car',
                'title' => [
                    'en' => 'Porsche 911 GT3 RS 2024',
                    'ar' => 'بورشه 911 جي تي 3 آر إس 2024',
                ],
                'description' => [
                    'en' => 'Brand new Porsche 911 GT3 RS with Weissach package, active DRS rear wing, carbon ceramic brakes, and bucket seats.',
                    'ar' => 'بورشه 911 GT3 RS زيرو مع باقة فايساخ الخفيفة، جناح خلفي ديناميكي نشط بتقنية DRS، فرامل كربون سيراميك، ومقاعد سباق من ألياف الكربون.',
                ],
                'price' => 11800000,
                'currency' => 'EGP',
                'year' => 2024,
                'mileage' => 500,
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'drivetrain' => 'rwd',
                'color' => 'Silver / Red Trim',
                'condition' => 'new',
                'horsepower' => 518,
                'cylinders' => 6,
                'engine_capacity' => 4000,
                'acceleration' => 3.2,
                'weight' => 1450,
                'model_3d_url' => '/models/coupe.glb',
                'is_featured' => 1,
                'show_on_home' => 1,
                'is_best_deal' => 1,
                'status' => 'approved',
                'main_image' => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=1200&q=80',
            ],

            // 3. Ferrari 458 Spider (Convertible / Sports)
            [
                'id' => 101125,
                'seller_id' => 1,
                'brand_id' => 14, // Ferrari
                'model_id' => 122, // 488 / Spider
                'city_id' => 1,
                'country_id' => 1,
                'type' => 'car',
                'title' => [
                    'en' => 'Ferrari 458 Spider 2023',
                    'ar' => 'فيراري 458 سبايدر 2023',
                ],
                'description' => [
                    'en' => 'Iconic naturally aspirated V8 mid-engine Italian masterpiece with retractable hardtop and carbon fiber racing seats.',
                    'ar' => 'أيقونة فيراري الإيطالية بمحرك V8 وسقف صلب قابل للكشف بالكامل، مقصورة جلدية فاخرة مع تطريزات يدوية وتقارير فحص وكالة معتمدة.',
                ],
                'price' => 9600000,
                'currency' => 'EGP',
                'year' => 2023,
                'mileage' => 8400,
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'drivetrain' => 'rwd',
                'color' => 'Rosso Corsa Red',
                'condition' => 'used',
                'horsepower' => 562,
                'cylinders' => 8,
                'engine_capacity' => 4500,
                'acceleration' => 3.4,
                'weight' => 1430,
                'model_3d_url' => '/models/sports.glb',
                'is_featured' => 1,
                'show_on_home' => 1,
                'is_best_deal' => 1,
                'status' => 'approved',
                'main_image' => 'https://images.unsplash.com/photo-1592198084033-aade902d1aae?auto=format&fit=crop&w=1200&q=80',
            ],

            // 4. Range Rover Sport Autobiography (SUV)
            [
                'id' => 101126,
                'seller_id' => 1,
                'brand_id' => 29, // Land Rover
                'model_id' => 255, // Range Rover Sport
                'city_id' => 1,
                'country_id' => 1,
                'type' => 'car',
                'title' => [
                    'en' => 'Range Rover Sport Autobiography 2024',
                    'ar' => 'رينج روفر سبورت أوتوبيوغرافي 2024',
                ],
                'description' => [
                    'en' => 'Ultimate luxury British SUV with adaptive dynamic air suspension, panoramic glass roof, Meridian 3D sound, and twin-turbo V8 power.',
                    'ar' => 'قمة الفخامة البريطانية مع نظام تعليق هوائي متكيف، سقف بانوراما، نظام صوت Meridian ثلاثي الأبعاد، ودفع رباعي ذكي متقدم.',
                ],
                'price' => 7850000,
                'currency' => 'EGP',
                'year' => 2024,
                'mileage' => 3200,
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'drivetrain' => 'awd',
                'color' => 'Santorini Black',
                'condition' => 'new',
                'horsepower' => 523,
                'cylinders' => 8,
                'engine_capacity' => 4400,
                'acceleration' => 4.3,
                'weight' => 2430,
                'model_3d_url' => '/models/suv.glb',
                'is_featured' => 1,
                'show_on_home' => 1,
                'is_best_deal' => 1,
                'status' => 'approved',
                'main_image' => 'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?auto=format&fit=crop&w=1200&q=80',
            ],

            // 5. Mercedes-Benz S-Class S580 (Sedan)
            [
                'id' => 101127,
                'seller_id' => 1,
                'brand_id' => 34, // Mercedes
                'model_id' => 298, // S-Class
                'city_id' => 1,
                'country_id' => 1,
                'type' => 'car',
                'title' => [
                    'en' => 'Mercedes-Benz S-Class S580 4MATIC 2024',
                    'ar' => 'مرسيدس بنز إس كلاس S580 فورماتيك 2024',
                ],
                'description' => [
                    'en' => 'The pinnacle of executive luxury sedans with Maybach-grade rear executive seating, MBUX hyperscreen, and Burmester high-end audio.',
                    'ar' => 'سيدة سيارات السيدان التنفيذية الفاخرة مع مقاعد خلفية رئوية مدفأة ومبردة مع مساج، شاشات MBUX تفاعلية، ونظام قيادة شبه ذاتي.',
                ],
                'price' => 8400000,
                'currency' => 'EGP',
                'year' => 2024,
                'mileage' => 1800,
                'transmission' => 'automatic',
                'fuel_type' => 'hybrid',
                'drivetrain' => 'awd',
                'color' => 'Obsidian Black',
                'condition' => 'new',
                'horsepower' => 496,
                'cylinders' => 8,
                'engine_capacity' => 4000,
                'acceleration' => 4.4,
                'weight' => 2190,
                'model_3d_url' => '/models/sedan.glb',
                'is_featured' => 1,
                'show_on_home' => 1,
                'is_best_deal' => 1,
                'status' => 'approved',
                'main_image' => 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?auto=format&fit=crop&w=1200&q=80',
            ],

            // 6. BMW M5 Competition (Sport Sedan)
            [
                'id' => 101128,
                'seller_id' => 1,
                'brand_id' => 6, // BMW
                'model_id' => 56, // M5
                'city_id' => 1,
                'country_id' => 1,
                'type' => 'car',
                'title' => [
                    'en' => 'BMW M5 Competition 2024',
                    'ar' => 'بي إم دبليو M5 كومبيتيشن 2024',
                ],
                'description' => [
                    'en' => 'High-performance luxury super-sedan with M xDrive switchable AWD/RWD system, M Carbon roof, and track-ready cooling.',
                    'ar' => 'سوبر سيدان الأداء العالي بمحرك M TwinPower Turbo ودفع كلي ذكي قابل للتحويل لدفع خلفي كامل وسقف كربون فايبر.',
                ],
                'price' => 6900000,
                'currency' => 'EGP',
                'year' => 2024,
                'mileage' => 6500,
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'drivetrain' => 'awd',
                'color' => 'Alpine White',
                'condition' => 'used',
                'horsepower' => 617,
                'cylinders' => 8,
                'engine_capacity' => 4400,
                'acceleration' => 3.1,
                'weight' => 1970,
                'model_3d_url' => '/models/sedan.glb',
                'is_featured' => 1,
                'show_on_home' => 1,
                'is_best_deal' => 1,
                'status' => 'approved',
                'main_image' => 'https://images.unsplash.com/photo-1555215695-3004980ad54e?auto=format&fit=crop&w=1200&q=80',
            ],

            // 7. Tesla Model 3 Performance (Electric EV)
            [
                'id' => 101129,
                'seller_id' => 1,
                'brand_id' => 51, // Tesla
                'model_id' => 431, // Model 3
                'city_id' => 1,
                'country_id' => 1,
                'type' => 'car',
                'title' => [
                    'en' => 'Tesla Model 3 Performance 2024',
                    'ar' => 'تسلا موديل 3 بيرفورمانس 2024',
                ],
                'description' => [
                    'en' => 'All-electric dual motor all-wheel drive rocket with Track Mode V3, adaptive damping, sports seats, and Full Self-Driving hardware.',
                    'ar' => 'صاروخ كهربائي بالكامل بمحركين ودفع كلي مع وضع الحلبات Track Mode V3، نظام تعليق متكيف، مقاعد رياضية، ومدى سير يتجاوز 528 كم.',
                ],
                'price' => 3100000,
                'currency' => 'EGP',
                'year' => 2024,
                'mileage' => 1200,
                'transmission' => 'automatic',
                'fuel_type' => 'electric',
                'drivetrain' => 'awd',
                'color' => 'Pearl White',
                'condition' => 'new',
                'horsepower' => 510,
                'cylinders' => 0,
                'engine_capacity' => 0,
                'acceleration' => 2.9,
                'weight' => 1850,
                'model_3d_url' => '/models/ev.glb',
                'is_featured' => 1,
                'show_on_home' => 1,
                'is_best_deal' => 1,
                'status' => 'approved',
                'main_image' => 'https://images.unsplash.com/photo-1560958089-b8a1929cea89?auto=format&fit=crop&w=1200&q=80',
            ],
        ];

        foreach ($showcaseCars as $data) {
            $mainImg = $data['main_image'];
            unset($data['main_image']);

            $car = Car::create($data);

            CarImage::create([
                'car_id' => $car->id,
                'path' => $mainImg,
                'is_main' => 1,
            ]);

            $this->command->info("Created showcase 3D Car: {$data['id']} - " . json_encode($data['title']['ar'], JSON_UNESCAPED_UNICODE) . " [{$data['model_3d_url']}]");
        }

        $this->command->info('Database cleaned successfully. Only verified 3D cars are now active!');
    }
}

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

        $coupeSpinFrames = array_map(fn($i) => "/images/360/exterior-coupe/frame_{$i}.jpg", range(1, 24));
        $suvSpinFrames = array_map(fn($i) => "/images/360/exterior-suv/frame_{$i}.jpg", range(1, 24));
        $interiorPano = '/images/360/interiors/cockpit_360.jpg';

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
                'exterior_360_images' => $coupeSpinFrames,
                'interior_360_url' => $interiorPano,
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
                'exterior_360_images' => $coupeSpinFrames,
                'interior_360_url' => $interiorPano,
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
                    'en' => 'Ferrari 458 Spider 2022',
                    'ar' => 'فيراري 458 سبايدر 2022',
                ],
                'description' => [
                    'en' => 'Stunning Ferrari 458 Spider in Rosso Corsa, naturally aspirated V8 screaming to 9000 RPM, retractable hard top, and carbon interior.',
                    'ar' => 'فيراري 458 سبايدر باللون الأحمر الأيقوني Rosso Corsa، محرك V8 تنفس طبيعي، سقف صلب قابل للطي كهربائياً، وديكورات داخلية ألياف الكربون.',
                ],
                'price' => 8900000,
                'currency' => 'EGP',
                'year' => 2022,
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
                'exterior_360_images' => $coupeSpinFrames,
                'interior_360_url' => $interiorPano,
                'is_featured' => 1,
                'show_on_home' => 1,
                'is_best_deal' => 1,
                'status' => 'approved',
                'main_image' => 'https://images.unsplash.com/photo-1583121274602-3e2820c69888?auto=format&fit=crop&w=1200&q=80',
            ],

            // 4. Range Rover Sport HSE (SUV)
            [
                'id' => 101126,
                'seller_id' => 1,
                'brand_id' => 26, // Land Rover
                'model_id' => 237, // Range Rover Sport
                'city_id' => 1,
                'country_id' => 1,
                'type' => 'car',
                'title' => [
                    'en' => 'Range Rover Sport HSE Dynamic 2023',
                    'ar' => 'رينج روفر سبورت HSE دايناميك 2023',
                ],
                'description' => [
                    'en' => 'Luxurious Land Rover Range Rover Sport with dynamic air suspension, panoramic roof, Meridian 3D sound system, and pristine condition.',
                    'ar' => 'لاند روفر رينج روفر سبورت HSE مع تعليق هوائي متكيف، سقف بانورامي، نظام صوت ميريديان ثلاثي الأبعاد، وحالة الفابريكا بالكامل.',
                ],
                'price' => 6750000,
                'currency' => 'EGP',
                'year' => 2023,
                'mileage' => 16000,
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'drivetrain' => 'awd',
                'color' => 'Santorini Black',
                'condition' => 'used',
                'horsepower' => 395,
                'cylinders' => 6,
                'engine_capacity' => 3000,
                'acceleration' => 5.7,
                'weight' => 2310,
                'exterior_360_images' => $suvSpinFrames,
                'interior_360_url' => $interiorPano,
                'is_featured' => 1,
                'show_on_home' => 1,
                'is_best_deal' => 1,
                'status' => 'approved',
                'main_image' => 'https://images.unsplash.com/photo-1506015391300-4802dc74de2e?auto=format&fit=crop&w=1200&q=80',
            ],

            // 5. Mercedes-Benz S-Class S500 (Sedan)
            [
                'id' => 101127,
                'seller_id' => 1,
                'brand_id' => 31, // Mercedes
                'model_id' => 282, // S-Class
                'city_id' => 1,
                'country_id' => 1,
                'type' => 'car',
                'title' => [
                    'en' => 'Mercedes-Benz S-Class S500 4MATIC 2023',
                    'ar' => 'مرسيدس بنز الفئة S إس 500 فورماتيك 2023',
                ],
                'description' => [
                    'en' => 'The pinnacle of luxury: S500 4MATIC with executive rear seating, Burmester 4D surround sound, digital light headlamps, and Level 3 driver assist.',
                    'ar' => 'قمة الفخامة الألمانية: مرسيدس S500 فورماتيك بمقاعد خلفية تنفيذية، نظام صوتي برميستر 4D، إضاءة رقمية ذكية، وأنظمة مساعدة القيادة المتقدمة.',
                ],
                'price' => 9200000,
                'currency' => 'EGP',
                'year' => 2023,
                'mileage' => 9500,
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'drivetrain' => 'awd',
                'color' => 'Obsidian Black',
                'condition' => 'used',
                'horsepower' => 429,
                'cylinders' => 6,
                'engine_capacity' => 3000,
                'acceleration' => 4.9,
                'weight' => 2065,
                'exterior_360_images' => $coupeSpinFrames,
                'interior_360_url' => $interiorPano,
                'is_featured' => 1,
                'show_on_home' => 1,
                'is_best_deal' => 1,
                'status' => 'approved',
                'main_image' => 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?auto=format&fit=crop&w=1200&q=80',
            ],

            // 6. BMW M5 Competition (Sedan)
            [
                'id' => 101128,
                'seller_id' => 1,
                'brand_id' => 5, // BMW
                'model_id' => 45, // M5
                'city_id' => 1,
                'country_id' => 1,
                'type' => 'car',
                'title' => [
                    'en' => 'BMW M5 Competition 2023',
                    'ar' => 'بي إم دبليو إم 5 كومبيتيشن 2023',
                ],
                'description' => [
                    'en' => 'High-performance supercar masquerading as an executive sedan. Twin-turbo V8, M xDrive with switchable pure RWD mode, and carbon roof.',
                    'ar' => 'سوبركار في ثوب سيدان تنفيذي فاخر: بي إم دبليو M5 بقوة 617 حصان، دفع كلي M xDrive مع إمكانية التحويل لدفع خلفي كامل، وسقف كربون.',
                ],
                'price' => 7450000,
                'currency' => 'EGP',
                'year' => 2023,
                'mileage' => 11000,
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'drivetrain' => 'awd',
                'color' => 'Marina Bay Blue',
                'condition' => 'used',
                'horsepower' => 617,
                'cylinders' => 8,
                'engine_capacity' => 4400,
                'acceleration' => 3.1,
                'weight' => 1970,
                'exterior_360_images' => $coupeSpinFrames,
                'interior_360_url' => $interiorPano,
                'is_featured' => 1,
                'show_on_home' => 1,
                'is_best_deal' => 1,
                'status' => 'approved',
                'main_image' => 'https://images.unsplash.com/photo-1555215695-3004980ad54e?auto=format&fit=crop&w=1200&q=80',
            ],

            // 7. Tesla Model 3 Performance (EV)
            [
                'id' => 101129,
                'seller_id' => 1,
                'brand_id' => 46, // Tesla
                'model_id' => 421, // Model 3
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
                'exterior_360_images' => $coupeSpinFrames,
                'interior_360_url' => $interiorPano,
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

            $this->command->info("Created showcase Car: {$data['id']} - " . json_encode($data['title']['ar'], JSON_UNESCAPED_UNICODE) . " [360 Spin: 24 frames, Interior 360: {$data['interior_360_url']}]");
        }

        $this->command->info('Database cleaned and seeded successfully with real 360 showcase cars!');
    }
}

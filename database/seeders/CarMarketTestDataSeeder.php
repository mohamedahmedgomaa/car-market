<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Modules\Admins\Models\Admin;
use App\Http\Modules\Sellers\Models\Seller;
use App\Http\Modules\Brands\Models\Brand;
use App\Http\Modules\Models\Models\Model;
use App\Http\Modules\Governorates\Models\Governorate;
use App\Http\Modules\Cities\Models\City;
use App\Http\Modules\Cars\Models\Car;
use App\Http\Modules\CarImages\Models\CarImage;
use App\Http\Modules\CarFeatures\Models\CarFeature;
use App\Http\Modules\CarFeaturePivots\Models\CarFeaturePivot;
use App\Http\Modules\Banners\Models\Banner;

class CarMarketTestDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Admins
        Admin::firstOrCreate(
            ['email' => 'admin@carmarket.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
                'phone' => '01000000001',
                'is_active' => true,
            ]
        );

        // 2. Additional Users for testing
        $testUsers = [
            ['name' => 'Ahmed Mohamed', 'email' => 'ahmed@example.com', 'password' => Hash::make('password')],
            ['name' => 'Mahmoud Hassan', 'email' => 'mahmoud@example.com', 'password' => Hash::make('password')],
            ['name' => 'Sara El-Sayed', 'email' => 'sara@example.com', 'password' => Hash::make('password')],
        ];
        foreach ($testUsers as $userData) {
            User::firstOrCreate(['email' => $userData['email']], $userData);
        }

        // 3. Find Locations
        $cairo = City::where('name->en', 'Cairo')->orWhere('name->ar', 'القاهرة')->first() ?? City::first();
        $giza = City::where('name->en', 'Giza')->orWhere('name->ar', 'الجيزة')->first() ?? $cairo;
        $alex = City::where('name->en', 'Alexandria')->orWhere('name->ar', 'الإسكندرية')->first() ?? $cairo;
        $mansoura = City::where('name->en', 'Mansoura')->orWhere('name->ar', 'المنصورة')->first() ?? $cairo;

        // 4. Seed Rich Sellers / Showrooms
        $sellersData = [
            [
                'email' => 'tarek-auto@example.com',
                'name' => 'El-Tarek Automotive',
                'phone' => '01011112222',
                'store_name' => ['en' => 'El-Tarek Automotive', 'ar' => 'الطارق أوتوموتيف'],
                'store_description' => ['en' => 'Leading automotive retailer in Egypt for luxury and commercial cars.', 'ar' => 'أكبر موزع سيارات في مصر لكبرى العلامات التجارية العالمية.'],
                'address' => ['en' => 'Ring Road, New Cairo, Egypt', 'ar' => 'الطريق الدائري، التجمع الخامس، القاهرة'],
                'city_id' => $cairo->id,
                'governorate_id' => $cairo->governorate_id,
                'tier' => 'vip',
                'sort_order' => 1,
            ],
            [
                'email' => 'bavarian-house@example.com',
                'name' => 'Bavarian Auto Group',
                'phone' => '01022223333',
                'store_name' => ['en' => 'Bavarian Auto House', 'ar' => 'المجموعة البافارية للسيارات'],
                'store_description' => ['en' => 'Specialized in German premium cars, BMW & Mini.', 'ar' => 'المتخصصون في السيارات الألمانية الفاخرة وBMW.'],
                'address' => ['en' => 'Mohandessin, Giza, Egypt', 'ar' => 'المهندسين، الجيزة'],
                'city_id' => $giza->id,
                'governorate_id' => $giza->governorate_id,
                'tier' => 'vip',
                'sort_order' => 2,
            ],
            [
                'email' => 'autodrive@example.com',
                'name' => 'AutoDrive Egypt',
                'phone' => '01033334444',
                'store_name' => ['en' => 'AutoDrive Showroom', 'ar' => 'معرض أوتو درايف'],
                'store_description' => ['en' => 'Trusted certified pre-owned and zero mileage vehicles.', 'ar' => 'سيارات كسر زيرو ومستعملة بحالة المصنع مع إمكانية التقسيط.'],
                'address' => ['en' => 'Nasr City, Cairo, Egypt', 'ar' => 'مدينة نصر، القاهرة'],
                'city_id' => $cairo->id,
                'governorate_id' => $cairo->governorate_id,
                'tier' => 'basic',
                'sort_order' => 3,
            ],
            [
                'email' => 'alex-star@example.com',
                'name' => 'Alexandria Star Motors',
                'phone' => '01044445555',
                'store_name' => ['en' => 'Alexandria Star Motors', 'ar' => 'نجمة الإسكندرية للسيارات'],
                'store_description' => ['en' => 'Premier car dealership serving Alexandria and Delta region.', 'ar' => 'معرض رائد لبيع وشراء أحدث السيارات في الإسكندرية والدلتا.'],
                'address' => ['en' => 'Smouha, Alexandria, Egypt', 'ar' => 'سموحة، الإسكندرية'],
                'city_id' => $alex->id,
                'governorate_id' => $alex->governorate_id,
                'tier' => 'vip',
                'sort_order' => 4,
            ],
            [
                'email' => 'delta-mall@example.com',
                'name' => 'Delta Auto Mall',
                'phone' => '01055556666',
                'store_name' => ['en' => 'Delta Auto Mall', 'ar' => 'مول الدلتا للسيارات'],
                'store_description' => ['en' => 'Huge variety of popular family and economic cars.', 'ar' => 'تشكيلة ضخمة من السيارات العائلية والاقتصادية بأفضل أسعار.'],
                'address' => ['en' => 'Geish Street, Mansoura, Egypt', 'ar' => 'شارع الجيش، المنصورة'],
                'city_id' => $mansoura->id,
                'governorate_id' => $mansoura->governorate_id,
                'tier' => 'basic',
                'sort_order' => 5,
            ],
        ];

        $sellers = Seller::all()->keyBy('email');
        foreach ($sellersData as $sData) {
            if (!$sellers->has($sData['email'])) {
                $seller = Seller::create(array_merge($sData, [
                    'password' => Hash::make('password'),
                    'is_verified' => true,
                    'is_active' => true,
                ]));
                $sellers->put($sData['email'], $seller);
            }
        }

        $allSellers = Seller::all();
        if ($allSellers->isEmpty()) {
            return;
        }

        // 5. Seed Banners
        $bannerImages = [
            'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=1600&q=80',
            'https://images.unsplash.com/photo-1617788138017-80ad40651399?auto=format&fit=crop&w=1600&q=80',
            'https://images.unsplash.com/photo-1555353540-64580b51c258?auto=format&fit=crop&w=1600&q=80',
            'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&w=1600&q=80',
        ];
        if (Banner::count() === 0) {
            foreach ($bannerImages as $idx => $bImg) {
                Banner::create([
                    'image_path' => $bImg,
                    'is_active' => true,
                    'type' => 'slider',
                    'link' => '/cars',
                ]);
            }
        }

        // 6. Seed High Quality Diverse Cars
        $carCatalog = [
            // Mercedes
            [
                'brand' => 'Mercedes',
                'model' => 'C-Class',
                'title_en' => 'Mercedes-Benz C200 AMG Line 2024',
                'title_ar' => 'مرسيدس-بنز C200 إيه إم جي لاين 2024',
                'desc_en' => 'Brand new Mercedes C200 AMG Line with digital light, panoramic sunroof, ambient lighting 64 colors, and Burmester sound system.',
                'desc_ar' => 'مرسيدس C200 باقة AMG الرياضية بحالة الزيرو، فتحة سقف بانوراما، نظام صوتي بيرمستر، وإضاءة محيطية تفاعلية.',
                'price' => 3850000,
                'year' => 2024,
                'mileage' => 5000,
                'transmission' => 'automatic',
                'fuel' => 'petrol',
                'drivetrain' => 'rwd',
                'color' => 'Obsidian Black',
                'condition' => 'used',
                'hp' => '204',
                'torque' => '300 Nm',
                'engine' => '1500cc Turbo',
                'cylinders' => 4,
                'accel' => '7.3s',
                'weight' => '1650 kg',
                'is_featured' => true,
                'is_best_deal' => true,
                'show_on_home' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1617814076367-b759c7d7e738?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1616422285623-13ff0162193c?auto=format&fit=crop&w=1000&q=80',
                ],
            ],
            [
                'brand' => 'Mercedes',
                'model' => 'E-Class',
                'title_en' => 'Mercedes-Benz E200 Exclusive 2024',
                'title_ar' => 'مرسيدس-بنز E200 إكسكلوسيف 2024',
                'desc_en' => 'The all-new E-Class with Superscreen, air suspension, massage seats, and head-up display.',
                'desc_ar' => 'الفئة E الجديدة كلياً مع شاشة سوبر سكرين ونظام تعليق هوائي وكراسي مساج وشاشة عرض بروجكتور.',
                'price' => 5400000,
                'year' => 2024,
                'mileage' => 1200,
                'transmission' => 'automatic',
                'fuel' => 'hybrid',
                'drivetrain' => 'rwd',
                'color' => 'Selenite Grey',
                'condition' => 'new',
                'hp' => '204',
                'torque' => '320 Nm',
                'engine' => '2000cc Turbo Mild-Hybrid',
                'cylinders' => 4,
                'accel' => '7.5s',
                'weight' => '1820 kg',
                'is_featured' => true,
                'is_best_deal' => false,
                'show_on_home' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1617814076367-b759c7d7e738?auto=format&fit=crop&w=1000&q=80',
                ],
            ],
            [
                'brand' => 'Mercedes',
                'model' => 'G-Class',
                'title_en' => 'Mercedes-AMG G63 BiTurbo 2023',
                'title_ar' => 'مرسيدس إيه إم جي G63 باي تيربو 2023',
                'desc_en' => 'Legendary G-Wagon G63 V8 BiTurbo with Night Package, carbon interior, 22-inch forged wheels.',
                'desc_ar' => 'وحش مرسيدس G63 بمحرك V8 مع حزمة نايت باكدج وتطعيمات كاربون فايبر وجنوط 22 إنش أصلية.',
                'price' => 14500000,
                'year' => 2023,
                'mileage' => 18000,
                'transmission' => 'automatic',
                'fuel' => 'petrol',
                'drivetrain' => 'awd',
                'color' => 'Matte Black',
                'condition' => 'used',
                'hp' => '585',
                'torque' => '850 Nm',
                'engine' => '4000cc V8 BiTurbo',
                'cylinders' => 8,
                'accel' => '4.5s',
                'weight' => '2560 kg',
                'is_featured' => true,
                'is_best_deal' => false,
                'show_on_home' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1520031441872-265e4ff70366?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1563720223185-11003d516935?auto=format&fit=crop&w=1000&q=80',
                ],
            ],

            // BMW
            [
                'brand' => 'BMW',
                'model' => '3 Series',
                'title_en' => 'BMW 320i M Sport LCI 2024',
                'title_ar' => 'بي إم دبليو 320i إم سبورت الفيس ليفت 2024',
                'desc_en' => 'BMW 320i LCI M Sport with curved display, Harman Kardon sound system, wireless charging and 18-inch M rims.',
                'desc_ar' => 'بي إم دبليو الفئة الثالثة الفيس ليفت مع الشاشة المنحنية العملاقة ونظام صوت هارمان كاردون وباقة إم سبورت.',
                'price' => 3100000,
                'year' => 2024,
                'mileage' => 8000,
                'transmission' => 'automatic',
                'fuel' => 'petrol',
                'drivetrain' => 'rwd',
                'color' => 'Portimao Blue',
                'condition' => 'used',
                'hp' => '184',
                'torque' => '300 Nm',
                'engine' => '2000cc TwinPower Turbo',
                'cylinders' => 4,
                'accel' => '7.1s',
                'weight' => '1570 kg',
                'is_featured' => true,
                'is_best_deal' => true,
                'show_on_home' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1555215695-3004980ad54e?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1556189250-72ba954cfc2b?auto=format&fit=crop&w=1000&q=80',
                ],
            ],
            [
                'brand' => 'BMW',
                'model' => '5 Series',
                'title_en' => 'BMW 520i M Sport All-New G60 2024',
                'title_ar' => 'بي إم دبليو 520i إم سبورت الشكل الجديد 2024',
                'desc_en' => 'The brand new generation BMW 5 Series G60 with BMW interaction bar, crystal controls, panoramic glass roof.',
                'desc_ar' => 'الجيل الجديد كلياً G60 مع شريط التفاعل الضوئي وأزرار الكريستال وسقف بانوراما بالكامل.',
                'price' => 5200000,
                'year' => 2024,
                'mileage' => 3500,
                'transmission' => 'automatic',
                'fuel' => 'hybrid',
                'drivetrain' => 'rwd',
                'color' => 'Brooklyn Grey',
                'condition' => 'used',
                'hp' => '208',
                'torque' => '330 Nm',
                'engine' => '2000cc Mild Hybrid',
                'cylinders' => 4,
                'accel' => '7.5s',
                'weight' => '1725 kg',
                'is_featured' => true,
                'is_best_deal' => false,
                'show_on_home' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1580273916550-e323be2ae537?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1555215695-3004980ad54e?auto=format&fit=crop&w=1000&q=80',
                ],
            ],
            [
                'brand' => 'BMW',
                'model' => 'X5',
                'title_en' => 'BMW X5 xDrive40i M Sport 2023',
                'title_ar' => 'بي إم دبليو X5 إكس درايف 40i إم سبورت 2023',
                'desc_en' => 'Luxury SUV with 3.0L inline 6 cylinder engine, laser lights, 360 camera, soft close doors.',
                'desc_ar' => 'سيارة دفع رباعي فخمة بمحرك 6 سلندر 3000 سي سي مع أبواب شفط وإضاءة ليزر وكاميرات 360.',
                'price' => 6900000,
                'year' => 2023,
                'mileage' => 22000,
                'transmission' => 'automatic',
                'fuel' => 'petrol',
                'drivetrain' => 'awd',
                'color' => 'Alpine White',
                'condition' => 'used',
                'hp' => '340',
                'torque' => '450 Nm',
                'engine' => '3000cc Inline-6',
                'cylinders' => 6,
                'accel' => '5.5s',
                'weight' => '2135 kg',
                'is_featured' => true,
                'is_best_deal' => false,
                'show_on_home' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1556189250-72ba954cfc2b?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1580273916550-e323be2ae537?auto=format&fit=crop&w=1000&q=80',
                ],
            ],

            // Porsche
            [
                'brand' => 'Porsche',
                'model' => 'Cayenne',
                'title_en' => 'Porsche Cayenne GTS Coupe 2023',
                'title_ar' => 'بورشه كايين جي تي إس كوبيه 2023',
                'desc_en' => 'Stunning Porsche Cayenne GTS Coupe with Sport Chrono package, active exhaust system and carbon interior.',
                'desc_ar' => 'بورشه كايين كوبيه بحالة الوكالة مع باقة سبورت كرونو وعادم رياضي نشط وفرش جلد أحمر أصلي.',
                'price' => 9500000,
                'year' => 2023,
                'mileage' => 14000,
                'transmission' => 'automatic',
                'fuel' => 'petrol',
                'drivetrain' => 'awd',
                'color' => 'Carmine Red',
                'condition' => 'used',
                'hp' => '460',
                'torque' => '620 Nm',
                'engine' => '4000cc V8 Twin-Turbo',
                'cylinders' => 8,
                'accel' => '4.5s',
                'weight' => '2145 kg',
                'is_featured' => true,
                'is_best_deal' => false,
                'show_on_home' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1614162692292-7ac56d7f7f1e?auto=format&fit=crop&w=1000&q=80',
                ],
            ],
            [
                'brand' => 'Porsche',
                'model' => 'Panamera',
                'title_en' => 'Porsche Panamera 4S 2022',
                'title_ar' => 'بورشه باناميرا 4S 2022',
                'desc_en' => 'Porsche Panamera 4S with rear axle steering, matrix LED headlights, 4-zone climate control.',
                'desc_ar' => 'بورشه باناميرا فور إس بحالة المصنع صيانة توكيل منتظمة مع توجيه للعجلات الخلفية.',
                'price' => 7800000,
                'year' => 2022,
                'mileage' => 31000,
                'transmission' => 'automatic',
                'fuel' => 'petrol',
                'drivetrain' => 'awd',
                'color' => 'Chalk Grey',
                'condition' => 'used',
                'hp' => '440',
                'torque' => '550 Nm',
                'engine' => '2900cc V6 Bi-Turbo',
                'cylinders' => 6,
                'accel' => '4.3s',
                'weight' => '1950 kg',
                'is_featured' => false,
                'is_best_deal' => false,
                'show_on_home' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1614162692292-7ac56d7f7f1e?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=1000&q=80',
                ],
            ],

            // Toyota
            [
                'brand' => 'Toyota',
                'model' => 'Corolla',
                'title_en' => 'Toyota Corolla Active Plus 2024',
                'title_ar' => 'تويوتا كورولا أكتيف بلس 2024',
                'desc_en' => 'Brand new Toyota Corolla 1.6L Active Plus with smart keyless entry, rear camera, LED lights and cruise control.',
                'desc_ar' => 'تويوتا كورولا زيرو الفئة الثانية أكتيف بلس مع بصمة ودخول ذكي وكاميرا خلفية ومثبت سرعة.',
                'price' => 1480000,
                'year' => 2024,
                'mileage' => 100,
                'transmission' => 'automatic',
                'fuel' => 'petrol',
                'drivetrain' => 'fwd',
                'color' => 'Silver Metallic',
                'condition' => 'new',
                'hp' => '120',
                'torque' => '154 Nm',
                'engine' => '1600cc VVT-i',
                'cylinders' => 4,
                'accel' => '11.0s',
                'weight' => '1290 kg',
                'is_featured' => true,
                'is_best_deal' => true,
                'show_on_home' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1590362891991-f776e747a588?auto=format&fit=crop&w=1000&q=80',
                ],
            ],
            [
                'brand' => 'Toyota',
                'model' => 'Fortuner',
                'title_en' => 'Toyota Fortuner 4.0L V6 4x4 2023',
                'title_ar' => 'تويوتا فورتشنر 4000 سي سي 6 سلندر 2023',
                'desc_en' => 'Robust 7-seater SUV with full 4x4 off-road capabilities, leather seats, diff lock, power tailgate.',
                'desc_ar' => 'تويوتا فورتشنر الفئة الأعلى 4000 سي سي دفع رباعي 7 راكب مع ديف لوك وشنطة كهرباء وفرش جلد طبيعي.',
                'price' => 3450000,
                'year' => 2023,
                'mileage' => 26000,
                'transmission' => 'automatic',
                'fuel' => 'petrol',
                'drivetrain' => '4wd',
                'color' => 'Attitude Black',
                'condition' => 'used',
                'hp' => '235',
                'torque' => '376 Nm',
                'engine' => '4000cc V6 Dual VVT-i',
                'cylinders' => 6,
                'accel' => '9.0s',
                'weight' => '2110 kg',
                'is_featured' => false,
                'is_best_deal' => true,
                'show_on_home' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?auto=format&fit=crop&w=1000&q=80',
                ],
            ],

            // Hyundai
            [
                'brand' => 'Hyundai',
                'model' => 'Tucson',
                'title_en' => 'Hyundai Tucson NX4 1.6T Red Line 2024',
                'title_ar' => 'هيونداي توسان NX4 الفئة الخامسة ريد لاين 2024',
                'desc_en' => 'Top category Hyundai Tucson with panoramic roof, 360 camera, blind spot monitor, dual 10.25 screens.',
                'desc_ar' => 'هيونداي توسان أعلى فئة ريد لاين فرش نبيتي فابريكا بالكامل مع شاشتين 10.25 وبانوراما وكاميرات 360.',
                'price' => 2150000,
                'year' => 2024,
                'mileage' => 9000,
                'transmission' => 'automatic',
                'fuel' => 'petrol',
                'drivetrain' => 'fwd',
                'color' => 'Dark Knight Grey',
                'condition' => 'used',
                'hp' => '180',
                'torque' => '265 Nm',
                'engine' => '1600cc Turbo GDI',
                'cylinders' => 4,
                'accel' => '8.8s',
                'weight' => '1490 kg',
                'is_featured' => true,
                'is_best_deal' => true,
                'show_on_home' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&w=1000&q=80',
                ],
            ],
            [
                'brand' => 'Hyundai',
                'model' => 'Elantra',
                'title_en' => 'Hyundai Elantra CN7 Premium 2023',
                'title_ar' => 'هيونداي إلنترا CN7 الفئة السادسة بريميوم 2023',
                'desc_en' => 'Futuristic design Hyundai Elantra CN7 with wireless Android Auto/Apple CarPlay, sunroof and full safety pack.',
                'desc_ar' => 'إلنترا سي إن 7 بحالة ممتازة فابريكا بالكامل مع فتحة سقف وأنظمة أمان متطورة وحساسات ركن أمامية وخلفية.',
                'price' => 1420000,
                'year' => 2023,
                'mileage' => 38000,
                'transmission' => 'automatic',
                'fuel' => 'petrol',
                'drivetrain' => 'fwd',
                'color' => 'Fiery Red',
                'condition' => 'used',
                'hp' => '127',
                'torque' => '157 Nm',
                'engine' => '1600cc MPI',
                'cylinders' => 4,
                'accel' => '10.5s',
                'weight' => '1255 kg',
                'is_featured' => false,
                'is_best_deal' => true,
                'show_on_home' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&w=1000&q=80',
                ],
            ],

            // Kia
            [
                'brand' => 'Kia',
                'model' => 'Sportage',
                'title_en' => 'Kia Sportage GT-Line 1.6T AWD 2024',
                'title_ar' => 'كيا سبورتاج جي تي لاين 1.6 تيربو دفع كلي 2024',
                'desc_en' => 'Kia Sportage GT-Line with curved panoramic display, Harman Kardon audio, heated/ventilated seats and AWD.',
                'desc_ar' => 'كيا سبورتاج الفئة الأعلى جي تي لاين دفع كلي مع شاشة منحنية وتبريد وتسخين كراسي ونظام صوت فاخر.',
                'price' => 2450000,
                'year' => 2024,
                'mileage' => 6000,
                'transmission' => 'automatic',
                'fuel' => 'petrol',
                'drivetrain' => 'awd',
                'color' => 'Wolf Grey',
                'condition' => 'used',
                'hp' => '180',
                'torque' => '265 Nm',
                'engine' => '1600cc Turbo GDI',
                'cylinders' => 4,
                'accel' => '8.6s',
                'weight' => '1540 kg',
                'is_featured' => true,
                'is_best_deal' => false,
                'show_on_home' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1583121274602-3e2820c69888?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&w=1000&q=80',
                ],
            ],
            [
                'brand' => 'Kia',
                'model' => 'Cerato',
                'title_en' => 'Kia Grand Cerato Topline 2022',
                'title_ar' => 'كيا جراند سيراتو توب لاين 2022',
                'desc_en' => 'Popular sedan with full options, sunroof, touch screen, cruise control and alloy wheels.',
                'desc_ar' => 'كيا سيراتو أعلى فئة توب لاين فتحة سقف، بصمة، شاشة تاتش، جنوط سبور، فابريكا بالكامل.',
                'price' => 1150000,
                'year' => 2022,
                'mileage' => 45000,
                'transmission' => 'automatic',
                'fuel' => 'petrol',
                'drivetrain' => 'fwd',
                'color' => 'Gravity Blue',
                'condition' => 'used',
                'hp' => '128',
                'torque' => '155 Nm',
                'engine' => '1600cc MPI',
                'cylinders' => 4,
                'accel' => '10.8s',
                'weight' => '1270 kg',
                'is_featured' => false,
                'is_best_deal' => true,
                'show_on_home' => false,
                'images' => [
                    'https://images.unsplash.com/photo-1583121274602-3e2820c69888?auto=format&fit=crop&w=1000&q=80',
                ],
            ],

            // Audi
            [
                'brand' => 'Audi',
                'model' => 'A4',
                'title_en' => 'Audi A4 S-Line 40 TFSI 2023',
                'title_ar' => 'أودي A4 إس لاين 40 TFSI 2023',
                'desc_en' => 'Audi A4 S-Line with virtual cockpit plus, Matrix LED lights, sports suspension and Bang & Olufsen sound.',
                'desc_ar' => 'أودي A4 باقة إس لاين الرياضية عدادات رقمية فيرشوال كوكبيت وإضاءة ماتريكس ليد ونظام بانج آند أولفسن.',
                'price' => 2950000,
                'year' => 2023,
                'mileage' => 16000,
                'transmission' => 'automatic',
                'fuel' => 'petrol',
                'drivetrain' => 'fwd',
                'color' => 'Daytona Grey',
                'condition' => 'used',
                'hp' => '190',
                'torque' => '320 Nm',
                'engine' => '2000cc Turbo',
                'cylinders' => 4,
                'accel' => '7.3s',
                'weight' => '1515 kg',
                'is_featured' => true,
                'is_best_deal' => false,
                'show_on_home' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1603584173870-7f23fdae1b7a?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?auto=format&fit=crop&w=1000&q=80',
                ],
            ],
            [
                'brand' => 'Audi',
                'model' => 'Q8',
                'title_en' => 'Audi Q8 55 TFSI Quattro S-Line 2023',
                'title_ar' => 'أودي Q8 كواترو 55 TFSI إس لاين 2023',
                'desc_en' => 'Luxury flagship coupe-SUV with Quattro permanent AWD, air suspension, soft close doors and HD Matrix headlights.',
                'desc_ar' => 'أودي Q8 الفخمة بنظام الدفع الكلي كواترو وأبواب شفط ونظام تعليق هوائي وشاشات لمس متعددة.',
                'price' => 7400000,
                'year' => 2023,
                'mileage' => 19000,
                'transmission' => 'automatic',
                'fuel' => 'hybrid',
                'drivetrain' => 'awd',
                'color' => 'Dragon Orange',
                'condition' => 'used',
                'hp' => '340',
                'torque' => '500 Nm',
                'engine' => '3000cc V6 Turbo Mild-Hybrid',
                'cylinders' => 6,
                'accel' => '5.9s',
                'weight' => '2215 kg',
                'is_featured' => true,
                'is_best_deal' => false,
                'show_on_home' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1541348263662-e0c8de4259ba?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1603584173870-7f23fdae1b7a?auto=format&fit=crop&w=1000&q=80',
                ],
            ],

            // Land Rover
            [
                'brand' => 'Land Rover',
                'model' => 'Range Rover Sport',
                'title_en' => 'Range Rover Sport Dynamic SE P400 2024',
                'title_ar' => 'رينج روفر سبورت ديناميك SE موديل 2024',
                'desc_en' => 'The peak of British luxury and capability. Meridian 3D sound, Pixel LED, active noise cancellation.',
                'desc_ar' => 'رينج روفر سبورت 2024 بحالة الزيرو مع نظام صوت ميريديان ثلاثي الأبعاد وعزل ضوضاء فائق وفخامة لا تضاهى.',
                'price' => 11800000,
                'year' => 2024,
                'mileage' => 4000,
                'transmission' => 'automatic',
                'fuel' => 'hybrid',
                'drivetrain' => 'awd',
                'color' => 'Santorini Black',
                'condition' => 'used',
                'hp' => '400',
                'torque' => '550 Nm',
                'engine' => '3000cc Inline-6 Turbo MHEV',
                'cylinders' => 6,
                'accel' => '5.7s',
                'weight' => '2310 kg',
                'is_featured' => true,
                'is_best_deal' => false,
                'show_on_home' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1542282088-72c9c27ed0cd?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&w=1000&q=80',
                ],
            ],

            // Volkswagen
            [
                'brand' => 'Volkswagen',
                'model' => 'Passat',
                'title_en' => 'Volkswagen Passat R-Line 2022',
                'title_ar' => 'فولكس فاجن باسات آر لاين 2022',
                'desc_en' => 'Volkswagen Passat R-Line with panoramic roof, digital cockpit, massage driver seat and DCC suspension.',
                'desc_ar' => 'فولكس فاجن باسات أعلى فئة آر لاين فتحة سقف بانوراما، كرسي مساج، عدادات ديجيتال، فابريكا بالكامل.',
                'price' => 1750000,
                'year' => 2022,
                'mileage' => 35000,
                'transmission' => 'automatic',
                'fuel' => 'petrol',
                'drivetrain' => 'fwd',
                'color' => 'Pyrite Silver',
                'condition' => 'used',
                'hp' => '150',
                'torque' => '250 Nm',
                'engine' => '1400cc TSI Turbo',
                'cylinders' => 4,
                'accel' => '8.7s',
                'weight' => '1430 kg',
                'is_featured' => false,
                'is_best_deal' => true,
                'show_on_home' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1541899481282-d53bffe3c35d?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&w=1000&q=80',
                ],
            ],

            // Nissan
            [
                'brand' => 'Nissan',
                'model' => 'Qashqai',
                'title_en' => 'Nissan Qashqai Tekna Plus 2023',
                'title_ar' => 'نيسان قشقاي تيكنا بلس أعلى فئة 2023',
                'desc_en' => 'Nissan Qashqai Tekna Plus with ProPILOT semi-autonomous driving, Bose sound system, massage Nappa leather seats.',
                'desc_ar' => 'نيسان قشقاي الفئة الأعلى تيكنا بلس كراسي جلد نابا ومساج ونظام صوت بوز وقيادة شبه ذاتية بروبايلوت.',
                'price' => 1650000,
                'year' => 2023,
                'mileage' => 20000,
                'transmission' => 'automatic',
                'fuel' => 'petrol',
                'drivetrain' => 'fwd',
                'color' => 'Ceramic Pearl',
                'condition' => 'used',
                'hp' => '148',
                'torque' => '250 Nm',
                'engine' => '1332cc Turbo',
                'cylinders' => 4,
                'accel' => '9.2s',
                'weight' => '1385 kg',
                'is_featured' => false,
                'is_best_deal' => true,
                'show_on_home' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&w=1000&q=80',
                ],
            ],

            // Peugeot
            [
                'brand' => 'Peugeot',
                'model' => '3008',
                'title_en' => 'Peugeot 3008 GT-Pack 2023',
                'title_ar' => 'بيجو 3008 جي تي باك 2023',
                'desc_en' => 'French luxury styling with i-Cockpit, Focal premium audio, panoramic roof and red Nappa leather.',
                'desc_ar' => 'بيجو 3008 أعلى فئة جي تي باك فرش جلد نابا أحمر ونظام صوت فوكال وسقف بانوراما بالكامل.',
                'price' => 2050000,
                'year' => 2023,
                'mileage' => 15000,
                'transmission' => 'automatic',
                'fuel' => 'petrol',
                'drivetrain' => 'fwd',
                'color' => 'Celebes Blue',
                'condition' => 'used',
                'hp' => '165',
                'torque' => '240 Nm',
                'engine' => '1600cc THP Turbo',
                'cylinders' => 4,
                'accel' => '8.9s',
                'weight' => '1390 kg',
                'is_featured' => true,
                'is_best_deal' => false,
                'show_on_home' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&w=1000&q=80',
                ],
            ],

            // Jeep
            [
                'brand' => 'Jeep',
                'model' => 'Grand Cherokee',
                'title_en' => 'Jeep Grand Cherokee Limited 3.6L 2023',
                'title_ar' => 'جيب جراند شيروكي ليمتد 3600 سي سي 2023',
                'desc_en' => 'American icon with Quadra-Trac II 4x4, Selec-Terrain, Uconnect 5 10.1-inch screen and McIntosh audio.',
                'desc_ar' => 'جيب جراند شيروكي ليمتد دفع رباعي قوي مع شاشة 10.1 إنش ونظام صوت ماكنتوش الفاخر وفابريكا بالكامل.',
                'price' => 3950000,
                'year' => 2023,
                'mileage' => 24000,
                'transmission' => 'automatic',
                'fuel' => 'petrol',
                'drivetrain' => '4wd',
                'color' => 'Diamond Black',
                'condition' => 'used',
                'hp' => '293',
                'torque' => '352 Nm',
                'engine' => '3600cc Pentastar V6',
                'cylinders' => 6,
                'accel' => '7.9s',
                'weight' => '2160 kg',
                'is_featured' => true,
                'is_best_deal' => false,
                'show_on_home' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1541348263662-e0c8de4259ba?auto=format&fit=crop&w=1000&q=80',
                ],
            ],
        ];

        // All features to randomly attach
        $allFeatures = CarFeature::pluck('id')->all();

        $sellerArray = $allSellers->values()->all();
        $sellerCount = count($sellerArray);

        $cities = [$cairo, $giza, $alex, $mansoura];

        foreach ($carCatalog as $idx => $carItem) {
            $brand = Brand::where('name->en', $carItem['brand'])->orWhere('name->ar', $carItem['brand'])->first();
            if (!$brand) {
                continue;
            }

            $model = Model::where('brand_id', $brand->id)
                ->where(function ($q) use ($carItem) {
                    $q->where('name->en', 'like', '%' . $carItem['model'] . '%')
                      ->orWhere('name->ar', 'like', '%' . $carItem['model'] . '%');
                })->first();

            if (!$model) {
                $model = Model::where('brand_id', $brand->id)->first();
            }

            if (!$model) {
                continue;
            }

            $assignedSeller = $sellerArray[$idx % $sellerCount];
            $assignedCity = $cities[$idx % count($cities)];

            $car = Car::create([
                'seller_id' => $assignedSeller->id,
                'brand_id' => $brand->id,
                'model_id' => $model->id,
                'city_id' => $assignedCity->id,
                'governorate_id' => $assignedCity->governorate_id,
                'country_id' => 1,
                'type' => 'car',
                'title' => [
                    'en' => $carItem['title_en'],
                    'ar' => $carItem['title_ar'],
                ],
                'description' => [
                    'en' => $carItem['desc_en'],
                    'ar' => $carItem['desc_ar'],
                ],
                'price' => $carItem['price'],
                'currency' => 'EGP',
                'year' => $carItem['year'],
                'mileage' => $carItem['mileage'],
                'transmission' => $carItem['transmission'],
                'fuel_type' => $carItem['fuel'],
                'drivetrain' => $carItem['drivetrain'],
                'color' => $carItem['color'],
                'condition' => $carItem['condition'],
                'horsepower' => $carItem['hp'],
                'torque' => $carItem['torque'],
                'engine_capacity' => $carItem['engine'],
                'cylinders' => $carItem['cylinders'],
                'acceleration' => $carItem['accel'],
                'weight' => $carItem['weight'],
                'phone_number' => $assignedSeller->phone ?? '01012345678',
                'whatsapp_number' => $assignedSeller->phone ?? '01012345678',
                'is_best_deal' => $carItem['is_best_deal'],
                'is_import' => ($idx % 3 === 0),
                'is_featured' => $carItem['is_featured'],
                'show_on_home' => $carItem['show_on_home'],
                'is_global_ad' => ($idx % 4 === 0),
                'status' => 'approved',
            ]);

            // Add images
            foreach ($carItem['images'] as $imgIdx => $imgUrl) {
                CarImage::create([
                    'car_id' => $car->id,
                    'path' => $imgUrl,
                    'is_main' => ($imgIdx === 0),
                ]);
            }

            // Attach features
            if (!empty($allFeatures)) {
                $featureSampleCount = min(8, count($allFeatures));
                $randomFeatureIds = array_rand(array_flip($allFeatures), $featureSampleCount);
                if (!is_array($randomFeatureIds)) {
                    $randomFeatureIds = [$randomFeatureIds];
                }
                foreach ($randomFeatureIds as $fId) {
                    CarFeaturePivot::create([
                        'car_id' => $car->id,
                        'car_feature_id' => $fId,
                    ]);
                }
            }
        }
    }
}

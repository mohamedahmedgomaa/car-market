<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Http\Modules\Sellers\Models\Seller;
use App\Http\Modules\Brands\Models\Brand;
use App\Http\Modules\Cities\Models\City;
use App\Http\Modules\Cars\Models\Car;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed users
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // 2. Passport Clients & Initial Lookups
        $this->call(PassportClientsSeeder::class);
        $this->call(InitialDataSeeder::class);

        // 3. Seed Mock Sellers
        $seller1 = Seller::create([
            'name' => 'Negm Motors',
            'email' => 'negm-motors@example.com',
            'password' => bcrypt('password'),
            'phone' => '01234567890',
            'store_name' => ['en' => 'Negm Motors', 'ar' => 'نجم موتورز'],
            'store_description' => ['en' => 'Premium luxury car showroom in Cairo.', 'ar' => 'معرض سيارات فاخرة مميز في القاهرة.'],
            'address' => ['en' => 'Cairo, Egypt', 'ar' => 'القاهرة، مصر'],
            'is_verified' => true,
            'is_active' => true,
        ]);

        $seller2 = Seller::create([
            'name' => 'Al-Ahram Cars',
            'email' => 'ahram-cars@example.com',
            'password' => bcrypt('password'),
            'phone' => '01551552993',
            'store_name' => ['en' => 'Al-Ahram Cars', 'ar' => 'معرض الأهرام للسيارات'],
            'store_description' => ['en' => 'The best deal on used and new cars.', 'ar' => 'أفضل العروض على السيارات المستعملة والجديدة.'],
            'address' => ['en' => 'Giza, Egypt', 'ar' => 'الجيزة، مصر'],
            'is_verified' => true,
            'is_active' => true,
        ]);

        // 4. Seed Mock Cars
        $bmw = Brand::where('name->en', 'BMW')->first();
        $bmwModel = $bmw ? $bmw->models()->first() : null;

        $mercedes = Brand::where('name->en', 'Mercedes')->first();
        $mercModel = $mercedes ? $mercedes->models()->first() : null;

        $toyota = Brand::where('name->en', 'Toyota')->first();
        $toyotaModel = $toyota ? $toyota->models()->first() : null;

        $cairo = City::where('name->en', 'Cairo')->first();
        $giza = City::where('name->en', 'Giza')->first();

        if ($bmw && $bmwModel && $cairo) {
            Car::create([
                'seller_id' => $seller1->id,
                'brand_id' => $bmw->id,
                'model_id' => $bmwModel->id,
                'city_id' => $cairo->id,
                'country_id' => 1,
                'type' => 'car',
                'title' => ['en' => 'BMW 3 Series M Sport', 'ar' => 'بي إم دبليو الفئة الثالثة إم سبورت'],
                'description' => ['en' => 'Mint condition BMW 3 Series with full options and M Sport package.', 'ar' => 'بي إم دبليو الفئة الثالثة بحالة الزيرو مع كامل التجهيزات وباقة إم سبورت.'],
                'price' => 2850000,
                'year' => 2024,
                'mileage' => 12000,
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'drivetrain' => 'rwd',
                'condition' => 'used',
                'horsepower' => 258,
                'status' => 'approved',
            ]);
        }

        if ($mercedes && $mercModel && $cairo) {
            Car::create([
                'seller_id' => $seller1->id,
                'brand_id' => $mercedes->id,
                'model_id' => $mercModel->id,
                'city_id' => $cairo->id,
                'country_id' => 1,
                'type' => 'car',
                'title' => ['en' => 'Mercedes-Benz C-Class C200', 'ar' => 'مرسيدس-بنز سي كلاس C200'],
                'description' => ['en' => 'Brand new Mercedes C200 with panorama and ambient lighting.', 'ar' => 'مرسيدس C200 كسر زيرو مع فتحة سقف بانوراما وإضاءة محيطية.'],
                'price' => 3700000,
                'year' => 2025,
                'mileage' => 4500,
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'drivetrain' => 'awd',
                'condition' => 'used',
                'horsepower' => 204,
                'status' => 'approved',
            ]);
        }

        if ($toyota && $toyotaModel && $giza) {
            Car::create([
                'seller_id' => $seller2->id,
                'brand_id' => $toyota->id,
                'model_id' => $toyotaModel->id,
                'city_id' => $giza->id,
                'country_id' => 1,
                'type' => 'car',
                'title' => ['en' => 'Toyota Corolla Active Plus', 'ar' => 'تويوتا كورولا أكتيف بلس'],
                'description' => ['en' => 'Reliable and highly fuel-efficient Toyota Corolla.', 'ar' => 'تويوتا كورولا اقتصادية جداً في استهلاك الوقود واعتمادية للغاية.'],
                'price' => 1350000,
                'year' => 2023,
                'mileage' => 32000,
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'drivetrain' => 'fwd',
                'condition' => 'used',
                'horsepower' => 120,
                'status' => 'approved',
            ]);

            // Add a pending car to show different stats
            Car::create([
                'seller_id' => $seller2->id,
                'brand_id' => $toyota->id,
                'model_id' => $toyotaModel->id,
                'city_id' => $giza->id,
                'country_id' => 1,
                'type' => 'car',
                'title' => ['en' => 'Toyota Corolla (Pending Approval)', 'ar' => 'تويوتا كورولا (بانتظار الموافقة)'],
                'description' => ['en' => 'Pending approval toyota corolla.', 'ar' => 'سيارة تويوتا كورولا قيد المراجعة.'],
                'price' => 1300000,
                'year' => 2023,
                'mileage' => 15000,
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'drivetrain' => 'fwd',
                'condition' => 'used',
                'horsepower' => 120,
                'status' => 'pending',
            ]);
        }
    }
}

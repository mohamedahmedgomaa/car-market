<?php

namespace Tests\Feature;

use App\Http\Modules\Brands\Models\Brand;
use App\Http\Modules\CarFeatures\Models\CarFeature;
use App\Http\Modules\Cars\Models\Car;
use App\Http\Modules\Cities\Models\City;
use App\Http\Modules\Countries\Models\Country;
use App\Http\Modules\Governorates\Models\Governorate;
use App\Http\Modules\Models\Models\Model as CarModel;
use App\Http\Modules\Sellers\Models\Seller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CarCompareTest extends TestCase
{
    use RefreshDatabase;

    protected Seller $seller;
    protected Brand $brand;
    protected CarModel $carModel;
    protected Country $country;
    protected Governorate $governorate;
    protected City $city;

    protected function setUp(): void
    {
        parent::setUp();

        $this->country = Country::create(['name' => ['en' => 'Egypt', 'ar' => 'مصر']]);
        $this->governorate = Governorate::create([
            'name' => ['en' => 'Cairo', 'ar' => 'القاهرة'],
            'country_id' => $this->country->id,
        ]);
        $this->city = City::create([
            'name' => ['en' => 'Nasr City', 'ar' => 'مدينة نصر'],
            'country_id' => $this->country->id,
            'governorate_id' => $this->governorate->id,
        ]);
        $this->brand = Brand::create([
            'name' => ['en' => 'BMW', 'ar' => 'بي إم دبليو'],
            'type' => 'car',
        ]);
        $this->carModel = CarModel::create([
            'brand_id' => $this->brand->id,
            'name' => ['en' => 'X5', 'ar' => 'إكس 5'],
            'type' => 'car',
        ]);
        $this->seller = Seller::create([
            'name' => 'Seller One',
            'email' => 'seller@example.com',
            'password' => Hash::make('secret123'),
            'phone' => '01000000001',
            'store_name' => ['en' => 'Alpha Motors', 'ar' => 'ألفا موتورز'],
            'is_verified' => true,
            'is_active' => true,
            'city_id' => $this->city->id,
            'governorate_id' => $this->governorate->id,
        ]);
    }

    protected function makeCar(array $overrides = []): Car
    {
        return Car::create(array_merge([
            'seller_id' => $this->seller->id,
            'brand_id' => $this->brand->id,
            'model_id' => $this->carModel->id,
            'city_id' => $this->city->id,
            'governorate_id' => $this->governorate->id,
            'country_id' => $this->country->id,
            'type' => 'car',
            'title' => ['en' => 'BMW X5 2024', 'ar' => 'بي إم دبليو X5 2024'],
            'description' => ['en' => 'Clean SUV', 'ar' => 'سيارة نظيفة'],
            'price' => 4500000,
            'currency' => 'EGP',
            'year' => 2024,
            'mileage' => 10000,
            'transmission' => 'automatic',
            'fuel_type' => 'petrol',
            'drivetrain' => 'awd',
            'condition' => 'used',
            'horsepower' => '335',
            'engine_capacity' => '3000',
            'status' => 'approved',
        ], $overrides));
    }

    public function test_compare_returns_approved_cars_in_requested_order_with_features_and_no_view_increment(): void
    {
        $feature = CarFeature::create(['name' => ['en' => 'Sunroof', 'ar' => 'فتحة سقف']]);

        $car1 = $this->makeCar(['price' => 4200000, 'condition' => 'used']);
        $car2 = $this->makeCar(['price' => 5200000, 'condition' => 'new', 'mileage' => 0]);
        $pending = $this->makeCar(['status' => 'pending']);

        $car1->features()->attach($feature->id);

        $res = $this->getJson("/api/user/cars/compare?ids={$car2->id},{$car1->id},{$pending->id}");
        $res->assertStatus(200);

        $data = $res->json('data');
        $this->assertCount(2, $data);
        $this->assertSame($car2->id, $data[0]['id']);
        $this->assertSame($car1->id, $data[1]['id']);
        $this->assertCount(1, $data[1]['features']);
        $this->assertArrayNotHasKey('password', $data[0]['seller']);

        $this->assertSame(0, $car1->fresh()->views_count);
        $this->assertSame(0, $car2->fresh()->views_count);
    }

    public function test_compare_rejects_mixed_vehicle_types(): void
    {
        $car = $this->makeCar(['type' => 'car']);
        $moto = $this->makeCar(['type' => 'motorcycle']);

        $res = $this->getJson("/api/user/cars/compare?ids={$car->id},{$moto->id}");
        $res->assertStatus(422);
    }

    public function test_compare_requires_at_least_one_id(): void
    {
        $res = $this->getJson('/api/user/cars/compare');
        $res->assertStatus(422);
    }
}

<?php

namespace Tests\Feature;

use App\Http\Modules\Admins\Models\Admin;
use App\Http\Modules\Brands\Models\Brand;
use App\Http\Modules\Cars\Models\Car;
use App\Http\Modules\Cars\Models\CarPriceHistory;
use App\Http\Modules\Cities\Models\City;
use App\Http\Modules\Countries\Models\Country;
use App\Http\Modules\FavoriteCars\Models\FavoriteCar;
use App\Http\Modules\Governorates\Models\Governorate;
use App\Http\Modules\Models\Models\Model as CarModel;
use App\Http\Modules\Notifications\Models\Notification;
use App\Http\Modules\Sellers\Models\Seller;
use App\Http\Modules\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;
use Tests\TestCase;

class SellerAnalyticsAndSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected Seller $sellerA;
    protected Seller $sellerB;
    protected Admin $admin;
    protected User $user;
    protected Brand $brand;
    protected CarModel $carModel;
    protected Country $country;
    protected Governorate $governorate;
    protected City $city;
    protected Car $carA;

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

        $this->sellerA = Seller::create([
            'name' => 'Seller One',
            'email' => 'seller1@example.com',
            'password' => Hash::make('secret123'),
            'phone' => '01000000001',
            'store_name' => ['en' => 'Alpha Showroom', 'ar' => 'معرض ألفا'],
            'store_description' => ['en' => 'Best cars', 'ar' => 'أفضل السيارات'],
            'address' => ['en' => 'Cairo', 'ar' => 'القاهرة'],
            'bank_account' => 'EG123456789',
            'tax_number' => 'TAX-999',
            'is_verified' => false,
            'is_active' => true,
            'tier' => 'none',
            'city_id' => $this->city->id,
            'governorate_id' => $this->governorate->id,
        ]);

        $this->sellerB = Seller::create([
            'name' => 'Seller Two',
            'email' => 'seller2@example.com',
            'password' => Hash::make('secret123'),
            'phone' => '01000000002',
            'store_name' => ['en' => 'Beta Motors', 'ar' => 'معرض بيتا'],
            'is_verified' => false,
            'is_active' => true,
            'tier' => 'none',
        ]);

        $this->admin = Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'phone' => '01099999999',
            'role' => 'super_admin',
        ]);

        $this->user = User::create([
            'name' => 'Buyer User',
            'email' => 'buyer@example.com',
            'password' => Hash::make('buyer123'),
            'phone' => '01111111111',
        ]);

        $this->carA = Car::create([
            'seller_id' => $this->sellerA->id,
            'brand_id' => $this->brand->id,
            'model_id' => $this->carModel->id,
            'country_id' => $this->country->id,
            'governorate_id' => $this->governorate->id,
            'city_id' => $this->city->id,
            'type' => 'car',
            'title' => ['en' => 'BMW X5 2024', 'ar' => 'بي إم دبليو X5 2024'],
            'description' => ['en' => 'Mint condition', 'ar' => 'حالة ممتازة'],
            'price' => 3000000,
            'currency' => 'EGP',
            'year' => 2024,
            'mileage' => 12000,
            'transmission' => 'automatic',
            'fuel_type' => 'petrol',
            'drivetrain' => 'awd',
            'condition' => 'used',
            'status' => 'approved',
        ]);
    }

    public function test_seller_dto_hides_password_and_protects_sensitive_fields_from_public(): void
    {
        // Public user viewing seller A
        $publicRes = $this->getJson("/api/user/seller/{$this->sellerA->id}");
        $publicRes->assertStatus(200);
        $publicData = $publicRes->json('data');

        $this->assertArrayNotHasKey('password', $publicData);
        $this->assertNull($publicData['bank_account']);
        $this->assertNull($publicData['tax_number']);

        // Seller A viewing their own profile via /api/seller/auth/me
        Passport::actingAs($this->sellerA, [], 'api_seller');
        $meRes = $this->getJson('/api/seller/auth/me');
        $meRes->assertStatus(200);
        $meData = $meRes->json('data');

        $this->assertArrayNotHasKey('password', $meData);
        $this->assertEquals('EG123456789', $meData['bank_account']);
        $this->assertEquals('TAX-999', $meData['tax_number']);
    }

    public function test_seller_cannot_modify_another_seller_or_self_elevate_tier(): void
    {
        Passport::actingAs($this->sellerA, [], 'api_seller');

        // Attempt to update Seller B
        $resOther = $this->putJson("/api/seller/seller/{$this->sellerB->id}", [
            'name' => 'Hacked Name',
        ]);
        $resOther->assertStatus(403);

        // Attempt to self-verify and upgrade tier to diamond
        $resSelf = $this->putJson('/api/seller/auth/profile', [
            'name' => 'Updated Seller One',
            'is_verified' => true,
            'tier' => 'diamond',
        ]);
        $resSelf->assertStatus(200);

        $this->sellerA->refresh();
        $this->assertEquals('Updated Seller One', $this->sellerA->name);
        $this->assertFalse($this->sellerA->is_verified);
        $this->assertEquals('none', $this->sellerA->tier);
    }

    public function test_seller_cannot_update_or_delete_other_sellers_car_or_use_admin_status_endpoint(): void
    {
        Passport::actingAs($this->sellerB, [], 'api_seller');

        // Seller B tries to delete Seller A's car
        $delRes = $this->deleteJson("/api/seller/cars/{$this->carA->id}");
        $delRes->assertStatus(403);

        // Seller A tries to call Admin-only status endpoint
        Passport::actingAs($this->sellerA, [], 'api_seller');
        $statusRes = $this->patchJson("/api/seller/cars/{$this->carA->id}/status", [
            'status' => 'approved',
            'is_featured' => true,
        ]);
        $statusRes->assertStatus(403);
    }

    public function test_price_drop_logs_to_car_price_histories_and_notifies_favorited_users(): void
    {
        // User favorites Car A
        FavoriteCar::create([
            'user_id' => $this->user->id,
            'car_id' => $this->carA->id,
        ]);

        Passport::actingAs($this->sellerA, [], 'api_seller');

        // Seller A drops the price from 3,000,000 to 2,700,000
        $updateRes = $this->putJson("/api/seller/cars/{$this->carA->id}", [
            'brand_id' => $this->brand->id,
            'model_id' => $this->carModel->id,
            'country_id' => $this->country->id,
            'governorate_id' => $this->governorate->id,
            'city_id' => $this->city->id,
            'type' => 'car',
            'title_ar' => 'بي إم دبليو X5 2024',
            'title_en' => 'BMW X5 2024',
            'description_ar' => 'حالة ممتازة',
            'description_en' => 'Mint condition',
            'price' => 2700000,
            'currency' => 'EGP',
            'year' => 2024,
            'mileage' => 12000,
            'transmission' => 'automatic',
            'fuel_type' => 'petrol',
            'drivetrain' => 'awd',
            'condition' => 'used',
        ]);

        $updateRes->assertStatus(200);
        $this->assertTrue($updateRes->json('data.is_price_dropped'));
        $this->assertEquals(3000000, $updateRes->json('data.old_price'));

        // Verify car_price_histories record
        $history = CarPriceHistory::where('car_id', $this->carA->id)->first();
        $this->assertNotNull($history);
        $this->assertEquals(3000000, $history->old_price);
        $this->assertEquals(2700000, $history->new_price);
        $this->assertEquals(-300000, $history->price_difference);
        $this->assertEquals(-10.0, $history->change_percentage);

        // Verify User received price_drop notification
        $notif = Notification::where('user_id', $this->user->id)
            ->where('type', 'price_drop')
            ->first();
        $this->assertNotNull($notif);
    }

    public function test_seller_can_mark_car_as_sold_and_reports_reflect_accurate_metrics(): void
    {
        // Track events from public user
        $this->getJson("/api/user/cars/{$this->carA->id}")->assertStatus(200);
        $this->postJson('/api/user/track-event', [
            'event_type' => 'whatsapp_click',
            'car_id' => $this->carA->id,
        ])->assertStatus(200);
        $this->postJson('/api/user/track-event', [
            'event_type' => 'phone_click',
            'car_id' => $this->carA->id,
        ])->assertStatus(200);

        $this->carA->refresh();
        $this->assertEquals(1, $this->carA->views_count);
        $this->assertEquals(1, $this->carA->whatsapp_clicks_count);
        $this->assertEquals(1, $this->carA->phone_clicks_count);

        // Seller marks car as sold
        Passport::actingAs($this->sellerA, [], 'api_seller');
        $soldRes = $this->patchJson("/api/seller/cars/{$this->carA->id}/seller-status", [
            'status' => 'sold',
            'sold_price' => 2950000,
        ]);
        $soldRes->assertStatus(200);
        $this->assertEquals('sold', $soldRes->json('data.status'));
        $this->assertEquals(2950000, $soldRes->json('data.sold_price'));

        // Check Seller Overview Report
        $overviewRes = $this->getJson('/api/seller/reports/overview');
        $overviewRes->assertStatus(200);
        $this->assertEquals(1, $overviewRes->json('data.inventory.sold'));
        $this->assertEquals(1, $overviewRes->json('data.engagement.car_views'));
        $this->assertEquals(2, $overviewRes->json('data.engagement.total_leads'));

        // Check Performance Chart Report
        $chartRes = $this->getJson('/api/seller/reports/performance-chart?period=7d');
        $chartRes->assertStatus(200);
        $this->assertEquals(1, $chartRes->json('data.totals_in_period.car_views'));
        $this->assertEquals(1, $chartRes->json('data.totals_in_period.whatsapp_clicks'));
        $this->assertEquals(1, $chartRes->json('data.totals_in_period.phone_clicks'));

        // Check Cars Ranking Report
        $rankingRes = $this->getJson('/api/seller/reports/cars-ranking');
        $rankingRes->assertStatus(200);
        $this->assertCount(1, $rankingRes->json('data.top_performing'));

        // Check CSV Export
        $exportRes = $this->get('/api/seller/reports/export');
        $exportRes->assertStatus(200);
    }

    public function test_admin_rejecting_car_stores_reason_and_notifies_seller(): void
    {
        Passport::actingAs($this->admin, [], 'api_admin');

        $res = $this->patchJson("/api/admin/cars/{$this->carA->id}/status", [
            'status' => 'rejected',
            'rejection_reason' => 'Images are blurry, please upload clear photos.',
        ]);

        $res->assertStatus(200);
        $this->assertEquals('rejected', $res->json('data.status'));
        $this->assertEquals('Images are blurry, please upload clear photos.', $res->json('data.rejection_reason'));

        $sellerNotif = Notification::where('seller_id', $this->sellerA->id)
            ->where('type', 'car_rejected')
            ->first();
        $this->assertNotNull($sellerNotif);
    }
}


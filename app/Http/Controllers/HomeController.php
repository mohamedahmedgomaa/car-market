<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Modules\Cars\Models\Car;
use App\Http\Modules\Banners\Models\Banner;
use App\Http\Modules\Brands\Models\Brand;

use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Return ultra-fast consolidated home page data.
     * Only returns the strictly required card fields to optimize network payload and eliminate round trips.
     */
    public function index(Request $request): JsonResponse
    {
        $noCache = $request->boolean('nocache');

        $data = $noCache ? $this->getHomeData() : Cache::remember('home_page_payload', 30, fn () => $this->getHomeData());

        return response()->json([
            'status' => 200,
            'data' => $data,
        ]);
    }

    protected function getHomeData(): array
    {
        // 1. Hero Banners
        $heroBanners = Banner::query()
            ->where('is_active', true)
            ->where(function ($q) {
                $q->where('type', 'hero')->orWhere('type', 'slider');
            })
            ->select('id', 'image_path', 'link')
            ->take(5)
            ->get();

        if ($heroBanners->isEmpty()) {
            $heroBanners = Banner::query()
                ->where('is_active', true)
                ->select('id', 'image_path', 'link')
                ->take(5)
                ->get();
        }

        // 2. Video Banner
        $videoBanner = Banner::query()
            ->where('is_active', true)
            ->where('type', 'video')
            ->value('image_path') ?? 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4';

        // 3. Brands for search bar
        $brands = Brand::query()
            ->select('id', 'name', 'type')
            ->orderBy('name->en')
            ->get()
            ->map(function ($b) {
                return [
                    'id' => $b->id,
                    'name' => $b->getTranslations('name'),
                    'type' => $b->type,
                ];
            });

        // 4. Helper to fetch and format lightweight car cards
        $fetchCards = function ($callback) {
            $query = Car::query()
                ->where('status', 'approved')
                ->with([
                    'images' => fn ($q) => $q->where('is_main', true)->select('id', 'car_id', 'path', 'is_main'),
                    'brand:id,name',
                    'model:id,name',
                    'city:id,name',
                    'seller:id,name,store_name,store_logo,tier,is_verified',
                ])
                ->select([
                    'id', 'seller_id', 'brand_id', 'model_id', 'city_id',
                    'type', 'title', 'price', 'currency', 'year', 'mileage',
                    'condition', 'is_best_deal', 'is_featured', 'is_global_ad',
                    'is_import', 'show_on_home', 'status', 'created_at',
                ]);

            $callback($query);

            return $query->get()->map(function ($car) {
                $mainImg = $car->images->first()?->path;
                return [
                    'id' => $car->id,
                    'type' => $car->type,
                    'title' => $car->getTranslations('title'),
                    'price' => (float) $car->price,
                    'currency' => $car->currency ?? 'EGP',
                    'year' => (int) $car->year,
                    'mileage' => (int) $car->mileage,
                    'condition' => $car->condition,
                    'is_best_deal' => (bool) $car->is_best_deal,
                    'is_featured' => (bool) $car->is_featured,
                    'is_global_ad' => (bool) $car->is_global_ad,
                    'is_import' => (bool) $car->is_import,
                    'created_at' => (string) $car->created_at,
                    'images' => $mainImg ? [['path' => $mainImg, 'is_main' => 1]] : [],
                    'main_image' => $mainImg,
                    'brand' => $car->brand ? [
                        'id' => $car->brand->id,
                        'name' => $car->brand->getTranslations('name'),
                    ] : null,
                    'model' => $car->model ? [
                        'id' => $car->model->id,
                        'name' => $car->model->getTranslations('name'),
                    ] : null,
                    'city' => $car->city ? [
                        'id' => $car->city->id,
                        'name' => $car->city->getTranslations('name'),
                    ] : null,
                    'seller' => $car->seller ? [
                        'id' => $car->seller->id,
                        'name' => $car->seller->name,
                        'store_name' => $car->seller->getTranslations('store_name'),
                        'store_logo' => $car->seller->store_logo,
                        'tier' => $car->seller->tier ?? 'basic',
                        'is_verified' => (bool) $car->seller->is_verified,
                    ] : null,
                ];
            });
        };

        // Latest Cars (type: car, show_on_home: 1)
        $latestCars = $fetchCards(function ($q) {
            $q->where('type', 'car')->where('show_on_home', true)->latest('created_at')->limit(8);
        });

        // Latest Bikes (type: motorcycle, show_on_home: 1)
        $latestBikes = $fetchCards(function ($q) {
            $q->where('type', 'motorcycle')->where('show_on_home', true)->latest('created_at')->limit(8);
        });

        // Best Deals (is_best_deal: 1, sort: price asc)
        $bestDeals = $fetchCards(function ($q) {
            $q->where('is_best_deal', true)->orderBy('price', 'asc')->limit(8);
        });

        // Top Cars for footer (sort: price desc)
        $topCars = $fetchCards(function ($q) {
            $q->orderBy('price', 'desc')->limit(3);
        });

        return [
            'hero_banners' => $heroBanners,
            'video_banner' => $videoBanner,
            'brands' => $brands,
            'latest_cars' => $latestCars,
            'latest_bikes' => $latestBikes,
            'best_deals' => $bestDeals,
            'top_cars' => $topCars,
        ];
    }
}

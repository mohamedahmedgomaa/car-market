<?php

namespace App\Http\Modules\Sellers\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Modules\Cars\Models\Car;
use App\Http\Modules\Cars\Models\CarPriceHistory;
use App\Http\Modules\FavoriteCars\Models\FavoriteCar;
use App\Http\Modules\Sellers\Models\AnalyticsEvent;
use App\Http\Modules\Sellers\Models\Seller;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SellerReportController extends Controller
{
    /**
     * Resolve the target seller ID (from authenticated seller or admin request parameter).
     */
    protected function resolveSellerId(Request $request, ?int $routeSellerId = null): ?int
    {
        try {
            if (auth('api_admin')->check()) {
                return $routeSellerId ?? ($request->filled('seller_id') ? (int) $request->query('seller_id') : null);
            }
        } catch (\Throwable $e) {
            // Ignore guard error
        }

        try {
            if (auth('api_seller')->check()) {
                return (int) auth('api_seller')->id();
            }
        } catch (\Throwable $e) {
            // Ignore guard error
        }

        return null;
    }

    /**
     * 1. GET /seller/reports/overview
     * Comprehensive KPI cards, inventory breakdown, financials, engagement, conversion rate, and alerts.
     */
    public function overview(Request $request, ?int $sellerId = null): JsonResponse
    {
        $targetSellerId = $this->resolveSellerId($request, $sellerId);
        if (!$targetSellerId) {
            return response()->json(['status' => 403, 'message' => 'Seller ID is required.'], 403);
        }

        $seller = Seller::find($targetSellerId);
        if (!$seller) {
            return response()->json(['status' => 404, 'message' => 'Seller not found.'], 404);
        }

        $hasCounterCols = Schema::hasColumn('cars', 'views_count');

        // 1. Inventory status breakdown
        $statusCounts = Car::where('seller_id', $targetSellerId)
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $totalCars = array_sum($statusCounts);
        $approvedCars = (int) ($statusCounts['approved'] ?? 0);
        $pendingCars = (int) ($statusCounts['pending'] ?? 0);
        $rejectedCars = (int) ($statusCounts['rejected'] ?? 0);
        $soldCars = (int) ($statusCounts['sold'] ?? 0);
        $archivedCars = (int) ($statusCounts['archived'] ?? 0);

        $featuredCars = Car::where('seller_id', $targetSellerId)->where('is_featured', true)->count();
        $bestDealsCars = Car::where('seller_id', $targetSellerId)->where('is_best_deal', true)->count();

        $typeCounts = Car::where('seller_id', $targetSellerId)
            ->select('type', DB::raw('COUNT(*) as count'))
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();

        // 2. Financials & Sales
        $activeValueByCurrency = Car::where('seller_id', $targetSellerId)
            ->where('status', 'approved')
            ->select(DB::raw("COALESCE(currency, 'EGP') as currency"), DB::raw('SUM(price) as total_value'), DB::raw('COUNT(*) as count'))
            ->groupBy(DB::raw("COALESCE(currency, 'EGP')"))
            ->get();

        $hasSoldPrice = Schema::hasColumn('cars', 'sold_price');
        $hasSoldAt = Schema::hasColumn('cars', 'sold_at');

        $soldRevenueByCurrency = Car::where('seller_id', $targetSellerId)
            ->where('status', 'sold')
            ->select(
                DB::raw("COALESCE(currency, 'EGP') as currency"),
                DB::raw($hasSoldPrice ? 'SUM(COALESCE(sold_price, price)) as total_revenue' : 'SUM(price) as total_revenue'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy(DB::raw("COALESCE(currency, 'EGP')"))
            ->get();

        $soldThisMonthCount = Car::where('seller_id', $targetSellerId)
            ->where('status', 'sold')
            ->when($hasSoldAt, fn ($q) => $q->where('sold_at', '>=', now()->startOfMonth()), fn ($q) => $q->where('updated_at', '>=', now()->startOfMonth()))
            ->count();

        $avgDaysToSell = null;
        if ($hasSoldAt && $soldCars > 0) {
            $soldRecords = Car::where('seller_id', $targetSellerId)
                ->where('status', 'sold')
                ->whereNotNull('sold_at')
                ->get(['created_at', 'sold_at']);

            if ($soldRecords->isNotEmpty()) {
                $totalDays = $soldRecords->sum(fn ($c) => max(1, Carbon::parse($c->created_at)->diffInDays(Carbon::parse($c->sold_at))));
                $avgDaysToSell = round($totalDays / $soldRecords->count(), 1);
            }
        }

        // 3. Engagement & Conversion Metrics
        $carViews = 0;
        $whatsappClicks = 0;
        $phoneClicks = 0;
        $sharesCount = 0;

        if ($hasCounterCols) {
            $agg = Car::where('seller_id', $targetSellerId)
                ->selectRaw('
                    COALESCE(SUM(views_count), 0) as car_views,
                    COALESCE(SUM(whatsapp_clicks_count), 0) as whatsapp_clicks,
                    COALESCE(SUM(phone_clicks_count), 0) as phone_clicks,
                    COALESCE(SUM(shares_count), 0) as shares_count
                ')
                ->first();

            $carViews = (int) ($agg->car_views ?? 0);
            $whatsappClicks = (int) ($agg->whatsapp_clicks ?? 0);
            $phoneClicks = (int) ($agg->phone_clicks ?? 0);
            $sharesCount = (int) ($agg->shares_count ?? 0);
        }

        $profileViews = Schema::hasColumn('sellers', 'views_count') ? (int) ($seller->views_count ?? 0) : 0;
        $favoritesCount = FavoriteCar::whereIn('car_id', Car::where('seller_id', $targetSellerId)->select('id'))->count();

        $totalLeads = $whatsappClicks + $phoneClicks;
        $conversionRate = $carViews > 0 ? round(($totalLeads / $carViews) * 100, 2) : 0.0;

        // 4. Actionable Alerts
        $expiringAdsSoonCount = Car::where('seller_id', $targetSellerId)
            ->where('is_featured', true)
            ->whereNotNull('ad_expiry')
            ->whereBetween('ad_expiry', [now(), now()->addDays(7)])
            ->count();

        $staleCarsCount = Car::where('seller_id', $targetSellerId)
            ->where('status', 'approved')
            ->where('created_at', '<=', now()->subDays(30))
            ->count();

        return response()->json([
            'status' => 200,
            'data' => [
                'seller' => [
                    'id' => $seller->id,
                    'name' => $seller->name,
                    'store_name' => $seller->getTranslations('store_name'),
                    'tier' => $seller->tier ?? 'none',
                    'is_verified' => (bool) $seller->is_verified,
                ],
                'inventory' => [
                    'total' => $totalCars,
                    'approved' => $approvedCars,
                    'pending' => $pendingCars,
                    'rejected' => $rejectedCars,
                    'sold' => $soldCars,
                    'archived' => $archivedCars,
                    'featured' => $featuredCars,
                    'best_deals' => $bestDealsCars,
                    'by_type' => [
                        'car' => (int) ($typeCounts['car'] ?? 0),
                        'motorcycle' => (int) ($typeCounts['motorcycle'] ?? 0),
                    ],
                ],
                'financials' => [
                    'active_inventory_value' => $activeValueByCurrency,
                    'sold_revenue' => $soldRevenueByCurrency,
                    'sold_this_month_count' => $soldThisMonthCount,
                    'average_days_to_sell' => $avgDaysToSell,
                ],
                'engagement' => [
                    'total_views' => $carViews + $profileViews,
                    'car_views' => $carViews,
                    'profile_views' => $profileViews,
                    'whatsapp_clicks' => $whatsappClicks,
                    'phone_clicks' => $phoneClicks,
                    'total_leads' => $totalLeads,
                    'favorites_count' => $favoritesCount,
                    'shares_count' => $sharesCount,
                    'conversion_rate' => $conversionRate,
                ],
                'alerts' => [
                    'rejected_cars_count' => $rejectedCars,
                    'expiring_ads_soon_count' => $expiringAdsSoonCount,
                    'stale_cars_count' => $staleCarsCount,
                ],
            ],
        ]);
    }

    /**
     * 2. GET /seller/reports/performance-chart?period=7d|30d|90d|custom&from=YYYY-MM-DD&to=YYYY-MM-DD
     * Time-series chart data for views, leads, and device breakdown.
     */
    public function performanceChart(Request $request, ?int $sellerId = null): JsonResponse
    {
        $targetSellerId = $this->resolveSellerId($request, $sellerId);
        if (!$targetSellerId) {
            return response()->json(['status' => 403, 'message' => 'Seller ID is required.'], 403);
        }

        $period = $request->query('period', '30d');
        $endDate = now()->endOfDay();

        $startDate = match ($period) {
            '7d' => now()->subDays(6)->startOfDay(),
            '90d' => now()->subDays(89)->startOfDay(),
            'custom' => $request->filled('from')
                ? Carbon::parse($request->query('from'))->startOfDay()
                : now()->subDays(29)->startOfDay(),
            default => now()->subDays(29)->startOfDay(),
        };

        if ($period === 'custom' && $request->filled('to')) {
            $endDate = Carbon::parse($request->query('to'))->endOfDay();
        }

        $labels = [];
        $dateMap = [];
        foreach (CarbonPeriod::create($startDate, '1 day', $endDate) as $date) {
            $d = $date->format('Y-m-d');
            $labels[] = $d;
            $dateMap[$d] = [
                AnalyticsEvent::EVENT_CAR_VIEW => 0,
                AnalyticsEvent::EVENT_PROFILE_VIEW => 0,
                AnalyticsEvent::EVENT_WHATSAPP_CLICK => 0,
                AnalyticsEvent::EVENT_PHONE_CLICK => 0,
                AnalyticsEvent::EVENT_SHARE_CLICK => 0,
                AnalyticsEvent::EVENT_MAP_CLICK => 0,
                AnalyticsEvent::EVENT_SOCIAL_CLICK => 0,
            ];
        }

        $devicesBreakdown = [
            'mobile' => 0,
            'desktop' => 0,
            'tablet' => 0,
        ];

        if (Schema::hasTable('analytics_events')) {
            $carFilter = $request->filled('car_id') ? (int) $request->query('car_id') : null;

            $rows = AnalyticsEvent::query()
                ->where('seller_id', $targetSellerId)
                ->when($carFilter, fn ($q) => $q->where('car_id', $carFilter))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->select(
                    DB::raw('DATE(created_at) as event_date'),
                    'event_type',
                    DB::raw('COUNT(*) as total')
                )
                ->groupBy(DB::raw('DATE(created_at)'), 'event_type')
                ->get();

            foreach ($rows as $row) {
                $d = $row->event_date;
                $type = $row->event_type;
                if (isset($dateMap[$d][$type])) {
                    $dateMap[$d][$type] = (int) $row->total;
                }
            }

            $deviceRows = AnalyticsEvent::query()
                ->where('seller_id', $targetSellerId)
                ->when($carFilter, fn ($q) => $q->where('car_id', $carFilter))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->select('device_type', DB::raw('COUNT(*) as total'))
                ->groupBy('device_type')
                ->pluck('total', 'device_type')
                ->toArray();

            foreach ($deviceRows as $device => $count) {
                if (isset($devicesBreakdown[$device])) {
                    $devicesBreakdown[$device] = (int) $count;
                }
            }
        }

        $series = [
            'car_views' => array_column($dateMap, AnalyticsEvent::EVENT_CAR_VIEW),
            'profile_views' => array_column($dateMap, AnalyticsEvent::EVENT_PROFILE_VIEW),
            'whatsapp_clicks' => array_column($dateMap, AnalyticsEvent::EVENT_WHATSAPP_CLICK),
            'phone_clicks' => array_column($dateMap, AnalyticsEvent::EVENT_PHONE_CLICK),
            'share_clicks' => array_column($dateMap, AnalyticsEvent::EVENT_SHARE_CLICK),
            'map_clicks' => array_column($dateMap, AnalyticsEvent::EVENT_MAP_CLICK),
            'social_clicks' => array_column($dateMap, AnalyticsEvent::EVENT_SOCIAL_CLICK),
        ];

        $totalsInPeriod = array_map('array_sum', $series);

        return response()->json([
            'status' => 200,
            'data' => [
                'period' => [
                    'type' => $period,
                    'from' => $startDate->toDateString(),
                    'to' => $endDate->toDateString(),
                ],
                'labels' => $labels,
                'series' => $series,
                'totals_in_period' => $totalsInPeriod,
                'devices_breakdown' => $devicesBreakdown,
            ],
        ]);
    }

    /**
     * 3. GET /seller/reports/cars-ranking
     * Top performing cars, stale inventory needing attention, and brand performance distribution.
     * Uses optimized SQL-level limits & aggregations to prevent memory exhaustion with large inventories.
     */
    public function carsRanking(Request $request, ?int $sellerId = null): JsonResponse
    {
        $targetSellerId = $this->resolveSellerId($request, $sellerId);
        if (!$targetSellerId) {
            return response()->json(['status' => 403, 'message' => 'Seller ID is required.'], 403);
        }

        $limit = min(50, max(5, (int) $request->query('limit', 10)));

        // 1. Top performing cars (SQL ordered and limited)
        $topCars = Car::query()
            ->where('seller_id', $targetSellerId)
            ->where('status', 'approved')
            ->orderByRaw('(COALESCE(views_count, 0) + (COALESCE(whatsapp_clicks_count, 0) + COALESCE(phone_clicks_count, 0)) * 5) DESC')
            ->with([
                'brand:id,name',
                'model:id,name',
                'images' => fn ($q) => $q->orderByDesc('is_main')->orderBy('id')->select('id', 'car_id', 'path', 'is_main'),
            ])
            ->withCount(['favorites', 'images'])
            ->limit($limit)
            ->get();

        $topPerforming = $topCars->map(fn (Car $c) => $this->formatCarMetric($c));

        // 2. Stale / Needs Attention cars (SQL filtered and limited)
        $staleCars = Car::query()
            ->where('seller_id', $targetSellerId)
            ->where('status', 'approved')
            ->where(function ($q) {
                $q->where('created_at', '<=', now()->subDays(20))
                  ->orWhere(function ($sub) {
                      $sub->where('views_count', '>=', 20)
                          ->where('whatsapp_clicks_count', 0)
                          ->where('phone_clicks_count', 0);
                  });
            })
            ->orderBy('created_at', 'asc')
            ->with([
                'brand:id,name',
                'model:id,name',
                'images' => fn ($q) => $q->orderByDesc('is_main')->orderBy('id')->select('id', 'car_id', 'path', 'is_main'),
            ])
            ->withCount(['favorites', 'images'])
            ->limit($limit)
            ->get();

        if ($staleCars->isEmpty()) {
            $staleCars = Car::query()
                ->where('seller_id', $targetSellerId)
                ->where('status', 'approved')
                ->orderBy('created_at', 'asc')
                ->with([
                    'brand:id,name',
                    'model:id,name',
                    'images' => fn ($q) => $q->orderByDesc('is_main')->orderBy('id')->select('id', 'car_id', 'path', 'is_main'),
                ])
                ->withCount(['favorites', 'images'])
                ->limit($limit)
                ->get();
        }

        $staleInventory = $staleCars->map(fn (Car $c) => $this->formatCarMetric($c));

        // 3. Brand distribution (Aggregated directly in database)
        $brandDistribution = DB::table('cars')
            ->where('cars.seller_id', $targetSellerId)
            ->whereNotNull('cars.brand_id')
            ->join('brands', 'cars.brand_id', '=', 'brands.id')
            ->selectRaw('
                cars.brand_id,
                brands.name as brand_name,
                COUNT(*) as cars_count,
                SUM(CASE WHEN cars.status = "approved" THEN 1 ELSE 0 END) as approved_count,
                SUM(CASE WHEN cars.status = "sold" THEN 1 ELSE 0 END) as sold_count,
                COALESCE(SUM(cars.views_count), 0) as total_views,
                COALESCE(SUM(COALESCE(cars.whatsapp_clicks_count, 0) + COALESCE(cars.phone_clicks_count, 0)), 0) as total_leads
            ')
            ->groupBy('cars.brand_id', 'brands.name')
            ->orderByDesc('total_views')
            ->limit(6)
            ->get()
            ->map(function ($row) {
                $name = $row->brand_name;
                if (is_string($name) && str_starts_with($name, '{')) {
                    $decoded = json_decode($name, true);
                    $name = is_array($decoded) ? $decoded : ['en' => $name, 'ar' => $name];
                } else {
                    $name = ['en' => (string) $name, 'ar' => (string) $name];
                }

                return [
                    'brand' => [
                        'id' => $row->brand_id,
                        'name' => $name,
                    ],
                    'cars_count' => (int) $row->cars_count,
                    'approved_count' => (int) $row->approved_count,
                    'sold_count' => (int) $row->sold_count,
                    'total_views' => (int) $row->total_views,
                    'total_leads' => (int) $row->total_leads,
                    'total_favorites' => 0,
                ];
            });

        return response()->json([
            'status' => 200,
            'data' => [
                'top_performing' => $topPerforming,
                'needs_attention' => $staleInventory,
                'brand_distribution' => $brandDistribution,
            ],
        ]);
    }

    /**
     * Helper to format car metric card with recommendations.
     */
    private function formatCarMetric(Car $car): array
    {
        $views = (int) ($car->views_count ?? 0);
        $whatsapp = (int) ($car->whatsapp_clicks_count ?? 0);
        $phone = (int) ($car->phone_clicks_count ?? 0);
        $shares = (int) ($car->shares_count ?? 0);
        $favorites = (int) ($car->favorites_count ?? 0);
        $leads = $whatsapp + $phone;
        $conversionRate = $views > 0 ? round(($leads / $views) * 100, 2) : 0.0;
        $daysListed = (int) Carbon::parse($car->created_at)->diffInDays(now());
        $engagementScore = $views + ($leads * 5) + ($favorites * 3) + ($shares * 2);

        $recommendations = [];
        $imagesCount = $car->relationLoaded('images') ? $car->images->count() : (int) ($car->images_count ?? 0);
        if ($imagesCount < 4) {
            $recommendations[] = [
                'code' => 'add_more_photos',
                'ar' => 'أضف 4 صور على الأقل لزيادة المشاهدات بنسبة تصل إلى 60%',
                'en' => 'Add at least 4 photos to boost views by up to 60%',
            ];
        }
        if ($daysListed >= 30 && empty($car->old_price)) {
            $recommendations[] = [
                'code' => 'consider_price_drop',
                'ar' => 'مر أكثر من 30 يوماً على الإعلان، جرب تخفيض السعر للحصول على شارة "انخفض السعر" وإشعار المهتمين',
                'en' => 'Listed over 30 days ago; consider a slight price reduction to trigger Price Drop alerts',
            ];
        }
        if ($views >= 30 && $leads === 0) {
            $recommendations[] = [
                'code' => 'improve_description_or_price',
                'ar' => 'المشاهدات جيدة ولكن لا توجد نقرات تواصل؛ راجع تفاصيل الوصف أو تنافسية السعر',
                'en' => 'Good views but zero contact clicks; review description details or price competitiveness',
            ];
        }

        return [
            'id' => $car->id,
            'title' => $car->getTranslations('title'),
            'type' => $car->type,
            'status' => $car->status,
            'price' => (float) $car->price,
            'old_price' => $car->old_price ? (float) $car->old_price : null,
            'is_price_dropped' => !empty($car->old_price) && (float) $car->price < (float) $car->old_price,
            'sold_price' => $car->sold_price ? (float) $car->sold_price : null,
            'sold_at' => $car->sold_at,
            'currency' => $car->currency ?? 'EGP',
            'year' => (int) $car->year,
            'condition' => $car->condition,
            'is_featured' => (bool) $car->is_featured,
            'main_image' => $car->images->first()?->path,
            'images_count' => $imagesCount,
            'brand' => $car->brand ? [
                'id' => $car->brand->id,
                'name' => $car->brand->getTranslations('name'),
            ] : null,
            'model' => $car->model ? [
                'id' => $car->model->id,
                'name' => $car->model->getTranslations('name'),
            ] : null,
            'metrics' => [
                'views_count' => $views,
                'whatsapp_clicks_count' => $whatsapp,
                'phone_clicks_count' => $phone,
                'total_leads' => $leads,
                'favorites_count' => $favorites,
                'shares_count' => $shares,
                'conversion_rate' => $conversionRate,
                'engagement_score' => $engagementScore,
                'days_listed' => $daysListed,
            ],
            'recommendations' => $recommendations,
            'created_at' => $car->created_at,
        ];
    }

    /**
     * 4. GET /seller/reports/price-history
     * Returns the log of price changes from `car_price_histories` for the seller's cars.
     */
    public function priceHistory(Request $request, ?int $sellerId = null): JsonResponse
    {
        $targetSellerId = $this->resolveSellerId($request, $sellerId);
        if (!$targetSellerId) {
            return response()->json(['status' => 403, 'message' => 'Seller ID is required.'], 403);
        }

        if (!Schema::hasTable('car_price_histories')) {
            return response()->json(['status' => 200, 'data' => []]);
        }

        $query = CarPriceHistory::query()
            ->where('seller_id', $targetSellerId)
            ->when($request->filled('car_id'), fn ($q) => $q->where('car_id', (int) $request->query('car_id')))
            ->with([
                'car:id,title,brand_id,model_id,price,currency,status',
                'car.brand:id,name',
                'car.model:id,name',
                'car.images' => fn ($q) => $q->where('is_main', true)->select('id', 'car_id', 'path', 'is_main'),
            ])
            ->orderByDesc('created_at');

        $histories = $query->paginate((int) $request->query('per_page', 20));

        return response()->json([
            'status' => 200,
            'data' => $histories,
        ]);
    }

    /**
     * 5. GET /seller/reports/export
     * Exports the seller's full inventory performance report as an Excel-compatible UTF-8 CSV.
     */
    public function exportCsv(Request $request, ?int $sellerId = null): StreamedResponse|JsonResponse
    {
        $targetSellerId = $this->resolveSellerId($request, $sellerId);
        if (!$targetSellerId) {
            return response()->json(['status' => 403, 'message' => 'Seller ID is required.'], 403);
        }

        $fileName = 'seller_' . $targetSellerId . '_report_' . now()->format('Y_m_d') . '.csv';

        return response()->streamDownload(function () use ($targetSellerId) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for proper Arabic display in Excel
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'ID',
                'Title (AR)',
                'Title (EN)',
                'Brand',
                'Model',
                'Type',
                'Year',
                'Condition',
                'Status',
                'Current Price',
                'Previous Price',
                'Sold Price',
                'Currency',
                'Views',
                'WhatsApp Clicks',
                'Phone Clicks',
                'Total Leads',
                'Favorites',
                'Shares',
                'Conversion Rate (%)',
                'Days Listed',
                'Created At',
                'Sold At',
            ]);

            Car::query()
                ->where('seller_id', $targetSellerId)
                ->with(['brand:id,name', 'model:id,name'])
                ->withCount('favorites')
                ->orderByDesc('created_at')
                ->chunk(200, function ($cars) use ($handle) {
                    foreach ($cars as $car) {
                        $views = (int) ($car->views_count ?? 0);
                        $wa = (int) ($car->whatsapp_clicks_count ?? 0);
                        $ph = (int) ($car->phone_clicks_count ?? 0);
                        $leads = $wa + $ph;
                        $conv = $views > 0 ? round(($leads / $views) * 100, 2) : 0;
                        $daysListed = (int) Carbon::parse($car->created_at)->diffInDays(now());

                        fputcsv($handle, [
                            $car->id,
                            $car->getTranslation('title', 'ar', false),
                            $car->getTranslation('title', 'en', false),
                            $car->brand?->getTranslation('name', 'en', false) ?? '',
                            $car->model?->getTranslation('name', 'en', false) ?? '',
                            $car->type,
                            $car->year,
                            $car->condition,
                            $car->status,
                            $car->price,
                            $car->old_price ?? '',
                            $car->sold_price ?? '',
                            $car->currency ?? 'EGP',
                            $views,
                            $wa,
                            $ph,
                            $leads,
                            (int) ($car->favorites_count ?? 0),
                            (int) ($car->shares_count ?? 0),
                            $conv,
                            $daysListed,
                            (string) ($car->created_at ?? ''),
                            (string) ($car->sold_at ?? ''),
                        ]);
                    }
                });

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}

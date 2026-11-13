<?php

use App\Http\Controllers\AiSearchController;
use App\Http\Controllers\HomeController;
use App\Http\Modules\Admins\Controllers\AdminController;
use App\Http\Modules\Sellers\Controllers\AnalyticsTrackingController;
use App\Http\Modules\Sellers\Controllers\SellerController;
use App\Http\Modules\Sellers\Controllers\SellerReportController;
use App\Http\Modules\Users\Controllers\UserController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Dynamic Modules Routes Loader (with per-module middleware)
|--------------------------------------------------------------------------
| - كل موديول لازم يكون عنده: app/Http/Modules/{Module}/Route/index.php
| - تقدر تمرر modules كـ:
|   1) ['Cars','Brands']   => كلها هتاخد default middleware
|   2) ['Cars' => ['mw1','mw2'], 'Brands'] => Cars middleware خاص + Brands default
|
*/

$loadModuleRoutes = static function (
    array $modules,
    string $prefix,
    array|string|null $defaultMiddleware = null
) {
    foreach ($modules as $key => $value) {
        $module = is_string($key) ? $key : $value;
        $middleware = is_string($key) ? $value : $defaultMiddleware;

        $moduleFolder = trim($module, "/\\");
        $routeFile = base_path("app/Http/Modules/{$moduleFolder}/Route/index.php");

        if (!file_exists($routeFile)) {
            continue;
        }

        $route = Route::prefix($prefix);

        if (!empty($middleware)) {
            $route->middleware($middleware);
        }

        $route->group(function () use ($routeFile) {
            require $routeFile;
        });
    }
};

/** -------------------------
 * Admin Modules
 * ------------------------*/
$adminModules = [
    'Admins',
    'Sellers',
    'Users',
    'Cars',
    'Brands',
    'Countries',
    'Governorates',
    'Cities',
    'CarFeatures',
    'Models',
    'Banners',
    'Notifications',
    'Dashboard',
];

$loadModuleRoutes($adminModules, 'admin', 'auth:api_admin');

// Admin Seller Reports (allows Admin to inspect any Seller's analytics & reports)
Route::prefix('admin/sellers/{sellerId}/reports')
    ->middleware('auth:api_admin')
    ->group(function () {
        Route::get('overview', [SellerReportController::class, 'overview']);
        Route::get('performance-chart', [SellerReportController::class, 'performanceChart']);
        Route::get('cars-ranking', [SellerReportController::class, 'carsRanking']);
        Route::get('price-history', [SellerReportController::class, 'priceHistory']);
        Route::get('export', [SellerReportController::class, 'exportCsv']);
    });

// Admin Auth Routes (بدون auth على login/register)
Route::prefix('admin/auth')->group(function () {
    Route::post('register', [AdminController::class, 'register']);
    Route::post('login', [AdminController::class, 'login']);

    Route::middleware('auth:api_admin')->group(function () {
        Route::post('logout', [AdminController::class, 'logout']);
    });
});

/** -------------------------
 * Seller Modules
 * ------------------------*/
$sellerModules = [
    'Sellers',
    'Brands',
    'Models',
    'Categories',
    'Countries',
    'Governorates',
    'Cities',
    'CarFeatures',
    'Notifications',
    'Cars' => ['auth:api_seller', 'force.seller', 'force.filter.seller'],
];

$loadModuleRoutes($sellerModules, 'seller', 'auth:api_seller');

// Seller Reports & Analytics Routes
Route::prefix('seller/reports')
    ->middleware('auth:api_seller')
    ->group(function () {
        Route::get('overview', [SellerReportController::class, 'overview']);
        Route::get('performance-chart', [SellerReportController::class, 'performanceChart']);
        Route::get('cars-ranking', [SellerReportController::class, 'carsRanking']);
        Route::get('price-history', [SellerReportController::class, 'priceHistory']);
        Route::get('export', [SellerReportController::class, 'exportCsv']);
    });

// Seller Auth & Profile Routes
Route::prefix('seller/auth')->group(function () {
    Route::post('register', [SellerController::class, 'register']);
    Route::post('login', [SellerController::class, 'login']);

    Route::middleware('auth:api_seller')->group(function () {
        Route::post('logout', [SellerController::class, 'logout']);
        Route::get('me', [SellerController::class, 'me']);
        Route::post('me', [SellerController::class, 'me']);
        Route::post('profile', [SellerController::class, 'updateProfile']);
        Route::put('profile', [SellerController::class, 'updateProfile']);
    });
});

/** -------------------------
 * User Modules
 * ------------------------*/
$userModules = [
    'Sellers',
    'Users',
    'Brands',
    'Models',
    'Countries',
    'Governorates',
    'CarFeatures',
    'Cities',
    'FavoriteCars',
    'Notifications',
    'Cars',
    'Banners',
];

$loadModuleRoutes($userModules, 'user');

// Dedicated fast home page endpoint
Route::get('user/home', [HomeController::class, 'index']);

// Public/User interaction tracking endpoint (WhatsApp, Phone Call, Share, Map, Social clicks)
Route::middleware('throttle:60,1')->post('user/track-event', [AnalyticsTrackingController::class, 'store']);

// User Auth Routes
Route::prefix('user/auth')->group(function () {
    Route::post('register', [UserController::class, 'register']);
    Route::post('login', [UserController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [UserController::class, 'logout']);
        Route::post('me', [UserController::class, 'me']);
    });
});

Route::middleware('throttle:10,1')->get(
    '/ai/search',
    [AiSearchController::class, 'search']
);

Route::get('/__migrate_pgsql_to_sqlite', static function () {
    if (request('key') !== env('MIGRATION_KEY')) {
        abort(403);
    }

    if (DB::connection('sqlite')->table('users')->count() > 0) {
        return '❌ Migration already done';
    }

    Artisan::call('db:migrate-pgsql-to-sqlite');

    return nl2br(Artisan::output());
});

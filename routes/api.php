<?php

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

        // لو associative: 'Cars' => ['mw...']
        // لو indexed: 0 => 'Cars'
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
    'Cities',
    'CarFeatures',
    'Models',
];

$loadModuleRoutes($adminModules, 'admin', 'auth:api_admin');

// Admin Auth Routes (بدون auth على login/register)
Route::prefix('admin/auth')->group(function () {
    Route::post('register', [\App\Http\Modules\Admins\Controllers\AdminController::class, 'register']);
    Route::post('login', [\App\Http\Modules\Admins\Controllers\AdminController::class, 'login']);

    Route::middleware('auth:api_admin')->group(function () {
        Route::post('logout', [\App\Http\Modules\Admins\Controllers\AdminController::class, 'logout']);
        // Route::get('me', ...); // لو عندك
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
    'Cities',
    'CarFeatures',
    'Cars' => ['auth:api_seller', 'force.seller', 'force.filter.seller'],
];

$loadModuleRoutes($sellerModules, 'seller', 'auth:api_seller');

// Seller Auth Routes
Route::prefix('seller/auth')->group(function () {
    Route::post('register', [\App\Http\Modules\Sellers\Controllers\SellerController::class, 'register']);
    Route::post('login', [\App\Http\Modules\Sellers\Controllers\SellerController::class, 'login']);

    Route::middleware('auth:api_seller')->group(function () {
        Route::post('logout', [\App\Http\Modules\Sellers\Controllers\SellerController::class, 'logout']);
        // Route::get('me', ...); // لو عندك
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
    'CarFeatures',
    'Cities',
    'FavoriteCars',
    'Notifications',
    'Cars',
];

$loadModuleRoutes($userModules, 'user');

// User Auth Routes
Route::prefix('user/auth')->group(function () {
    Route::post('register', [\App\Http\Modules\Users\Controllers\UserController::class, 'register']);
    Route::post('login', [\App\Http\Modules\Users\Controllers\UserController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [\App\Http\Modules\Users\Controllers\UserController::class, 'logout']);
        Route::post('me', [\App\Http\Modules\Users\Controllers\UserController::class, 'me']);
    });
});


use App\Http\Controllers\AiSearchController;

Route::middleware('throttle:10,1')->get(
    '/ai/search',
    [AiSearchController::class, 'search']
);

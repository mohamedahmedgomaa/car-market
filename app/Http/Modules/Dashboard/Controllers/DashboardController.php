<?php

namespace App\Http\Modules\Dashboard\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Modules\Cars\Models\Car;
use App\Http\Modules\Users\Models\User;
use App\Http\Modules\Sellers\Models\Seller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        $totalCars = Car::count();
        $pendingCars = Car::where('status', 'pending')->count();
        $approvedCars = Car::where('status', 'approved')->count();
        
        $totalUsers = User::count();
        $totalSellers = Seller::count();
        
        // Stats by brand (Top 5)
        $brandStats = Car::select('brand_id', DB::raw('count(*) as total'))
            ->with('brand:id,name')
            ->groupBy('brand_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Recent cars
        $recentCars = Car::with(['brand', 'model', 'seller'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => [
                    'cars' => [
                        'total' => $totalCars,
                        'pending' => $pendingCars,
                        'approved' => $approvedCars,
                    ],
                    'users' => [
                        'total' => $totalUsers,
                    ],
                    'sellers' => [
                        'total' => $totalSellers,
                    ],
                ],
                'brandStats' => $brandStats,
                'recentCars' => $recentCars,
            ]
        ]);
    }
}

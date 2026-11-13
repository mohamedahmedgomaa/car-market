<?php

namespace App\Http\Modules\Sellers\Services;

use App\Http\Modules\Cars\Models\Car;
use App\Http\Modules\Sellers\Models\AnalyticsEvent;
use App\Http\Modules\Sellers\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AnalyticsTrackerService
{
    /**
     * Track a user interaction or view event safely without failing the main request.
     */
    public function track(
        string $eventType,
        ?int $sellerId = null,
        ?int $carId = null,
        ?Request $request = null,
        array $metadata = []
    ): bool {
        try {
            if (!in_array($eventType, AnalyticsEvent::ALLOWED_EVENTS, true)) {
                return false;
            }

            $request = $request ?? request();

            // Do not count seller's own views/clicks on their own inventory
            $loggedSeller = null;
            try {
                if ($request->bearerToken()) {
                    $loggedSeller = auth('api_seller')->user();
                }
            } catch (\Throwable $e) {
                $loggedSeller = null;
            }

            if ($loggedSeller && $sellerId && (int) $loggedSeller->id === (int) $sellerId) {
                return false;
            }

            if (!$sellerId && $carId) {
                $sellerId = Car::where('id', $carId)->value('seller_id');
            }

            if (!$sellerId) {
                return false;
            }

            $ip = (string) ($request->ip() ?? '0.0.0.0');
            $userAgent = substr((string) ($request->userAgent() ?? ''), 0, 255);
            $ipHash = hash('sha256', $ip . '|' . substr($userAgent, 0, 80));
            $deviceType = $this->detectDeviceType($userAgent);
            $userId = null;
            try {
                if ($request->bearerToken()) {
                    $userId = auth('api')->id();
                }
            } catch (\Throwable $e) {
                $userId = null;
            }

            // Deduplicate views within 30 minutes & clicks within 2 minutes from the same IP/UA
            $cooldownMinutes = in_array($eventType, [AnalyticsEvent::EVENT_CAR_VIEW, AnalyticsEvent::EVENT_PROFILE_VIEW], true)
                ? 30
                : 2;

            if (Schema::hasTable('analytics_events')) {
                $recentExists = AnalyticsEvent::query()
                    ->where('seller_id', $sellerId)
                    ->where('event_type', $eventType)
                    ->when($carId, fn ($q) => $q->where('car_id', $carId), fn ($q) => $q->whereNull('car_id'))
                    ->where('ip_hash', $ipHash)
                    ->where('created_at', '>=', now()->subMinutes($cooldownMinutes))
                    ->exists();

                if ($recentExists) {
                    return false;
                }

                AnalyticsEvent::create([
                    'seller_id' => $sellerId,
                    'car_id' => $carId,
                    'user_id' => $userId,
                    'event_type' => $eventType,
                    'ip_hash' => $ipHash,
                    'device_type' => $deviceType,
                    'user_agent' => $userAgent,
                    'metadata' => !empty($metadata) ? $metadata : null,
                    'created_at' => now(),
                ]);
            }

            // Increment cached counters on cars / sellers
            $this->incrementCounters($eventType, $sellerId, $carId);

            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    protected function incrementCounters(string $eventType, int $sellerId, ?int $carId): void
    {
        if ($eventType === AnalyticsEvent::EVENT_PROFILE_VIEW) {
            if (Schema::hasColumn('sellers', 'views_count')) {
                Seller::where('id', $sellerId)->increment('views_count');
            }
            return;
        }

        if (!$carId) {
            return;
        }

        $columnMap = [
            AnalyticsEvent::EVENT_CAR_VIEW => 'views_count',
            AnalyticsEvent::EVENT_WHATSAPP_CLICK => 'whatsapp_clicks_count',
            AnalyticsEvent::EVENT_PHONE_CLICK => 'phone_clicks_count',
            AnalyticsEvent::EVENT_SHARE_CLICK => 'shares_count',
        ];

        $column = $columnMap[$eventType] ?? null;
        if ($column && Schema::hasColumn('cars', $column)) {
            Car::where('id', $carId)->increment($column);
        }
    }

    protected function detectDeviceType(string $userAgent): string
    {
        $ua = strtolower($userAgent);
        if (str_contains($ua, 'ipad') || str_contains($ua, 'tablet')) {
            return 'tablet';
        }
        if (str_contains($ua, 'mobile') || str_contains($ua, 'android') || str_contains($ua, 'iphone')) {
            return 'mobile';
        }

        return 'desktop';
    }
}

<?php

namespace App\Http\Modules\Sellers\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Modules\Sellers\Models\AnalyticsEvent;
use App\Http\Modules\Sellers\Services\AnalyticsTrackerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AnalyticsTrackingController extends Controller
{
    public function __construct(protected AnalyticsTrackerService $tracker)
    {
    }

    /**
     * POST /api/user/track-event
     * Body: { "event_type": "whatsapp_click|phone_click|share_click|map_click|social_click|car_view|profile_view", "car_id": 123, "seller_id": 45, "metadata": {} }
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'event_type' => ['required', 'string', Rule::in(AnalyticsEvent::ALLOWED_EVENTS)],
            'seller_id' => ['nullable', 'integer', 'exists:sellers,id'],
            'car_id' => ['nullable', 'integer', 'exists:cars,id'],
            'metadata' => ['nullable', 'array'],
        ]);

        if (empty($validated['seller_id']) && empty($validated['car_id'])) {
            return response()->json([
                'status' => 422,
                'message' => 'Either seller_id or car_id must be provided.',
            ], 422);
        }

        $tracked = $this->tracker->track(
            $validated['event_type'],
            isset($validated['seller_id']) ? (int) $validated['seller_id'] : null,
            isset($validated['car_id']) ? (int) $validated['car_id'] : null,
            $request,
            $validated['metadata'] ?? []
        );

        return response()->json([
            'status' => 200,
            'tracked' => $tracked,
        ]);
    }
}


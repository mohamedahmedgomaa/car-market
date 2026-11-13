<?php

namespace App\Http\Modules\Notifications\Services;

use App\Http\Modules\Notifications\Dtos\NotificationDto;
use App\Http\Modules\Notifications\Mappers\NotificationMapper;
use App\Http\Modules\Notifications\Models\Notification;
use App\Http\Modules\Notifications\Repositories\NotificationRepository;
use Gomaa\Base\Base\Requests\BaseRequest;
use Gomaa\Base\Base\Services\BaseApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Schema;

class NotificationService extends BaseApiService
{
    protected string $dtoClass = NotificationDto::class;
    protected string $mapperClass = NotificationMapper::class;

    public function __construct(NotificationRepository $repository)
    {
        parent::__construct($repository);
    }

    public function index(BaseRequest $request): JsonResponse
    {
        // Scope notifications automatically for logged-in Seller or User if not Admin
        if (!auth('api_admin')->check()) {
            $filter = (array) $request->query('filter', []);
            if (auth('api_seller')->check()) {
                $filter['seller_id'] = auth('api_seller')->id();
            } elseif (auth('api')->check()) {
                $filter['user_id'] = auth('api')->id();
            }
            $request->query->set('filter', $filter);
        }

        return parent::index($request);
    }

    public function markAsRead(int $id): JsonResponse
    {
        $notification = Notification::find($id);
        if (!$notification) {
            return $this->responseWithError('Notification not found', 404);
        }

        if (auth('api_seller')->check() && (int) $notification->seller_id !== (int) auth('api_seller')->id()) {
            return $this->responseWithError('Forbidden', 403);
        }

        if (auth('api')->check() && !auth('api_seller')->check() && (int) $notification->user_id !== (int) auth('api')->id()) {
            return $this->responseWithError('Forbidden', 403);
        }

        $notification->update(['is_read' => true]);

        return $this->responseWithData($this->toDto($notification->fresh()), 200);
    }

    public function markAllAsRead(): JsonResponse
    {
        $query = Notification::query()->where('is_read', false);

        if (auth('api_seller')->check()) {
            $query->where('seller_id', auth('api_seller')->id());
        } elseif (auth('api')->check()) {
            $query->where('user_id', auth('api')->id());
        } else {
            return $this->responseWithError('Unauthenticated', 401);
        }

        $updated = $query->update(['is_read' => true]);

        return $this->responseWithData(['updated_count' => $updated], 200);
    }

    public static function notifySeller(
        int $sellerId,
        array $title,
        array $body,
        string $type = 'general',
        array $data = []
    ): ?Notification {
        try {
            if (!Schema::hasTable('notifications') || !Schema::hasColumn('notifications', 'seller_id')) {
                return null;
            }

            return Notification::create([
                'seller_id' => $sellerId,
                'type' => $type,
                'title' => $title,
                'body' => $body,
                'data' => !empty($data) ? $data : null,
                'is_read' => false,
            ]);
        } catch (\Throwable $e) {
            return null;
        }
    }

    public static function notifyUser(
        int $userId,
        array $title,
        array $body,
        string $type = 'general',
        array $data = []
    ): ?Notification {
        try {
            if (!Schema::hasTable('notifications')) {
                return null;
            }

            $payload = [
                'user_id' => $userId,
                'title' => $title,
                'body' => $body,
                'is_read' => false,
            ];

            if (Schema::hasColumn('notifications', 'type')) {
                $payload['type'] = $type;
            }
            if (Schema::hasColumn('notifications', 'data')) {
                $payload['data'] = !empty($data) ? $data : null;
            }

            return Notification::create($payload);
        } catch (\Throwable $e) {
            return null;
        }
    }
}

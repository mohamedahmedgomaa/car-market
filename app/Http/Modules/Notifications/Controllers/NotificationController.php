<?php

namespace App\Http\Modules\Notifications\Controllers;

use App\Http\Modules\Notifications\Requests\CreateNotificationRequest;
use App\Http\Modules\Notifications\Requests\DeleteNotificationRequest;
use App\Http\Modules\Notifications\Requests\ListNotificationRequest;
use App\Http\Modules\Notifications\Requests\ShowNotificationRequest;
use App\Http\Modules\Notifications\Requests\UpdateNotificationRequest;
use App\Http\Modules\Notifications\Services\NotificationService;
use Gomaa\Base\Base\Controllers\BaseApiController;
use Illuminate\Http\JsonResponse;

class NotificationController extends BaseApiController
{
    public function __construct(NotificationService $service)
    {
        parent::__construct($service, [
            'index' => ListNotificationRequest::class,
            'show' => ShowNotificationRequest::class,
            'store' => CreateNotificationRequest::class,
            'update' => UpdateNotificationRequest::class,
            'destroy' => DeleteNotificationRequest::class,
        ]);
    }

    public function markAsRead(int $id): JsonResponse
    {
        return $this->service->markAsRead($id);
    }

    public function markAllAsRead(): JsonResponse
    {
        return $this->service->markAllAsRead();
    }
}

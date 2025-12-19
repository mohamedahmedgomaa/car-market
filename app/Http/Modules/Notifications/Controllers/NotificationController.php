<?php

namespace App\Http\Modules\Notifications\Controllers;


use Gomaa\Base\Base\Controllers\BaseApiController;
use App\Http\Modules\Notifications\Requests\CreateNotificationRequest;
use App\Http\Modules\Notifications\Requests\DeleteNotificationRequest;
use App\Http\Modules\Notifications\Requests\ListNotificationRequest;
use App\Http\Modules\Notifications\Requests\ShowNotificationRequest;
use App\Http\Modules\Notifications\Requests\UpdateNotificationRequest;
use App\Http\Modules\Notifications\Services\NotificationService;

class NotificationController extends BaseApiController
{

    /**
     * @param NotificationService $service
     */
    public function __construct(NotificationService $service)
    {
        parent::__construct($service,[
            'index' => ListNotificationRequest::class,
            'show' => ShowNotificationRequest::class,
            'store' => CreateNotificationRequest::class,
            'update' => UpdateNotificationRequest::class,
            'destroy' => DeleteNotificationRequest::class,
        ]);
    }

}

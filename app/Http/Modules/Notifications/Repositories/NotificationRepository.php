<?php

namespace App\Http\Modules\Notifications\Repositories;

use Gomaa\Base\Base\Repositories\BaseApiRepository;
use App\Http\Modules\Notifications\Models\Notification;

class NotificationRepository extends BaseApiRepository
{
    /**
     * Examples constructor.
     *
     * @param Notification $model
     */
    public function __construct(Notification $model)
    {
        $this->model = $model;
    }

}

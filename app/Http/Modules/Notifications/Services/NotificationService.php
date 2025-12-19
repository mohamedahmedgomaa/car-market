<?php

namespace App\Http\Modules\Notifications\Services;

use Gomaa\Base\Base\Services\BaseApiService;
use App\Http\Modules\Notifications\Repositories\NotificationRepository;
use App\Http\Modules\Notifications\Mappers\NotificationMapper;
use App\Http\Modules\Notifications\Dtos\NotificationDto;

class NotificationService extends BaseApiService
{
    protected string $dtoClass = NotificationDto::class;
    protected string $mapperClass = NotificationMapper::class;

    /**
     * @param NotificationRepository $repository
     */
    public function __construct(NotificationRepository $repository)
    {
        parent::__construct($repository);
    }
}

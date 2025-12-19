<?php

namespace App\Http\Modules\CarImages\Services;

use Gomaa\Base\Base\Services\BaseApiService;
use App\Http\Modules\CarImages\Repositories\CarImageRepository;
use App\Http\Modules\CarImages\Mappers\CarImageMapper;
use App\Http\Modules\CarImages\Dtos\CarImageDto;

class CarImageService extends BaseApiService
{
    protected string $dtoClass = CarImageDto::class;
    protected string $mapperClass = CarImageMapper::class;

    /**
     * @param CarImageRepository $repository
     */
    public function __construct(CarImageRepository $repository)
    {
        parent::__construct($repository);
    }
}

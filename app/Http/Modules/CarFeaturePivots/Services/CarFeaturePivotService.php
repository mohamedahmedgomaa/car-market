<?php

namespace App\Http\Modules\CarFeaturePivots\Services;

use Gomaa\Base\Base\Services\BaseApiService;
use App\Http\Modules\CarFeaturePivots\Repositories\CarFeaturePivotRepository;
use App\Http\Modules\CarFeaturePivots\Mappers\CarFeaturePivotMapper;
use App\Http\Modules\CarFeaturePivots\Dtos\CarFeaturePivotDto;

class CarFeaturePivotService extends BaseApiService
{
    protected string $dtoClass = CarFeaturePivotDto::class;
    protected string $mapperClass = CarFeaturePivotMapper::class;

    /**
     * @param CarFeaturePivotRepository $repository
     */
    public function __construct(CarFeaturePivotRepository $repository)
    {
        parent::__construct($repository);
    }
}

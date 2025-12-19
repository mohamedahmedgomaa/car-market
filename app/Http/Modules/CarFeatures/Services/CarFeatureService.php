<?php

namespace App\Http\Modules\CarFeatures\Services;

use Gomaa\Base\Base\Services\BaseApiService;
use App\Http\Modules\CarFeatures\Repositories\CarFeatureRepository;
use App\Http\Modules\CarFeatures\Mappers\CarFeatureMapper;
use App\Http\Modules\CarFeatures\Dtos\CarFeatureDto;

class CarFeatureService extends BaseApiService
{
    protected string $dtoClass = CarFeatureDto::class;
    protected string $mapperClass = CarFeatureMapper::class;

    /**
     * @param CarFeatureRepository $repository
     */
    public function __construct(CarFeatureRepository $repository)
    {
        parent::__construct($repository);
    }
}

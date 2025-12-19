<?php

namespace App\Http\Modules\CarFeatures\Repositories;

use Gomaa\Base\Base\Repositories\BaseApiRepository;
use App\Http\Modules\CarFeatures\Models\CarFeature;

class CarFeatureRepository extends BaseApiRepository
{
    /**
     * Examples constructor.
     *
     * @param CarFeature $model
     */
    public function __construct(CarFeature $model)
    {
        $this->model = $model;
    }

}

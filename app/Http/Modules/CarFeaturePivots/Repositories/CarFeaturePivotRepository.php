<?php

namespace App\Http\Modules\CarFeaturePivots\Repositories;

use Gomaa\Base\Base\Repositories\BaseApiRepository;
use App\Http\Modules\CarFeaturePivots\Models\CarFeaturePivot;

class CarFeaturePivotRepository extends BaseApiRepository
{
    /**
     * Examples constructor.
     *
     * @param CarFeaturePivot $model
     */
    public function __construct(CarFeaturePivot $model)
    {
        $this->model = $model;
    }

}

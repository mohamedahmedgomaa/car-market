<?php

namespace App\Http\Modules\CarImages\Repositories;

use Gomaa\Base\Base\Repositories\BaseApiRepository;
use App\Http\Modules\CarImages\Models\CarImage;

class CarImageRepository extends BaseApiRepository
{
    /**
     * Examples constructor.
     *
     * @param CarImage $model
     */
    public function __construct(CarImage $model)
    {
        $this->model = $model;
    }

}

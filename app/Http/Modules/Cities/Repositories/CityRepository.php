<?php

namespace App\Http\Modules\Cities\Repositories;

use Gomaa\Base\Base\Repositories\BaseApiRepository;
use App\Http\Modules\Cities\Models\City;

class CityRepository extends BaseApiRepository
{
    /**
     * Examples constructor.
     *
     * @param City $model
     */
    public function __construct(City $model)
    {
        $this->model = $model;
    }

}

<?php

namespace App\Http\Modules\Cars\Repositories;

use Gomaa\Base\Base\Repositories\BaseApiRepository;
use App\Http\Modules\Cars\Models\Car;

class CarRepository extends BaseApiRepository
{
    /**
     * Examples constructor.
     *
     * @param Car $model
     */
    public function __construct(Car $model)
    {
        $this->model = $model;
    }

}

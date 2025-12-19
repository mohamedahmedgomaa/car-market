<?php

namespace App\Http\Modules\Models\Repositories;

use Gomaa\Base\Base\Repositories\BaseApiRepository;
use App\Http\Modules\Models\Models\Model;

class ModelRepository extends BaseApiRepository
{
    /**
     * Examples constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

}

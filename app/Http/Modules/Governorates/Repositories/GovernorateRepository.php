<?php

namespace App\Http\Modules\Governorates\Repositories;

use Gomaa\Base\Base\Repositories\BaseApiRepository;
use App\Http\Modules\Governorates\Models\Governorate;

class GovernorateRepository extends BaseApiRepository
{
    /**
     * GovernorateRepository constructor.
     *
     * @param Governorate $model
     */
    public function __construct(Governorate $model)
    {
        $this->model = $model;
    }
}

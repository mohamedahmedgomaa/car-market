<?php

namespace App\Http\Modules\Brands\Repositories;

use Gomaa\Base\Base\Repositories\BaseApiRepository;
use App\Http\Modules\Brands\Models\Brand;

class BrandRepository extends BaseApiRepository
{
    /**
     * Examples constructor.
     *
     * @param Brand $model
     */
    public function __construct(Brand $model)
    {
        $this->model = $model;
    }

}

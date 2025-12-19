<?php

namespace App\Http\Modules\Countries\Repositories;

use Gomaa\Base\Base\Repositories\BaseApiRepository;
use App\Http\Modules\Countries\Models\Country;

class CountryRepository extends BaseApiRepository
{
    /**
     * Examples constructor.
     *
     * @param Country $model
     */
    public function __construct(Country $model)
    {
        $this->model = $model;
    }

}

<?php

namespace App\Http\Modules\Sellers\Repositories;

use Gomaa\Base\Base\Repositories\BaseApiRepository;
use App\Http\Modules\Sellers\Models\Seller;

class SellerRepository extends BaseApiRepository
{
    /**
     * Examples constructor.
     *
     * @param Seller $model
     */
    public function __construct(Seller $model)
    {
        $this->model = $model;
    }

}

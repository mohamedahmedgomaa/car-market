<?php

namespace App\Http\Modules\FavoriteCars\Repositories;

use App\Http\Modules\FavoriteCars\Models\FavoriteCar;
use Gomaa\Base\Base\Repositories\BaseApiRepository;

class FavoriteCarRepository extends BaseApiRepository
{
    /**
     * Examples constructor.
     *
     * @param FavoriteCar $model
     */
    public function __construct(FavoriteCar $model)
    {
        $this->model = $model;
    }

}

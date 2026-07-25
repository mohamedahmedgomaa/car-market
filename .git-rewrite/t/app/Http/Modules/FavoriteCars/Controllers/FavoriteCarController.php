<?php

namespace App\Http\Modules\FavoriteCars\Controllers;


use App\Http\Modules\FavoriteCars\Services\FavoriteCarService;
use Gomaa\Base\Base\Controllers\BaseApiController;
use App\Http\Modules\FavoriteCars\Requests\CreateFavoriteCarRequest;
use App\Http\Modules\FavoriteCars\Requests\DeleteFavoriteCarRequest;
use App\Http\Modules\FavoriteCars\Requests\ListFavoriteCarRequest;
use App\Http\Modules\FavoriteCars\Requests\ShowFavoriteCarRequest;
use App\Http\Modules\FavoriteCars\Requests\UpdateFavoriteCarRequest;

class FavoriteCarController extends BaseApiController
{

    /**
     * @param FavoriteCarService $service
     */
    public function __construct(FavoriteCarService $service)
    {
        parent::__construct($service,[
            'index' => ListFavoriteCarRequest::class,
            'show' => ShowFavoriteCarRequest::class,
            'store' => CreateFavoriteCarRequest::class,
            'update' => UpdateFavoriteCarRequest::class,
            'destroy' => DeleteFavoriteCarRequest::class,
        ]);
    }

}

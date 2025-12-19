<?php

namespace App\Http\Modules\FavoriteCars\Services;

use Gomaa\Base\Base\Services\BaseApiService;
use App\Http\Modules\FavoriteCars\Repositories\FavoriteCarRepository;
use App\Http\Modules\FavoriteCars\Mappers\FavoriteCarMapper;
use App\Http\Modules\FavoriteCars\Dtos\FavoriteCarDto;

class FavoriteCarService extends BaseApiService
{
    protected string $dtoClass = FavoriteCarDto::class;
    protected string $mapperClass = FavoriteCarMapper::class;

    /**
     * @param FavoriteCarRepository $repository
     */
    public function __construct(FavoriteCarRepository $repository)
    {
        parent::__construct($repository);
    }
}

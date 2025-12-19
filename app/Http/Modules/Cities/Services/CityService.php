<?php

namespace App\Http\Modules\Cities\Services;

use Gomaa\Base\Base\Services\BaseApiService;
use App\Http\Modules\Cities\Repositories\CityRepository;
use App\Http\Modules\Cities\Mappers\CityMapper;
use App\Http\Modules\Cities\Dtos\CityDto;

class CityService extends BaseApiService
{
    protected string $dtoClass = CityDto::class;
    protected string $mapperClass = CityMapper::class;

    /**
     * @param CityRepository $repository
     */
    public function __construct(CityRepository $repository)
    {
        parent::__construct($repository);
    }
}

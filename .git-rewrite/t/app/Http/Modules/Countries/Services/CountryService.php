<?php

namespace App\Http\Modules\Countries\Services;

use Gomaa\Base\Base\Services\BaseApiService;
use App\Http\Modules\Countries\Repositories\CountryRepository;
use App\Http\Modules\Countries\Mappers\CountryMapper;
use App\Http\Modules\Countries\Dtos\CountryDto;

class CountryService extends BaseApiService
{
    protected string $dtoClass = CountryDto::class;
    protected string $mapperClass = CountryMapper::class;

    /**
     * @param CountryRepository $repository
     */
    public function __construct(CountryRepository $repository)
    {
        parent::__construct($repository);
    }
}

<?php

namespace App\Http\Modules\Brands\Services;

use Gomaa\Base\Base\Services\BaseApiService;
use App\Http\Modules\Brands\Repositories\BrandRepository;
use App\Http\Modules\Brands\Mappers\BrandMapper;
use App\Http\Modules\Brands\Dtos\BrandDto;

class BrandService extends BaseApiService
{
    protected string $dtoClass = BrandDto::class;
    protected string $mapperClass = BrandMapper::class;

    /**
     * @param BrandRepository $repository
     */
    public function __construct(BrandRepository $repository)
    {
        parent::__construct($repository);
    }
}

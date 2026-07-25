<?php

namespace App\Http\Modules\Models\Services;

use Gomaa\Base\Base\Services\BaseApiService;
use App\Http\Modules\Models\Repositories\ModelRepository;
use App\Http\Modules\Models\Mappers\ModelMapper;
use App\Http\Modules\Models\Dtos\ModelDto;

class ModelService extends BaseApiService
{
    protected string $dtoClass = ModelDto::class;
    protected string $mapperClass = ModelMapper::class;

    /**
     * @param ModelRepository $repository
     */
    public function __construct(ModelRepository $repository)
    {
        parent::__construct($repository);
    }
}

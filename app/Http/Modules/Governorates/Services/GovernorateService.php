<?php

namespace App\Http\Modules\Governorates\Services;

use Gomaa\Base\Base\Services\BaseApiService;
use App\Http\Modules\Governorates\Repositories\GovernorateRepository;
use App\Http\Modules\Governorates\Mappers\GovernorateMapper;
use App\Http\Modules\Governorates\Dtos\GovernorateDto;

class GovernorateService extends BaseApiService
{
    protected string $dtoClass = GovernorateDto::class;
    protected string $mapperClass = GovernorateMapper::class;

    /**
     * @param GovernorateRepository $repository
     */
    public function __construct(GovernorateRepository $repository)
    {
        parent::__construct($repository);
    }
}

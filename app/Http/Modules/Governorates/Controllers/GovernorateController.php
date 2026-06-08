<?php

namespace App\Http\Modules\Governorates\Controllers;

use Gomaa\Base\Base\Controllers\BaseApiController;
use App\Http\Modules\Governorates\Requests\CreateGovernorateRequest;
use App\Http\Modules\Governorates\Requests\DeleteGovernorateRequest;
use App\Http\Modules\Governorates\Requests\ListGovernorateRequest;
use App\Http\Modules\Governorates\Requests\ShowGovernorateRequest;
use App\Http\Modules\Governorates\Requests\UpdateGovernorateRequest;
use App\Http\Modules\Governorates\Services\GovernorateService;

class GovernorateController extends BaseApiController
{
    /**
     * @param GovernorateService $service
     */
    public function __construct(GovernorateService $service)
    {
        parent::__construct($service, [
            'index' => ListGovernorateRequest::class,
            'show' => ShowGovernorateRequest::class,
            'store' => CreateGovernorateRequest::class,
            'update' => UpdateGovernorateRequest::class,
            'destroy' => DeleteGovernorateRequest::class,
        ]);
    }
}

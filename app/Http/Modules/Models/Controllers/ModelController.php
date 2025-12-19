<?php

namespace App\Http\Modules\Models\Controllers;


use Gomaa\Base\Base\Controllers\BaseApiController;
use App\Http\Modules\Models\Requests\CreateModelRequest;
use App\Http\Modules\Models\Requests\DeleteModelRequest;
use App\Http\Modules\Models\Requests\ListModelRequest;
use App\Http\Modules\Models\Requests\ShowModelRequest;
use App\Http\Modules\Models\Requests\UpdateModelRequest;
use App\Http\Modules\Models\Services\ModelService;

class ModelController extends BaseApiController
{

    /**
     * @param ModelService $service
     */
    public function __construct(ModelService $service)
    {
        parent::__construct($service,[
            'index' => ListModelRequest::class,
            'show' => ShowModelRequest::class,
            'store' => CreateModelRequest::class,
            'update' => UpdateModelRequest::class,
            'destroy' => DeleteModelRequest::class,
        ]);
    }

}

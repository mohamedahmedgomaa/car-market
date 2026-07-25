<?php

namespace App\Http\Modules\CarFeatures\Controllers;


use Gomaa\Base\Base\Controllers\BaseApiController;
use App\Http\Modules\CarFeatures\Requests\CreateCarFeatureRequest;
use App\Http\Modules\CarFeatures\Requests\DeleteCarFeatureRequest;
use App\Http\Modules\CarFeatures\Requests\ListCarFeatureRequest;
use App\Http\Modules\CarFeatures\Requests\ShowCarFeatureRequest;
use App\Http\Modules\CarFeatures\Requests\UpdateCarFeatureRequest;
use App\Http\Modules\CarFeatures\Services\CarFeatureService;

class CarFeatureController extends BaseApiController
{

    /**
     * @param CarFeatureService $service
     */
    public function __construct(CarFeatureService $service)
    {
        parent::__construct($service,[
            'index' => ListCarFeatureRequest::class,
            'show' => ShowCarFeatureRequest::class,
            'store' => CreateCarFeatureRequest::class,
            'update' => UpdateCarFeatureRequest::class,
            'destroy' => DeleteCarFeatureRequest::class,
        ]);
    }

}

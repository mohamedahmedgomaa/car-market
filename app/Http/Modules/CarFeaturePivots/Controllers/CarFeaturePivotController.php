<?php

namespace App\Http\Modules\CarFeaturePivots\Controllers;


use Gomaa\Base\Base\Controllers\BaseApiController;
use App\Http\Modules\CarFeaturePivots\Requests\CreateCarFeaturePivotRequest;
use App\Http\Modules\CarFeaturePivots\Requests\DeleteCarFeaturePivotRequest;
use App\Http\Modules\CarFeaturePivots\Requests\ListCarFeaturePivotRequest;
use App\Http\Modules\CarFeaturePivots\Requests\ShowCarFeaturePivotRequest;
use App\Http\Modules\CarFeaturePivots\Requests\UpdateCarFeaturePivotRequest;
use App\Http\Modules\CarFeaturePivots\Services\CarFeaturePivotService;

class CarFeaturePivotController extends BaseApiController
{

    /**
     * @param CarFeaturePivotService $service
     */
    public function __construct(CarFeaturePivotService $service)
    {
        parent::__construct($service,[
            'index' => ListCarFeaturePivotRequest::class,
            'show' => ShowCarFeaturePivotRequest::class,
            'store' => CreateCarFeaturePivotRequest::class,
            'update' => UpdateCarFeaturePivotRequest::class,
            'destroy' => DeleteCarFeaturePivotRequest::class,
        ]);
    }

}

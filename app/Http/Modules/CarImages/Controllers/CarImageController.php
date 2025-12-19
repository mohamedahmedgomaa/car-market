<?php

namespace App\Http\Modules\CarImages\Controllers;


use Gomaa\Base\Base\Controllers\BaseApiController;
use App\Http\Modules\CarImages\Requests\CreateCarImageRequest;
use App\Http\Modules\CarImages\Requests\DeleteCarImageRequest;
use App\Http\Modules\CarImages\Requests\ListCarImageRequest;
use App\Http\Modules\CarImages\Requests\ShowCarImageRequest;
use App\Http\Modules\CarImages\Requests\UpdateCarImageRequest;
use App\Http\Modules\CarImages\Services\CarImageService;

class CarImageController extends BaseApiController
{

    /**
     * @param CarImageService $service
     */
    public function __construct(CarImageService $service)
    {
        parent::__construct($service,[
            'index' => ListCarImageRequest::class,
            'show' => ShowCarImageRequest::class,
            'store' => CreateCarImageRequest::class,
            'update' => UpdateCarImageRequest::class,
            'destroy' => DeleteCarImageRequest::class,
        ]);
    }

}

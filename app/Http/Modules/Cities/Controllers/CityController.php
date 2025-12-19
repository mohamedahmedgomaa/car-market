<?php

namespace App\Http\Modules\Cities\Controllers;


use Gomaa\Base\Base\Controllers\BaseApiController;
use App\Http\Modules\Cities\Requests\CreateCityRequest;
use App\Http\Modules\Cities\Requests\DeleteCityRequest;
use App\Http\Modules\Cities\Requests\ListCityRequest;
use App\Http\Modules\Cities\Requests\ShowCityRequest;
use App\Http\Modules\Cities\Requests\UpdateCityRequest;
use App\Http\Modules\Cities\Services\CityService;

class CityController extends BaseApiController
{

    /**
     * @param CityService $service
     */
    public function __construct(CityService $service)
    {
        parent::__construct($service,[
            'index' => ListCityRequest::class,
            'show' => ShowCityRequest::class,
            'store' => CreateCityRequest::class,
            'update' => UpdateCityRequest::class,
            'destroy' => DeleteCityRequest::class,
        ]);
    }

}

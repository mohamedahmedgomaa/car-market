<?php

namespace App\Http\Modules\Cars\Controllers;


use App\Http\Modules\Cars\Models\Car;
use App\Http\Modules\Cars\Requests\UpdateCarStatusRequest;
use Gomaa\Base\Base\Controllers\BaseApiController;
use App\Http\Modules\Cars\Requests\CreateCarRequest;
use App\Http\Modules\Cars\Requests\DeleteCarRequest;
use App\Http\Modules\Cars\Requests\ListCarRequest;
use App\Http\Modules\Cars\Requests\ShowCarRequest;
use App\Http\Modules\Cars\Requests\UpdateCarRequest;
use App\Http\Modules\Cars\Services\CarService;
use Illuminate\Http\JsonResponse;

class CarController extends BaseApiController
{

    /**
     * @param CarService $service
     */
    public function __construct(CarService $service)
    {
        parent::__construct($service,[
            'index' => ListCarRequest::class,
            'show' => ShowCarRequest::class,
            'store' => CreateCarRequest::class,
            'update' => UpdateCarRequest::class,
            'destroy' => DeleteCarRequest::class,
        ]);
    }

    public function updateStatus(UpdateCarStatusRequest $request, Car $car): JsonResponse
    {
        return $this->service->updateStatus($request, $car);
    }
}

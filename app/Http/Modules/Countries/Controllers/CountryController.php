<?php

namespace App\Http\Modules\Countries\Controllers;


use Gomaa\Base\Base\Controllers\BaseApiController;
use App\Http\Modules\Countries\Requests\CreateCountryRequest;
use App\Http\Modules\Countries\Requests\DeleteCountryRequest;
use App\Http\Modules\Countries\Requests\ListCountryRequest;
use App\Http\Modules\Countries\Requests\ShowCountryRequest;
use App\Http\Modules\Countries\Requests\UpdateCountryRequest;
use App\Http\Modules\Countries\Services\CountryService;

class CountryController extends BaseApiController
{

    /**
     * @param CountryService $service
     */
    public function __construct(CountryService $service)
    {
        parent::__construct($service,[
            'index' => ListCountryRequest::class,
            'show' => ShowCountryRequest::class,
            'store' => CreateCountryRequest::class,
            'update' => UpdateCountryRequest::class,
            'destroy' => DeleteCountryRequest::class,
        ]);
    }

}

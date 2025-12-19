<?php

namespace App\Http\Modules\Brands\Controllers;


use Gomaa\Base\Base\Controllers\BaseApiController;
use App\Http\Modules\Brands\Requests\CreateBrandRequest;
use App\Http\Modules\Brands\Requests\DeleteBrandRequest;
use App\Http\Modules\Brands\Requests\ListBrandRequest;
use App\Http\Modules\Brands\Requests\ShowBrandRequest;
use App\Http\Modules\Brands\Requests\UpdateBrandRequest;
use App\Http\Modules\Brands\Services\BrandService;

class BrandController extends BaseApiController
{

    /**
     * @param BrandService $service
     */
    public function __construct(BrandService $service)
    {
        parent::__construct($service,[
            'index' => ListBrandRequest::class,
            'show' => ShowBrandRequest::class,
            'store' => CreateBrandRequest::class,
            'update' => UpdateBrandRequest::class,
            'destroy' => DeleteBrandRequest::class,
        ]);
    }

}

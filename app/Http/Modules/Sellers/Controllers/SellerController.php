<?php

namespace App\Http\Modules\Sellers\Controllers;


use App\Http\Modules\Sellers\Requests\CreateSellerRequest;
use App\Http\Modules\Sellers\Requests\DeleteSellerRequest;
use App\Http\Modules\Sellers\Requests\ListSellerRequest;
use App\Http\Modules\Sellers\Requests\LoginSellerRequest;
use App\Http\Modules\Sellers\Requests\RegisterSellerRequest;
use App\Http\Modules\Sellers\Requests\ShowSellerRequest;
use App\Http\Modules\Sellers\Requests\UpdateSellerRequest;
use App\Http\Modules\Sellers\Services\SellerService;
use Gomaa\Base\Base\Controllers\BaseApiController;

class SellerController extends BaseApiController
{

    /**
     * @param SellerService $service
     */
    public function __construct(SellerService $service)
    {
        parent::__construct($service,[
            'index' => ListSellerRequest::class,
            'show' => ShowSellerRequest::class,
            'store' => CreateSellerRequest::class,
            'update' => UpdateSellerRequest::class,
            'destroy' => DeleteSellerRequest::class,
        ]);
    }

    public function register(RegisterSellerRequest $request)
    {
        return $this->service->register($request);
    }

    public function login(LoginSellerRequest $request)
    {
        return $this->service->login($request);
    }

    public function logout(ShowSellerRequest $request)
    {
        return $this->service->logout($request);
    }

}

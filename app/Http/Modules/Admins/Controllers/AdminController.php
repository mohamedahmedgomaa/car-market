<?php

namespace App\Http\Modules\Admins\Controllers;


use App\Http\Modules\Admins\Requests\LoginAdminRequest;
use App\Http\Modules\Admins\Requests\RegisterAdminRequest;
use Gomaa\Base\Base\Controllers\BaseApiController;
use App\Http\Modules\Admins\Requests\CreateAdminRequest;
use App\Http\Modules\Admins\Requests\DeleteAdminRequest;
use App\Http\Modules\Admins\Requests\ListAdminRequest;
use App\Http\Modules\Admins\Requests\ShowAdminRequest;
use App\Http\Modules\Admins\Requests\UpdateAdminRequest;
use App\Http\Modules\Admins\Services\AdminService;

class AdminController extends BaseApiController
{

    /**
     * @param AdminService $service
     */
    public function __construct(AdminService $service)
    {
        parent::__construct($service,[
            'index' => ListAdminRequest::class,
            'show' => ShowAdminRequest::class,
            'store' => CreateAdminRequest::class,
            'update' => UpdateAdminRequest::class,
            'destroy' => DeleteAdminRequest::class,
        ]);
    }

    public function register(RegisterAdminRequest $request)
    {
        return $this->service->register($request);
    }

    public function login(LoginAdminRequest $request)
    {
        return $this->service->login($request);
    }

    public function logout(ShowAdminRequest $request)
    {
        return $this->service->logout($request);
    }

}

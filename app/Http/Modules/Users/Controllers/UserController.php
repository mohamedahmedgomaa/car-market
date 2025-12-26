<?php

namespace App\Http\Modules\Users\Controllers;


use App\Http\Modules\Users\Requests\LoginUserRequest;
use App\Http\Modules\Users\Requests\RegisterUserRequest;
use Gomaa\Base\Base\Controllers\BaseApiController;
use App\Http\Modules\Users\Requests\CreateUserRequest;
use App\Http\Modules\Users\Requests\DeleteUserRequest;
use App\Http\Modules\Users\Requests\ListUserRequest;
use App\Http\Modules\Users\Requests\ShowUserRequest;
use App\Http\Modules\Users\Requests\UpdateUserRequest;
use App\Http\Modules\Users\Services\UserService;

class UserController extends BaseApiController
{

    /**
     * @param UserService $service
     */
    public function __construct(UserService $service)
    {
        parent::__construct($service,[
            'index' => ListUserRequest::class,
            'show' => ShowUserRequest::class,
            'store' => CreateUserRequest::class,
            'update' => UpdateUserRequest::class,
            'destroy' => DeleteUserRequest::class,
        ]);
    }

    public function register(RegisterUserRequest $request)
    {
        return $this->service->register($request);
    }

    public function login(LoginUserRequest $request)
    {
        return $this->service->login($request);
    }

    public function logout(ShowUserRequest $request)
    {
        return $this->service->logout($request);
    }

    public function me(ShowUserRequest $request)
    {
        return $this->service->me($request);
    }

}

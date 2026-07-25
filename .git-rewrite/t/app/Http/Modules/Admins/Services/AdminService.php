<?php

namespace App\Http\Modules\Admins\Services;

use App\Http\Modules\Admins\Models\Admin;
use App\Http\Modules\Admins\Requests\LoginAdminRequest;
use App\Http\Modules\Admins\Requests\RegisterAdminRequest;
use App\Http\Modules\Admins\Requests\ShowAdminRequest;
use Gomaa\Base\Base\Requests\BaseRequest;
use Gomaa\Base\Base\Services\BaseApiService;
use App\Http\Modules\Admins\Repositories\AdminRepository;
use App\Http\Modules\Admins\Mappers\AdminMapper;
use App\Http\Modules\Admins\Dtos\AdminDto;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AdminService extends BaseApiService
{
    protected string $dtoClass = AdminDto::class;
    protected string $mapperClass = AdminMapper::class;

    /**
     * @param AdminRepository $repository
     */
    public function __construct(AdminRepository $repository)
    {
        parent::__construct($repository);
    }

    public function register(RegisterAdminRequest $request)
    {
        $admin = $this->repository->save($request->all());

        $token = $admin->createToken('Api Token')->accessToken;

        return $this->responseWithData([
            'admin'=> $this->toDto($admin),
            'token'=> $token,
        ], 201);
    }

    public function login(LoginAdminRequest $request) {
        $admin = Admin::where('email', $request->email)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            $token = $admin->createToken('Api Token')->accessToken;

            return $this->responseWithData([
                'admin'=> $this->toDto($admin),
                'token'=> $token,
            ], 200);
        }

        return $this->responseWithError('Unauthorized', 401);
    }

    public function logout(ShowAdminRequest $request)
    {
        $request->user()->token()->revoke();
        return $this->responseWithMessage('Logged out successfully', 200);
    }
}

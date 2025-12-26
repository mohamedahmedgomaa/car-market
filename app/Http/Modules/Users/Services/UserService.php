<?php

namespace App\Http\Modules\Users\Services;

use App\Http\Modules\Users\Models\User;
use App\Http\Modules\Users\Requests\LoginUserRequest;
use App\Http\Modules\Users\Requests\RegisterUserRequest;
use App\Http\Modules\Users\Requests\ShowUserRequest;
use Gomaa\Base\Base\Services\BaseApiService;
use App\Http\Modules\Users\Repositories\UserRepository;
use App\Http\Modules\Users\Mappers\UserMapper;
use App\Http\Modules\Users\Dtos\UserDto;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseApiService
{
    protected string $dtoClass = UserDto::class;
    protected string $mapperClass = UserMapper::class;

    /**
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        parent::__construct($repository);
    }

    public function register(RegisterUserRequest $request)
    {
        $user = $this->repository->save($request->all());

        $token = $user->createToken('Api Token')->accessToken;

        return $this->responseWithData([
            'user'=> $this->toDto($user),
            'token'=> $token,
        ], 201);
    }

    public function login(LoginUserRequest $request) {
        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken('Api Token')->accessToken;

            return $this->responseWithData([
                'user'=> $this->toDto($user),
                'token'=> $token,
            ], 200);
        }

        return $this->responseWithError('Unauthorized', 401);
    }


    public function logout(ShowUserRequest $request)
    {
        $request->user()->token()->revoke();
        return $this->responseWithMessage('Logged out successfully', 200);
    }

    public function me(ShowUserRequest $request)
    {
        return $this->responseWithData($this->toDto($request->user()), 200);
    }
}

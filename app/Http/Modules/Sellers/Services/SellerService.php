<?php

namespace App\Http\Modules\Sellers\Services;

use App\Http\Modules\Sellers\Dtos\SellerDto;
use App\Http\Modules\Sellers\Mappers\SellerMapper;
use App\Http\Modules\Sellers\Models\Seller;
use App\Http\Modules\Sellers\Repositories\SellerRepository;
use App\Http\Modules\Sellers\Requests\CreateSellerRequest;
use App\Http\Modules\Sellers\Requests\LoginSellerRequest;
use App\Http\Modules\Sellers\Requests\RegisterSellerRequest;
use App\Http\Modules\Sellers\Requests\ShowSellerRequest;
use App\Http\Modules\Sellers\Requests\UpdateSellerRequest;
use Gomaa\Base\Base\Requests\BaseRequest;
use Gomaa\Base\Base\Services\BaseApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class SellerService extends BaseApiService
{
    protected string $dtoClass = SellerDto::class;
    protected string $mapperClass = SellerMapper::class;

    /**
     * @param SellerRepository $repository
     */
    public function __construct(SellerRepository $repository)
    {
        parent::__construct($repository);
    }

    public function store(BaseRequest|CreateSellerRequest $request): JsonResponse
    {
        $data = $request->all();

        if ($request->hasFile('store_logo')) {
            $uploaded = $request->file('store_logo')->storeOnCloudinary('store_logos');
            $data['store_logo'] = $uploaded->getSecurePath();
            // 'store_logo_public_id' => $uploaded->getPublicId(); // لو عندك عمود
        }

        $seller = Seller::create($data);

        return $this->responseWithData($this->toDto($seller), 201);
    }


    public function register(RegisterSellerRequest $request)
    {
        $data = $request->all();

        if ($request->hasFile('store_logo')) {
            $uploaded = $request->file('store_logo')->storeOnCloudinary('store_logos');
            $data['store_logo'] = $uploaded->getSecurePath();
            // $data['store_logo_public_id'] = $uploaded->getPublicId();
        }

        $data['is_verified'] = false;
        $data['is_active'] = true;

        $seller = $this->repository->save($data);

        $token = $seller->createToken('Api Token')->accessToken;

        return $this->responseWithData([
            'seller'=> $this->toDto($seller),
            'token'=> $token,
        ], 201);
    }

    public function update(BaseRequest|UpdateSellerRequest $request, int $id, bool $restore = false): JsonResponse
    {
        $seller = Seller::find($id);
        if (!$seller) return $this->responseWithError('Seller not found', 404);

        $data = $request->all();

        if ($request->hasFile('store_logo')) {

            // ✅ delete old from cloudinary if you store public id
            if (!empty($seller->store_logo_public_id ?? null)) {
                Cloudinary::destroy($seller->store_logo_public_id);
            }

            $uploaded = $request->file('store_logo')->storeOnCloudinary('store_logos');
            $data['store_logo'] = $uploaded->getSecurePath();
            // $data['store_logo_public_id'] = $uploaded->getPublicId();
        }

        return $seller->update($data)
            ? $this->responseWithData($this->toDto($seller), 200)
            : $this->responseWithError('Failed to update seller', 500);
    }

    public function login(LoginSellerRequest $request) {
        $seller = Seller::where('email', $request->email)->first();

        if ($seller && Hash::check($request->password, $seller->password)) {
            $token = $seller->createToken('Api Token')->accessToken;

            return $this->responseWithData([
                'seller'=> $this->toDto($seller),
                'token'=> $token,
            ], 200);
        }

        return $this->responseWithError('Unauthorized', 401);
    }

    public function logout(ShowSellerRequest $request)
    {
        $request->user()->token()->revoke();
        return $this->responseWithMessage('Logged out successfully', 200);
    }

}

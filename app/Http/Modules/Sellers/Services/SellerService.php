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
            $file = $request->file('store_logo');
            $destinationPath = public_path('uploads/store_logos');
            if (!file_exists($destinationPath)) {
                if (!mkdir($destinationPath, 0777, true) && !is_dir($destinationPath)) {
                    throw new \RuntimeException(sprintf('Directory "%s" was not created', $destinationPath));
                }
            }

            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move($destinationPath, $filename);
            $data['store_logo'] = url('uploads/store_logos/' . $filename);
        }

        $seller = Seller::create($data);

        return $this->responseWithData($this->toDto($seller), 201);
    }

    public function register(RegisterSellerRequest $request)
    {
        $data = $request->all();

        if ($request->hasFile('store_logo')) {
            $path = $request->file('store_logo')->store('store_logos', 'public');
            $data['store_logo'] = asset('storage/' . $path);
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

    public function update(BaseRequest|UpdateSellerRequest $request, int $id, bool $restore = false): JsonResponse
    {
        $data = $request->all();
        $seller = Seller::find($id);
        if (!$seller) {
            return $this->responseWithError('Seller not found', 404);
        }
        if ($request->hasFile('store_logo')) {
            if ($seller->store_logo) {
                $oldPath = str_replace(asset('storage/') . '/', '', $seller->store_logo);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            $file = $request->file('store_logo');
            $destinationPath = public_path('uploads/store_logos');
            if (!file_exists($destinationPath)) {
                if (!mkdir($destinationPath, 0777, true) && !is_dir($destinationPath)) {
                    throw new \RuntimeException(sprintf('Directory "%s" was not created', $destinationPath));
                }
            }

            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move($destinationPath, $filename);
            $data['store_logo'] = url('uploads/store_logos/' . $filename);
        }
        return $seller->update($data)
            ? $this->responseWithData($this->toDto($seller), 200)
            : $this->responseWithError('Failed to update seller', 500);
    }
}

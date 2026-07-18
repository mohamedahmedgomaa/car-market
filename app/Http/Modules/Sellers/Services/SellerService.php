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

class SellerService extends BaseApiService
{
    protected string $dtoClass = SellerDto::class;
    protected string $mapperClass = SellerMapper::class;

    public function __construct(SellerRepository $repository)
    {
        parent::__construct($repository);
    }

    public function store(BaseRequest|CreateSellerRequest $request): JsonResponse
    {
        $data = $request->all();

        if (isset($data['password']) && \Illuminate\Support\Facades\Hash::info($data['password'])['algoName'] === 'unknown') {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($data['password']);
        }

        $data['store_name'] = [
            'en' => $data['store_name_en'] ?? null,
            'ar' => $data['store_name_ar'] ?? null,
        ];

        $data['store_description'] = [
            'en' => $data['store_description_en'] ?? null,
            'ar' => $data['store_description_ar'] ?? null,
        ];

        $data['address'] = [
            'en' => $data['address_en'] ?? null,
            'ar' => $data['address_ar'] ?? null,
        ];

        if ($request->hasFile('store_logo')) {
            $file = $request->file('store_logo');

            $result = cloudinary()->uploadApi()->upload(
                $file->getRealPath(),
                [
                    'folder' => 'store_logos',
                    'resource_type' => 'image',
                ]
            );

            $data['store_logo'] = $result['secure_url'] ?? null;

            // لو عندك عمود:
            // $data['store_logo_public_id'] = $result['public_id'] ?? null;
        }

        if ($request->hasFile('tax_card_image')) {
            $file = $request->file('tax_card_image');

            $result = cloudinary()->uploadApi()->upload(
                $file->getRealPath(),
                [
                    'folder' => 'tax_card_images',
                    'resource_type' => 'image',
                ]
            );

            $data['tax_card_image'] = $result['secure_url'] ?? null;
        }

        $seller = Seller::create($data);

        return $this->responseWithData($this->toDto($seller), 201);
    }

    public function register(RegisterSellerRequest $request)
    {
        $data = $request->all();

        if ($request->hasFile('store_logo')) {
            $file = $request->file('store_logo');

            $result = cloudinary()->uploadApi()->upload(
                $file->getRealPath(),
                [
                    'folder' => 'store_logos',
                    'resource_type' => 'image',
                ]
            );

            $data['store_logo'] = $result['secure_url'] ?? null;

            // لو عندك عمود:
            // $data['store_logo_public_id'] = $result['public_id'] ?? null;
        }

        if ($request->hasFile('tax_card_image')) {
            $file = $request->file('tax_card_image');

            $result = cloudinary()->uploadApi()->upload(
                $file->getRealPath(),
                [
                    'folder' => 'tax_card_images',
                    'resource_type' => 'image',
                ]
            );

            $data['tax_card_image'] = $result['secure_url'] ?? null;
        }

        $data['is_verified'] = false;
        $data['is_active'] = true;

        $seller = $this->repository->save($data);

        $token = $seller->createToken('Api Token')->accessToken;

        return $this->responseWithData([
            'seller' => $this->toDto($seller),
            'token'  => $token,
        ], 201);
    }

    public function update(BaseRequest|UpdateSellerRequest $request, int $id, bool $restore = false): JsonResponse
    {
        $seller = Seller::find($id);
        if (!$seller) return $this->responseWithError('Seller not found', 404);

        $data = $request->all();

        if (isset($data['password']) && !empty($data['password'])) {
            if (\Illuminate\Support\Facades\Hash::info($data['password'])['algoName'] === 'unknown') {
                $data['password'] = \Illuminate\Support\Facades\Hash::make($data['password']);
            }
        } else {
            unset($data['password']);
        }

        if (array_key_exists('store_name_en', $data) || array_key_exists('store_name_ar', $data)) {
            $data['store_name'] = [
                'en' => $data['store_name_en'] ?? $seller->getTranslation('store_name', 'en', false),
                'ar' => $data['store_name_ar'] ?? $seller->getTranslation('store_name', 'ar', false),
            ];
        }

        if (array_key_exists('store_description_en', $data) || array_key_exists('store_description_ar', $data)) {
            $data['store_description'] = [
                'en' => $data['store_description_en'] ?? $seller->getTranslation('store_description', 'en', false),
                'ar' => $data['store_description_ar'] ?? $seller->getTranslation('store_description', 'ar', false),
            ];
        }

        if (array_key_exists('address_en', $data) || array_key_exists('address_ar', $data)) {
            $data['address'] = [
                'en' => $data['address_en'] ?? $seller->getTranslation('address', 'en', false),
                'ar' => $data['address_ar'] ?? $seller->getTranslation('address', 'ar', false),
            ];
        }

        if ($request->hasFile('store_logo')) {

            // ✅ احذف القديم من Cloudinary لو مخزن public_id
            if (!empty($seller->store_logo_public_id ?? null)) {
                try {
                    cloudinary()->uploadApi()->destroy($seller->store_logo_public_id);
                } catch (\Throwable $e) {
                    // تجاهل لو فشل الحذف
                }
            }

            $file = $request->file('store_logo');

            $result = cloudinary()->uploadApi()->upload(
                $file->getRealPath(),
                [
                    'folder' => 'store_logos',
                    'resource_type' => 'image',
                ]
            );

            $data['store_logo'] = $result['secure_url'] ?? null;

            // لو عندك عمود:
            // $data['store_logo_public_id'] = $result['public_id'] ?? null;
        }

        if ($request->hasFile('tax_card_image')) {
            $file = $request->file('tax_card_image');

            $result = cloudinary()->uploadApi()->upload(
                $file->getRealPath(),
                [
                    'folder' => 'tax_card_images',
                    'resource_type' => 'image',
                ]
            );

            $data['tax_card_image'] = $result['secure_url'] ?? null;
        }

        return $seller->update($data)
            ? $this->responseWithData($this->toDto($seller->fresh()), 200)
            : $this->responseWithError('Failed to update seller', 500);
    }

    public function login(LoginSellerRequest $request)
    {
        $seller = Seller::where('email', $request->email)->first();

        if ($seller && Hash::check($request->password, $seller->password)) {
            $token = $seller->createToken('Api Token')->accessToken;

            return $this->responseWithData([
                'seller' => $this->toDto($seller),
                'token'  => $token,
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

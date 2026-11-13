<?php

namespace App\Http\Modules\Sellers\Services;

use App\Http\Modules\Sellers\Dtos\SellerDto;
use App\Http\Modules\Sellers\Mappers\SellerMapper;
use App\Http\Modules\Sellers\Models\AnalyticsEvent;
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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class SellerService extends BaseApiService
{
    protected string $dtoClass = SellerDto::class;
    protected string $mapperClass = SellerMapper::class;

    public function __construct(
        SellerRepository $repository,
        protected AnalyticsTrackerService $analyticsTracker
    ) {
        parent::__construct($repository);
    }

    public function show(BaseRequest $request, int $id): JsonResponse
    {
        $response = parent::show($request, $id);

        if ($response->getStatusCode() === 200) {
            $this->analyticsTracker->track(
                AnalyticsEvent::EVENT_PROFILE_VIEW,
                $id,
                null,
                $request
            );
        }

        return $response;
    }

    public function store(BaseRequest|CreateSellerRequest $request): JsonResponse
    {
        if (!auth('api_admin')->check()) {
            return $this->responseWithError('Forbidden: Only admins can create sellers directly.', 403);
        }

        $data = $this->prepareSellerPayload($request);
        $data = $this->uploadSellerImages($request, $data);

        $seller = Seller::create($data);

        return $this->responseWithData($this->toDto($seller), 201);
    }

    public function register(RegisterSellerRequest $request): JsonResponse
    {
        $data = $this->prepareSellerPayload($request);
        $data = $this->uploadSellerImages($request, $data);

        $data['is_verified'] = false;
        $data['is_active'] = true;
        $data['tier'] = 'none';

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
        if (!$seller) {
            return $this->responseWithError('Seller not found', 404);
        }

        $isAdmin = auth('api_admin')->check();
        $loggedSellerId = auth('api_seller')->id();

        if (!$isAdmin && (int) $loggedSellerId !== (int) $seller->id) {
            return $this->responseWithError('Forbidden: You can only update your own seller profile.', 403);
        }

        $data = $this->prepareSellerPayload($request, $seller);

        // Strip admin-only fields if updated by a seller
        if (!$isAdmin) {
            unset($data['is_verified'], $data['is_active'], $data['tier'], $data['sort_order']);
        }

        $data = $this->uploadSellerImages($request, $data, $seller);

        return $seller->update($data)
            ? $this->responseWithData($this->toDto($seller->fresh()), 200)
            : $this->responseWithError('Failed to update seller', 500);
    }

    public function me(Request $request): JsonResponse
    {
        /** @var Seller|null $seller */
        $seller = auth('api_seller')->user() ?? $request->user();
        if (!$seller) {
            return $this->responseWithError('Unauthenticated', 401);
        }

        $seller->loadMissing(['city', 'governorate'])->loadCount('cars');

        return $this->responseWithData($this->toDto($seller), 200);
    }

    public function updateProfile(UpdateSellerRequest $request): JsonResponse
    {
        /** @var Seller|null $seller */
        $seller = auth('api_seller')->user() ?? $request->user();
        if (!$seller) {
            return $this->responseWithError('Unauthenticated', 401);
        }

        return $this->update($request, (int) $seller->id);
    }

    public function login(LoginSellerRequest $request): JsonResponse
    {
        $seller = Seller::where('email', $request->email)->first();

        if ($seller && Hash::check($request->password, $seller->password)) {
            if (!$seller->is_active) {
                return $this->responseWithError('Your seller account is currently deactivated. Please contact support.', 403);
            }

            $token = $seller->createToken('Api Token')->accessToken;

            return $this->responseWithData([
                'seller' => $this->toDto($seller),
                'token'  => $token,
            ], 200);
        }

        return $this->responseWithError('Unauthorized', 401);
    }

    public function logout(ShowSellerRequest $request): JsonResponse
    {
        $request->user()->token()->revoke();
        return $this->responseWithMessage('Logged out successfully', 200);
    }

    protected function prepareSellerPayload(Request $request, ?Seller $seller = null): array
    {
        $data = $request->all();

        if (isset($data['password']) && !empty($data['password'])) {
            if (Hash::info($data['password'])['algoName'] === 'unknown') {
                $data['password'] = Hash::make($data['password']);
            }
        } else {
            unset($data['password']);
        }

        if ($seller) {
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
        } else {
            if (isset($data['store_name_en']) || isset($data['store_name_ar'])) {
                $data['store_name'] = [
                    'en' => $data['store_name_en'] ?? ($data['store_name_ar'] ?? ''),
                    'ar' => $data['store_name_ar'] ?? ($data['store_name_en'] ?? ''),
                ];
            }
            if (isset($data['store_description_en']) || isset($data['store_description_ar'])) {
                $data['store_description'] = [
                    'en' => $data['store_description_en'] ?? null,
                    'ar' => $data['store_description_ar'] ?? null,
                ];
            }
            if (isset($data['address_en']) || isset($data['address_ar'])) {
                $data['address'] = [
                    'en' => $data['address_en'] ?? null,
                    'ar' => $data['address_ar'] ?? null,
                ];
            }
        }

        $socialFields = ['facebook', 'instagram', 'tiktok', 'youtube', 'website'];
        foreach ($socialFields as $field) {
            $urlKey = $field . '_url';
            if (array_key_exists($urlKey, $data) && empty($data[$field])) {
                $data[$field] = $data[$urlKey];
            }
        }

        return $data;
    }

    protected function uploadSellerImages(Request $request, array $data, ?Seller $seller = null): array
    {
        $imageConfigs = [
            'store_logo' => 'store_logos',
            'tax_card_image' => 'tax_card_images',
            'cover_image' => 'store_covers',
        ];

        foreach ($imageConfigs as $field => $folder) {
            if ($request->hasFile($field)) {
                try {
                    if ($field === 'store_logo' && !empty($seller?->store_logo_public_id)) {
                        try {
                            cloudinary()->uploadApi()->destroy($seller->store_logo_public_id);
                        } catch (\Throwable $e) {
                            // Ignore deletion error
                        }
                    }

                    $file = $request->file($field);
                    $result = cloudinary()->uploadApi()->upload(
                        $file->getRealPath(),
                        [
                            'folder' => $folder,
                            'resource_type' => 'image',
                        ]
                    );
                    $data[$field] = $result['secure_url'] ?? null;
                } catch (\Throwable $e) {
                    Log::warning("Upload failed for {$field}: " . $e->getMessage());
                }
            } else {
                // Prevent overwriting existing image URL with null if no new file was uploaded
                if ($seller && array_key_exists($field, $data) && !is_string($data[$field])) {
                    unset($data[$field]);
                }
            }
        }

        return $data;
    }
}

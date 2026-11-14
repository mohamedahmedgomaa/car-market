<?php

namespace App\Http\Modules\Cars\Services;

use App\Http\Modules\Cars\Dtos\CarDto;
use App\Http\Modules\Cars\Mappers\CarMapper;
use App\Http\Modules\Cars\Models\Car;
use App\Http\Modules\Cars\Models\CarPriceHistory;
use App\Http\Modules\Cars\Repositories\CarRepository;
use App\Http\Modules\Cars\Requests\CreateCarRequest;
use App\Http\Modules\Cars\Requests\ShowCarRequest;
use App\Http\Modules\Cars\Requests\UpdateCarRequest;
use App\Http\Modules\Cars\Requests\UpdateCarStatusRequest;
use App\Http\Modules\Cars\Requests\UpdateSellerCarStatusRequest;
use App\Http\Modules\FavoriteCars\Models\FavoriteCar;
use App\Http\Modules\Notifications\Services\NotificationService;
use App\Http\Modules\Sellers\Models\AnalyticsEvent;
use App\Http\Modules\Sellers\Services\AnalyticsTrackerService;
use Gomaa\Base\Base\Requests\BaseRequest;
use Gomaa\Base\Base\Services\BaseApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CarService extends BaseApiService
{
    protected string $dtoClass = CarDto::class;
    protected string $mapperClass = CarMapper::class;

    public function __construct(
        CarRepository $repository,
        protected AnalyticsTrackerService $analyticsTracker
    ) {
        parent::__construct($repository);
    }

    public function index(BaseRequest $request): JsonResponse
    {
        if ($request->filled('offset') && !$request->filled('page')) {
            $request->merge(['page' => 1]);
        }

        if (auth('api_seller')->check()) {
            $sellerId = auth('api_seller')->id();
            $filter = (array) $request->input('filter', []);
            $filter['seller_id'] = $sellerId;
            $request->merge(['filter' => $filter]);
            $request->query->set('filter', $filter);
        }

        return parent::index($request);
    }

    public function show(BaseRequest $request, int $id): JsonResponse
    {
        return $this->execute(function () use ($request, $id) {
            $relations = [
                'seller.city',
                'seller.governorate',
                'brand',
                'model',
                'city',
                'governorate',
                'country',
                'images',
                'features',
                'favorites',
            ];

            if (Schema::hasTable('car_price_histories')) {
                $relations[] = 'priceHistories';
            }

            $car = $this->repository->getModel()
                ->with($relations)
                ->find($id);

            if ($car) {
                $this->analyticsTracker->track(
                    AnalyticsEvent::EVENT_CAR_VIEW,
                    (int) $car->seller_id,
                    (int) $car->id,
                    $request
                );

                return $this->responseWithData($this->toDto($car));
            }

            return parent::responseErrorThereIsNoData();
        }, $this->checkAuth);
    }

    public function store(BaseRequest|CreateCarRequest $request): JsonResponse
    {
        @ini_set('max_execution_time', 300);
        @set_time_limit(300);
        @ini_set('memory_limit', '512M');

        return DB::transaction(function () use ($request) {
            $car = $this->repository->save($request->all());

            if ($request->filled('features')) {
                $car->features()->sync($request->features);
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $ext = strtolower($image->getClientOriginalExtension());
                    $mime = strtolower((string) $image->getMimeType());
                    $uploadOptions = [
                        'folder' => 'cars',
                        'resource_type' => 'image',
                    ];
                    if (in_array($ext, ['heic', 'heif']) || str_contains($mime, 'heic') || str_contains($mime, 'heif')) {
                        $uploadOptions['format'] = 'jpg';
                    }

                    $result = cloudinary()->uploadApi()->upload(
                        $image->getRealPath(),
                        $uploadOptions
                    );

                    $url = $result['secure_url'] ?? null;

                    $car->images()->create([
                        'path' => $url,
                        'is_main' => (string) $request->main_image === (string) $index,
                    ]);
                }
            }

            $this->handle360Uploads($car, $request);

            return $this->responseWithData($this->toDto($car), 201);
        });
    }

    public function update(BaseRequest|UpdateCarRequest $request, int $id, bool $restore = false): JsonResponse
    {
        @ini_set('max_execution_time', 300);
        @set_time_limit(300);
        @ini_set('memory_limit', '512M');

        $existingCar = Car::find($id);
        if (!$existingCar) {
            return $this->responseWithError('Car not found', 404);
        }

        $isAdmin = auth('api_admin')->check();
        $loggedSellerId = auth('api_seller')->id();

        if (!$isAdmin && (int) $existingCar->seller_id !== (int) $loggedSellerId) {
            return $this->responseWithError('Forbidden: You do not own this car listing.', 403);
        }

        return DB::transaction(function () use ($id, $request, $existingCar, $isAdmin, $loggedSellerId) {
            $oldPrice = (float) $existingCar->price;

            $data = $request->except([
                'features', 'images', 'keep_images',
                'main_image_id', 'main_image_new_index',
                'title_ar', 'title_en', 'description_ar', 'description_en',
            ]);

            // Prevent seller from changing car ownership
            if (!$isAdmin) {
                $data['seller_id'] = $existingCar->seller_id;

                // If a rejected car is edited by the seller, re-submit it as pending
                if ($existingCar->status === 'rejected') {
                    $data['status'] = 'pending';
                    if (Schema::hasColumn('cars', 'rejection_reason')) {
                        $data['rejection_reason'] = null;
                    }
                }
            }

            // Detect price change and track history
            $newPrice = isset($data['price']) ? (float) $data['price'] : $oldPrice;
            if ($oldPrice > 0 && abs($newPrice - $oldPrice) >= 0.01) {
                if (Schema::hasColumn('cars', 'old_price')) {
                    $data['old_price'] = $oldPrice;
                }
            }

            $car = $this->repository->updateById($id, $data);

            if ($oldPrice > 0 && abs($newPrice - $oldPrice) >= 0.01) {
                $this->recordPriceChange(
                    $car,
                    $oldPrice,
                    $newPrice,
                    $isAdmin ? 'admin' : 'seller',
                    $isAdmin ? auth('api_admin')->id() : $loggedSellerId
                );
            }

            $car->features()->sync($request->input('features', []));

            // Handle old images keep/delete
            $keepIds = collect($request->input('keep_images', []))
                ->filter()
                ->map(fn ($v) => (int) $v)
                ->values();

            $keepIds = $car->images()->whereIn('id', $keepIds)->pluck('id');

            $toDelete = $car->images()->whereNotIn('id', $keepIds)->get();
            foreach ($toDelete as $img) {
                if (!empty($img->public_id ?? null)) {
                    try {
                        cloudinary()->uploadApi()->destroy($img->public_id);
                    } catch (\Throwable $e) {
                        // Ignore deletion error
                    }
                }

                $img->delete();
            }

            // Upload new images
            $newImageIds = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $ext = strtolower($image->getClientOriginalExtension());
                    $mime = strtolower((string) $image->getMimeType());
                    $uploadOptions = [
                        'folder' => 'cars',
                        'resource_type' => 'image',
                    ];
                    if (in_array($ext, ['heic', 'heif']) || str_contains($mime, 'heic') || str_contains($mime, 'heif')) {
                        $uploadOptions['format'] = 'jpg';
                    }

                    $result = cloudinary()->uploadApi()->upload(
                        $image->getRealPath(),
                        $uploadOptions
                    );

                    $url = $result['secure_url'] ?? null;

                    $created = $car->images()->create([
                        'path' => $url,
                        'is_main' => false,
                    ]);

                    $newImageIds[$index] = $created->id;
                }
            }

            // Set main image
            $car->images()->update(['is_main' => false]);

            $mainOldId = $request->input('main_image_id');
            $mainNewIndex = $request->input('main_image_new_index');

            if ($mainOldId) {
                $car->images()->where('id', $mainOldId)->update(['is_main' => true]);
            } elseif ($mainNewIndex !== null && array_key_exists((int) $mainNewIndex, $newImageIds)) {
                $car->images()->where('id', $newImageIds[(int) $mainNewIndex])->update(['is_main' => true]);
            } else {
                $first = $car->images()->orderBy('id')->first();
                if ($first) {
                    $first->update(['is_main' => true]);
                }
            }

            $this->handle360Uploads($car, $request);

            $loadRelations = ['seller', 'brand', 'model', 'country', 'city', 'features', 'images'];
            if (Schema::hasTable('car_price_histories')) {
                $loadRelations[] = 'priceHistories';
            }
            $car->load($loadRelations);

            return $this->responseWithData($this->toDto($car), 200);
        });
    }

    public function destroy(BaseRequest $request, int $id, bool $forceDelete = false): JsonResponse
    {
        $car = Car::find($id);
        if (!$car) {
            return $this->responseWithError('Car not found', 404);
        }

        if (!auth('api_admin')->check() && (int) $car->seller_id !== (int) auth('api_seller')->id()) {
            return $this->responseWithError('Forbidden: You do not own this car listing.', 403);
        }

        return parent::destroy($request, $id, $forceDelete);
    }

    public function updateStatus(UpdateCarStatusRequest $request, Car $car): JsonResponse
    {
        $previousStatus = $car->status;
        $data = $request->validated();

        if (isset($data['created_at']) && !empty($data['created_at'])) {
            $car->created_at = \Carbon\Carbon::parse($data['created_at']);
            unset($data['created_at']);
        }

        if (isset($data['status']) && $data['status'] === 'approved' && Schema::hasColumn('cars', 'rejection_reason')) {
            $data['rejection_reason'] = null;
        }

        $car->fill($data);
        $car->save();

        // Notify Seller when status changes to approved or rejected
        if (isset($data['status']) && $data['status'] !== $previousStatus && $car->seller_id) {
            $titleAr = $car->getTranslation('title', 'ar', false) ?: 'سيارتك';
            $titleEn = $car->getTranslation('title', 'en', false) ?: 'your car';

            if ($data['status'] === 'approved') {
                NotificationService::notifySeller(
                    (int) $car->seller_id,
                    [
                        'ar' => 'تمت الموافقة على إعلانك ✅',
                        'en' => 'Your Car Listing is Approved ✅',
                    ],
                    [
                        'ar' => "تمت الموافقة على إعلان ({$titleAr}) وهو الآن معروض للجمهور.",
                        'en' => "Your listing ({$titleEn}) has been approved and is now live.",
                    ],
                    'car_approved',
                    ['car_id' => $car->id, 'status' => 'approved']
                );
            } elseif ($data['status'] === 'rejected') {
                $reason = $data['rejection_reason'] ?? null;
                NotificationService::notifySeller(
                    (int) $car->seller_id,
                    [
                        'ar' => 'تم رفض إعلان السيارة ⚠️',
                        'en' => 'Car Listing Rejected ⚠️',
                    ],
                    [
                        'ar' => "تم رفض إعلان ({$titleAr})." . ($reason ? " السبب: {$reason}" : ' يرجى مراجعة البيانات والمحاولة مرة أخرى.'),
                        'en' => "Your listing ({$titleEn}) was rejected." . ($reason ? " Reason: {$reason}" : ' Please review and update the listing.'),
                    ],
                    'car_rejected',
                    ['car_id' => $car->id, 'status' => 'rejected', 'rejection_reason' => $reason]
                );
            }
        }

        return $this->responseWithData($this->toDto($car->fresh()), 200);
    }

    public function updateSellerStatus(UpdateSellerCarStatusRequest $request, Car $car): JsonResponse
    {
        if (!auth('api_admin')->check() && (int) $car->seller_id !== (int) auth('api_seller')->id()) {
            return $this->responseWithError('Forbidden: You do not own this car listing.', 403);
        }

        $targetStatus = $request->input('status');

        if ($targetStatus === 'sold') {
            $car->status = 'sold';
            if (Schema::hasColumn('cars', 'sold_at')) {
                $car->sold_at = now();
            }
            if (Schema::hasColumn('cars', 'sold_price')) {
                $car->sold_price = $request->filled('sold_price')
                    ? (float) $request->input('sold_price')
                    : (float) $car->price;
            }
        } elseif ($targetStatus === 'archived') {
            $car->status = 'archived';
        } elseif ($targetStatus === 'available') {
            $car->status = 'pending';
            if (Schema::hasColumn('cars', 'sold_at')) {
                $car->sold_at = null;
            }
            if (Schema::hasColumn('cars', 'sold_price')) {
                $car->sold_price = null;
            }
        }

        $car->save();

        return $this->responseWithData($this->toDto($car->fresh()), 200);
    }

    public function toggleFavoriteCar(ShowCarRequest $request, Car $car)
    {
        $userId = $request->user()->id;

        $fav = FavoriteCar::where('user_id', $userId)
            ->where('car_id', $car->id)
            ->first();

        if ($fav) {
            $fav->delete();
            $isFavorite = false;
        } else {
            FavoriteCar::create([
                'user_id' => $userId,
                'car_id'  => $car->id,
            ]);
            $isFavorite = true;

            if ($car->seller_id) {
                $titleAr = $car->getTranslation('title', 'ar', false) ?: 'سيارتك';
                $titleEn = $car->getTranslation('title', 'en', false) ?: 'your car';

                NotificationService::notifySeller(
                    (int) $car->seller_id,
                    [
                        'ar' => 'إعجاب جديد بسيارتك ❤️',
                        'en' => 'New Favorite on Your Car ❤️',
                    ],
                    [
                        'ar' => "قام أحد المستخدمين بإضافة ({$titleAr}) إلى قائمة المفضلة.",
                        'en' => "A user added ({$titleEn}) to their favorites.",
                    ],
                    'car_favorited',
                    ['car_id' => $car->id]
                );
            }
        }

        $favoritesCount = FavoriteCar::where('car_id', $car->id)->count();

        return response()->json([
            'status' => 200,
            'data' => [
                'car_id' => $car->id,
                'is_favorited' => $isFavorite,
                'favorites_count' => $favoritesCount,
            ],
        ]);
    }

    protected function recordPriceChange(
        Car $car,
        float $oldPrice,
        float $newPrice,
        string $changedByType,
        ?int $changedById
    ): void {
        try {
            $diff = $newPrice - $oldPrice;
            $pct = $oldPrice > 0 ? round(($diff / $oldPrice) * 100, 2) : 0;

            if (Schema::hasTable('car_price_histories')) {
                CarPriceHistory::create([
                    'car_id' => $car->id,
                    'seller_id' => $car->seller_id,
                    'old_price' => $oldPrice,
                    'new_price' => $newPrice,
                    'price_difference' => $diff,
                    'change_percentage' => $pct,
                    'currency' => $car->currency ?? 'EGP',
                    'changed_by_type' => $changedByType,
                    'changed_by_id' => $changedById,
                ]);
            }

            // If price dropped, notify users who favorited this car!
            if ($newPrice < $oldPrice) {
                $titleAr = $car->getTranslation('title', 'ar', false) ?: 'سيارة في مفضلتك';
                $titleEn = $car->getTranslation('title', 'en', false) ?: 'a car in your favorites';
                $currency = $car->currency ?? 'EGP';
                $formattedNew = number_format($newPrice);
                $formattedOld = number_format($oldPrice);

                $userIds = FavoriteCar::where('car_id', $car->id)->pluck('user_id');
                foreach ($userIds as $uid) {
                    NotificationService::notifyUser(
                        (int) $uid,
                        [
                            'ar' => 'انخفض سعر سيارة في المفضلة! 🔥',
                            'en' => 'Price Drop on Your Favorite Car! 🔥',
                        ],
                        [
                            'ar' => "انخفض سعر ({$titleAr}) من {$formattedOld} {$currency} إلى {$formattedNew} {$currency}.",
                            'en' => "The price of ({$titleEn}) dropped from {$formattedOld} {$currency} to {$formattedNew} {$currency}.",
                        ],
                        'price_drop',
                        [
                            'car_id' => $car->id,
                            'old_price' => $oldPrice,
                            'new_price' => $newPrice,
                            'currency' => $currency,
                        ]
                    );
                }
            }
        } catch (\Throwable $e) {
            // Do not break car update if notification or history logging fails
        }
    }

    protected function handle360Uploads(Car $car, $request): void
    {
        $hasChanges = false;

        // 1. Interior 360 Panorama (Single Image)
        if ($request->hasFile('interior_360_image')) {
            $file = $request->file('interior_360_image');
            $url = $this->uploadSingleImage($file, 'cars/360_interiors');
            if ($url) {
                $car->interior_360_url = $url;
                $hasChanges = true;
            }
        } elseif ($request->filled('interior_360_url')) {
            $car->interior_360_url = $request->input('interior_360_url');
            $hasChanges = true;
        } elseif ($request->has('remove_interior_360') && $request->boolean('remove_interior_360')) {
            $car->interior_360_url = null;
            $hasChanges = true;
        }

        // 2. Exterior 360 Spin (Multiple image frames)
        if ($request->hasFile('exterior_360_files')) {
            $files = $request->file('exterior_360_files');
            usort($files, function ($a, $b) {
                return strnatcasecmp($a->getClientOriginalName(), $b->getClientOriginalName());
            });

            $urls = [];
            foreach ($files as $file) {
                $url = $this->uploadSingleImage($file, 'cars/360_exteriors/' . $car->id);
                if ($url) {
                    $urls[] = $url;
                }
            }
            if (!empty($urls)) {
                $car->exterior_360_images = $urls;
                $hasChanges = true;
            }
        } elseif ($request->hasFile('exterior_360_zip')) {
            $zipFile = $request->file('exterior_360_zip');
            if (class_exists(\ZipArchive::class)) {
                $zip = new \ZipArchive();
                if ($zip->open($zipFile->getRealPath()) === true) {
                    $extractPath = storage_path('app/temp_360_' . $car->id . '_' . uniqid());
                    $zip->extractTo($extractPath);
                    $zip->close();

                    $allFiles = \Illuminate\Support\Facades\File::allFiles($extractPath);
                    $validImages = array_filter($allFiles, function ($f) {
                        return in_array(strtolower($f->getExtension()), ['jpg', 'jpeg', 'png', 'webp']);
                    });

                    usort($validImages, function ($a, $b) {
                        return strnatcasecmp($a->getFilename(), $b->getFilename());
                    });

                    $urls = [];
                    foreach ($validImages as $imgFile) {
                        $url = $this->uploadSingleFilePath($imgFile->getRealPath(), 'cars/360_exteriors/' . $car->id);
                        if ($url) {
                            $urls[] = $url;
                        }
                    }

                    \Illuminate\Support\Facades\File::deleteDirectory($extractPath);

                    if (!empty($urls)) {
                        $car->exterior_360_images = $urls;
                        $hasChanges = true;
                    }
                }
            }
        } elseif ($request->filled('exterior_360_images')) {
            $val = $request->input('exterior_360_images');
            $car->exterior_360_images = is_array($val) ? $val : json_decode($val, true);
            $hasChanges = true;
        } elseif ($request->has('remove_exterior_360') && $request->boolean('remove_exterior_360')) {
            $car->exterior_360_images = null;
            $hasChanges = true;
        }

        // 3. Custom 3D Model GLB (Optional)
        if ($request->hasFile('model_3d_file')) {
            $file = $request->file('model_3d_file');
            $filename = 'car_' . $car->id . '_' . time() . '.glb';
            $path = $file->storeAs('uploads/models_3d', $filename, 'public');
            $car->model_3d_url = '/storage/' . $path;
            $hasChanges = true;
        } elseif ($request->filled('model_3d_url')) {
            $car->model_3d_url = $request->input('model_3d_url');
            $hasChanges = true;
        } elseif ($request->has('remove_model_3d') && $request->boolean('remove_model_3d')) {
            $car->model_3d_url = null;
            $hasChanges = true;
        }

        if ($hasChanges) {
            $car->save();
        }
    }

    protected function uploadSingleImage($file, string $folder): ?string
    {
        try {
            $result = cloudinary()->uploadApi()->upload(
                $file->getRealPath(),
                [
                    'folder' => $folder,
                    'resource_type' => 'image',
                ]
            );
            return $result['secure_url'] ?? null;
        } catch (\Throwable $e) {
            $ext = $file->getClientOriginalExtension() ?: 'jpg';
            $filename = uniqid('360_') . '.' . $ext;
            $path = $file->storeAs('uploads/' . $folder, $filename, 'public');
            return '/storage/' . $path;
        }
    }

    protected function uploadSingleFilePath(string $realPath, string $folder): ?string
    {
        try {
            $result = cloudinary()->uploadApi()->upload(
                $realPath,
                [
                    'folder' => $folder,
                    'resource_type' => 'image',
                ]
            );
            return $result['secure_url'] ?? null;
        } catch (\Throwable $e) {
            $ext = pathinfo($realPath, PATHINFO_EXTENSION) ?: 'jpg';
            $filename = uniqid('360_') . '.' . $ext;
            $destRel = 'uploads/' . $folder . '/' . $filename;
            \Illuminate\Support\Facades\Storage::disk('public')->put($destRel, file_get_contents($realPath));
            return '/storage/' . $destRel;
        }
    }
}

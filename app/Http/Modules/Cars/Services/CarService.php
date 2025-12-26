<?php

namespace App\Http\Modules\Cars\Services;

use App\Http\Modules\Cars\Models\Car;
use App\Http\Modules\Cars\Requests\CreateCarRequest;
use App\Http\Modules\Cars\Requests\ShowCarRequest;
use App\Http\Modules\Cars\Requests\UpdateCarRequest;
use App\Http\Modules\Cars\Requests\UpdateCarStatusRequest;
use App\Http\Modules\FavoriteCars\Models\FavoriteCar;
use Gomaa\Base\Base\Requests\BaseRequest;
use Gomaa\Base\Base\Services\BaseApiService;
use App\Http\Modules\Cars\Repositories\CarRepository;
use App\Http\Modules\Cars\Mappers\CarMapper;
use App\Http\Modules\Cars\Dtos\CarDto;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CarService extends BaseApiService
{
    protected string $dtoClass = CarDto::class;
    protected string $mapperClass = CarMapper::class;

    /**
     * @param CarRepository $repository
     */
    public function __construct(CarRepository $repository)
    {
        parent::__construct($repository);
    }

    public function store(BaseRequest|CreateCarRequest $request): JsonResponse
    {
        return DB::transaction(function () use ($request) {

            // 1️⃣ Create Car
            $car = $this->repository->save($request->all());

            // 2️⃣ Attach Features
            if ($request->filled('features')) {
                $car->features()->sync($request->features);
            }

            // 3️⃣ Upload Images
            if ($request->hasFile('images')) {

                foreach ($request->file('images') as $index => $image) {

                    $path = $image->store('cars', 'public');

                    $car->images()->create([
                        'path' => $path,
                        'is_main' => $request->main_image == $index,
                    ]);
                }
            }

            return $this->responseWithData($this->toDto($car), 201);
        });
    }

    public function update(BaseRequest|UpdateCarRequest $request, int $id, bool $restore = false): JsonResponse
    {
        return DB::transaction(function () use ($id, $request) {

            // 1) get car
            $car = $this->repository->getById($id);

            // 2) update car fields (exclude files/arrays we handle manually)
            $data = $request->except([
                'features',
                'images',
                'keep_images',
                'main_image_id',
                'main_image_new_index',
                'title_ar', 'title_en', 'description_ar', 'description_en',
            ]);

            $car = $this->repository->updateById($id, $data);

            // 3) sync features
            $car->features()->sync($request->input('features', []));

            // 4) handle old images keep/delete
            $keepIds = collect($request->input('keep_images', []))
                ->filter()
                ->map(fn ($v) => (int) $v)
                ->values();

            // ensure kept images belong to this car
            $keepIds = $car->images()->whereIn('id', $keepIds)->pluck('id');

            $toDelete = $car->images()->whereNotIn('id', $keepIds)->get();
            foreach ($toDelete as $img) {
                // delete file from storage
                Storage::disk('public')->delete($img->path);
                $img->delete();
            }

            // 5) upload new images
            $newImageIds = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('cars', 'public');

                    $created = $car->images()->create([
                        'path' => $path,
                        'is_main' => false, // هنظبطها بعدين
                    ]);

                    $newImageIds[$index] = $created->id; // map index -> id
                }
            }

            // 6) set main image
            // reset all to false first
            $car->images()->update(['is_main' => false]);

            $mainOldId = $request->input('main_image_id');
            $mainNewIndex = $request->input('main_image_new_index');

            if ($mainOldId) {
                // only if belongs to car
                $car->images()->where('id', $mainOldId)->update(['is_main' => true]);
            } elseif ($mainNewIndex !== null && array_key_exists((int)$mainNewIndex, $newImageIds)) {
                $car->images()->where('id', $newImageIds[(int)$mainNewIndex])->update(['is_main' => true]);
            } else {
                // fallback: first image as main if exists
                $first = $car->images()->orderByDesc('is_main')->orderBy('id')->first();
                if ($first) {
                    $first->update(['is_main' => true]);
                }
            }

            // refresh relations
            $car->load(['seller', 'brand', 'model', 'country', 'city', 'features', 'images']);

            return $this->responseWithData($this->toDto($car), 200);
        });
    }

    public function updateStatus(UpdateCarStatusRequest $request, Car $car): JsonResponse
    {
        $car->update([
            'status' => $request->status,
        ]);

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
}

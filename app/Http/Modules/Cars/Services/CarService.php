<?php

namespace App\Http\Modules\Cars\Services;

use App\Http\Modules\Cars\Models\Car;
use App\Http\Modules\Cars\Requests\CreateCarRequest;
use App\Http\Modules\Cars\Requests\ShowCarRequest;
use App\Http\Modules\Cars\Requests\UpdateCarRequest;
use App\Http\Modules\Cars\Requests\UpdateCarStatusRequest;
use App\Http\Modules\FavoriteCars\Models\FavoriteCar;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
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

            $car = $this->repository->save($request->all());

            if ($request->filled('features')) {
                $car->features()->sync($request->features);
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {

                    // ✅ Upload to Cloudinary
                    $uploaded = $image->storeOnCloudinary('cars'); // folder = cars
                    $url = $uploaded->getSecurePath(); // https url

                    $car->images()->create([
                        'path' => $url,
                        'is_main' => $request->main_image == $index,
                        // لو عندك عمود public_id ضيفه:
                        // 'public_id' => $uploaded->getPublicId(),
                    ]);
                }
            }

            return $this->responseWithData($this->toDto($car), 201);
        });
    }

    public function update(BaseRequest|UpdateCarRequest $request, int $id, bool $restore = false): JsonResponse
    {
        return DB::transaction(function () use ($id, $request) {

            $car = $this->repository->getById($id);

            $data = $request->except([
                'features','images','keep_images','main_image_id','main_image_new_index',
                'title_ar','title_en','description_ar','description_en',
            ]);

            $car = $this->repository->updateById($id, $data);

            $car->features()->sync($request->input('features', []));

            $keepIds = collect($request->input('keep_images', []))
                ->filter()
                ->map(fn ($v) => (int) $v)
                ->values();

            $keepIds = $car->images()->whereIn('id', $keepIds)->pluck('id');

            $toDelete = $car->images()->whereNotIn('id', $keepIds)->get();
            foreach ($toDelete as $img) {
                // ✅ delete from cloudinary if public_id exists
                if (!empty($img->public_id ?? null)) {
                    Cloudinary::destroy($img->public_id);
                }
                $img->delete();
            }

            $newImageIds = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {

                    $uploaded = $image->storeOnCloudinary('cars');
                    $url = $uploaded->getSecurePath();

                    $created = $car->images()->create([
                        'path' => $url,
                        'is_main' => false,
                        // 'public_id' => $uploaded->getPublicId(),
                    ]);

                    $newImageIds[$index] = $created->id;
                }
            }

            $car->images()->update(['is_main' => false]);

            $mainOldId = $request->input('main_image_id');
            $mainNewIndex = $request->input('main_image_new_index');

            if ($mainOldId) {
                $car->images()->where('id', $mainOldId)->update(['is_main' => true]);
            } elseif ($mainNewIndex !== null && array_key_exists((int)$mainNewIndex, $newImageIds)) {
                $car->images()->where('id', $newImageIds[(int)$mainNewIndex])->update(['is_main' => true]);
            } else {
                $first = $car->images()->orderBy('id')->first();
                if ($first) $first->update(['is_main' => true]);
            }

            $car->load(['seller','brand','model','country','city','features','images']);

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

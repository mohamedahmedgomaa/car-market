<?php

namespace App\Http\Modules\CarImages\Mappers;

use App\Http\Modules\CarImages\Models\CarImage;
use App\Http\Modules\CarImages\Dtos\CarImageDto;

class CarImageMapper
{
    /**
     * Convert Model → DTO
     */
    public function modelToDto(CarImage $model, ?CarImageDto $dto = null): CarImageDto
    {
        $dto = $dto ?? new CarImageDto();

                $dto->setId($model->id);
        $dto->setCarId($model->car_id);
        $dto->setPath($model->path);
        $dto->setIsMain($model->is_main);
        $dto->setCreatedAt($model->created_at);
        $dto->setUpdatedAt($model->updated_at);

        return $dto;
    }

    /**
     * Convert DTO → Model
     */
    public function dtoToModel(CarImageDto $dto, ?CarImage $model = null): CarImage
    {
        $model = $model ?? new CarImage();

                $model->id = $dto->getId();
        $model->car_id = $dto->getCarId();
        $model->path = $dto->getPath();
        $model->is_main = $dto->getIsMain();
        $model->created_at = $dto->getCreatedAt();
        $model->updated_at = $dto->getUpdatedAt();

        return $model;
    }

    /**
     * Convert Array → DTO
     */
    public function arrayToDto(array $data): CarImageDto
    {
        $dto = new CarImageDto();

        foreach ($data as $key => $value) {
            $method = 'set' . \Illuminate\Support\Str::studly($key);
            if (method_exists($dto, $method)) {
                $dto->$method($value);
            }
        }

        return $dto;
    }

    /**
     * Convert DTO → Array
     */
    public function dtoToArray(CarImageDto $dto): array
    {
        return [
                        'id' => $dto->getId(),
            'car_id' => $dto->getCarId(),
            'path' => $dto->getPath(),
            'is_main' => $dto->getIsMain(),
            'created_at' => $dto->getCreatedAt(),
            'updated_at' => $dto->getUpdatedAt(),

        ];
    }
}

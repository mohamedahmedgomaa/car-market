<?php

namespace App\Http\Modules\CarFeaturePivots\Mappers;

use App\Http\Modules\CarFeaturePivots\Models\CarFeaturePivot;
use App\Http\Modules\CarFeaturePivots\Dtos\CarFeaturePivotDto;

class CarFeaturePivotMapper
{
    /**
     * Convert Model → DTO
     */
    public function modelToDto(CarFeaturePivot $model, ?CarFeaturePivotDto $dto = null): CarFeaturePivotDto
    {
        $dto = $dto ?? new CarFeaturePivotDto();

                $dto->setId($model->id);
        $dto->setCarId($model->car_id);
        $dto->setCarFeatureId($model->car_feature_id);
        $dto->setCreatedAt($model->created_at);
        $dto->setUpdatedAt($model->updated_at);

        return $dto;
    }

    /**
     * Convert DTO → Model
     */
    public function dtoToModel(CarFeaturePivotDto $dto, ?CarFeaturePivot $model = null): CarFeaturePivot
    {
        $model = $model ?? new CarFeaturePivot();

                $model->id = $dto->getId();
        $model->car_id = $dto->getCarId();
        $model->car_feature_id = $dto->getCarFeatureId();
        $model->created_at = $dto->getCreatedAt();
        $model->updated_at = $dto->getUpdatedAt();

        return $model;
    }

    /**
     * Convert Array → DTO
     */
    public function arrayToDto(array $data): CarFeaturePivotDto
    {
        $dto = new CarFeaturePivotDto();

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
    public function dtoToArray(CarFeaturePivotDto $dto): array
    {
        return [
                        'id' => $dto->getId(),
            'car_id' => $dto->getCarId(),
            'car_feature_id' => $dto->getCarFeatureId(),
            'created_at' => $dto->getCreatedAt(),
            'updated_at' => $dto->getUpdatedAt(),

        ];
    }
}

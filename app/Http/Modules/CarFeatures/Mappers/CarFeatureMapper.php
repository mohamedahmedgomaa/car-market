<?php

namespace App\Http\Modules\CarFeatures\Mappers;

use App\Http\Modules\CarFeatures\Models\CarFeature;
use App\Http\Modules\CarFeatures\Dtos\CarFeatureDto;

class CarFeatureMapper
{
    /**
     * Convert Model → DTO
     */
    public function modelToDto(CarFeature $model, ?CarFeatureDto $dto = null): CarFeatureDto
    {
        $dto = $dto ?? new CarFeatureDto();

        $dto->setId($model->id);
        $dto->setName($model->getTranslations('name'));
        $dto->setCreatedAt($model->created_at);
        $dto->setUpdatedAt($model->updated_at);

        return $dto;
    }

    /**
     * Convert DTO → Model
     */
    public function dtoToModel(CarFeatureDto $dto, ?CarFeature $model = null): CarFeature
    {
        $model = $model ?? new CarFeature();

        $model->id = $dto->getId();
        $model->name = $dto->getName();
        $model->created_at = $dto->getCreatedAt();
        $model->updated_at = $dto->getUpdatedAt();

        return $model;
    }

    /**
     * Convert Array → DTO
     */
    public function arrayToDto(array $data): CarFeatureDto
    {
        $dto = new CarFeatureDto();

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
    public function dtoToArray(CarFeatureDto $dto): array
    {
        return [
            'id' => $dto->getId(),
            'name' => $dto->getName(),
            'created_at' => $dto->getCreatedAt(),
            'updated_at' => $dto->getUpdatedAt(),

        ];
    }
}

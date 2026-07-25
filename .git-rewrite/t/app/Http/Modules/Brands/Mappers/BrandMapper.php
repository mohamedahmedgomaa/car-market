<?php

namespace App\Http\Modules\Brands\Mappers;

use App\Http\Modules\Brands\Models\Brand;
use App\Http\Modules\Brands\Dtos\BrandDto;

class BrandMapper
{
    /**
     * Convert Model → DTO
     */
    public function modelToDto(Brand $model, ?BrandDto $dto = null): BrandDto
    {
        $dto = $dto ?? new BrandDto();

        $dto->setId($model->id);
        $dto->setName($model->getTranslations('name'));
        $dto->setCreatedAt($model->created_at);
        $dto->setUpdatedAt($model->updated_at);

        return $dto;
    }

    /**
     * Convert DTO → Model
     */
    public function dtoToModel(BrandDto $dto, ?Brand $model = null): Brand
    {
        $model = $model ?? new Brand();

        $model->id = $dto->getId();
        $model->name = $dto->getName();
        $model->created_at = $dto->getCreatedAt();
        $model->updated_at = $dto->getUpdatedAt();

        return $model;
    }

    /**
     * Convert Array → DTO
     */
    public function arrayToDto(array $data): BrandDto
    {
        $dto = new BrandDto();

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
    public function dtoToArray(BrandDto $dto): array
    {
        return [
            'id' => $dto->getId(),
            'name' => $dto->getName(),
            'created_at' => $dto->getCreatedAt(),
            'updated_at' => $dto->getUpdatedAt(),

        ];
    }
}

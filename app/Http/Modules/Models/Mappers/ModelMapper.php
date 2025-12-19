<?php

namespace App\Http\Modules\Models\Mappers;

use App\Http\Modules\Models\Models\Model;
use App\Http\Modules\Models\Dtos\ModelDto;

class ModelMapper
{
    /**
     * Convert Model → DTO
     */
    public function modelToDto(Model $model, ?ModelDto $dto = null): ModelDto
    {
        $dto = $dto ?? new ModelDto();

        $dto->setId($model->id);
        $dto->setBrandId($model->brand_id);
        $dto->setBrand($model->brand);
        $dto->setName($model->getTranslations('name'));
        $dto->setCreatedAt($model->created_at);
        $dto->setUpdatedAt($model->updated_at);

        return $dto;
    }

    /**
     * Convert DTO → Model
     */
    public function dtoToModel(ModelDto $dto, ?Model $model = null): Model
    {
        $model = $model ?? new Model();

        $model->id = $dto->getId();
        $model->brand_id = $dto->getBrandId();
        $model->name = $dto->getName();
        $model->created_at = $dto->getCreatedAt();
        $model->updated_at = $dto->getUpdatedAt();

        return $model;
    }

    /**
     * Convert Array → DTO
     */
    public function arrayToDto(array $data): ModelDto
    {
        $dto = new ModelDto();

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
    public function dtoToArray(ModelDto $dto): array
    {
        return [
            'id' => $dto->getId(),
            'brand_id' => $dto->getBrandId(),
            'name' => $dto->getName(),
            'created_at' => $dto->getCreatedAt(),
            'updated_at' => $dto->getUpdatedAt(),

        ];
    }
}

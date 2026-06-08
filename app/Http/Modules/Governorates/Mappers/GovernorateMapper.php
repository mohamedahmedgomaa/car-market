<?php

namespace App\Http\Modules\Governorates\Mappers;

use App\Http\Modules\Governorates\Models\Governorate;
use App\Http\Modules\Governorates\Dtos\GovernorateDto;

class GovernorateMapper
{
    /**
     * Convert Model → DTO
     */
    public function modelToDto(Governorate $model, ?GovernorateDto $dto = null): GovernorateDto
    {
        $dto = $dto ?? new GovernorateDto();

        $dto->setId($model->id);
        $dto->setCountryId($model->country_id);
        $dto->setCountry($model->country);
        $dto->setName($model->getTranslations('name'));
        $dto->setCreatedAt($model->created_at);
        $dto->setUpdatedAt($model->updated_at);

        return $dto;
    }

    /**
     * Convert DTO → Model
     */
    public function dtoToModel(GovernorateDto $dto, ?Governorate $model = null): Governorate
    {
        $model = $model ?? new Governorate();

        $model->id = $dto->getId();
        $model->country_id = $dto->getCountryId();
        $model->name = $dto->getName();
        $model->created_at = $dto->getCreatedAt();
        $model->updated_at = $dto->getUpdatedAt();

        return $model;
    }

    /**
     * Convert Array → DTO
     */
    public function arrayToDto(array $data): GovernorateDto
    {
        $dto = new GovernorateDto();

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
    public function dtoToArray(GovernorateDto $dto): array
    {
        return [
            'id' => $dto->getId(),
            'country_id' => $dto->getCountryId(),
            'name' => $dto->getName(),
            'created_at' => $dto->getCreatedAt(),
            'updated_at' => $dto->getUpdatedAt(),
        ];
    }
}

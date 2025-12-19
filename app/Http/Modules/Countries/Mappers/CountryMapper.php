<?php

namespace App\Http\Modules\Countries\Mappers;

use App\Http\Modules\Countries\Models\Country;
use App\Http\Modules\Countries\Dtos\CountryDto;

class CountryMapper
{
    /**
     * Convert Model → DTO
     */
    public function modelToDto(Country $model, ?CountryDto $dto = null): CountryDto
    {
        $dto = $dto ?? new CountryDto();

        $dto->setId($model->id);
        $dto->setName($model->getTranslations('name'));
        $dto->setCreatedAt($model->created_at);
        $dto->setUpdatedAt($model->updated_at);

        return $dto;
    }

    /**
     * Convert DTO → Model
     */
    public function dtoToModel(CountryDto $dto, ?Country $model = null): Country
    {
        $model = $model ?? new Country();

        $model->id = $dto->getId();
        $model->name = $dto->getName();
        $model->created_at = $dto->getCreatedAt();
        $model->updated_at = $dto->getUpdatedAt();

        return $model;
    }

    /**
     * Convert Array → DTO
     */
    public function arrayToDto(array $data): CountryDto
    {
        $dto = new CountryDto();

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
    public function dtoToArray(CountryDto $dto): array
    {
        return [
            'id' => $dto->getId(),
            'name' => $dto->getName(),
            'created_at' => $dto->getCreatedAt(),
            'updated_at' => $dto->getUpdatedAt(),

        ];
    }
}

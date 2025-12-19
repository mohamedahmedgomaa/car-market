<?php

namespace App\Http\Modules\Cities\Mappers;

use App\Http\Modules\Cities\Models\City;
use App\Http\Modules\Cities\Dtos\CityDto;

class CityMapper
{
    /**
     * Convert Model → DTO
     */
    public function modelToDto(City $model, ?CityDto $dto = null): CityDto
    {
        $dto = $dto ?? new CityDto();

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
    public function dtoToModel(CityDto $dto, ?City $model = null): City
    {
        $model = $model ?? new City();

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
    public function arrayToDto(array $data): CityDto
    {
        $dto = new CityDto();

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
    public function dtoToArray(CityDto $dto): array
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

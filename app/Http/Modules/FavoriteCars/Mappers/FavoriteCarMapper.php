<?php

namespace App\Http\Modules\FavoriteCars\Mappers;

use App\Http\Modules\FavoriteCars\Models\FavoriteCar;
use App\Http\Modules\FavoriteCars\Dtos\FavoriteCarDto;

class FavoriteCarMapper
{
    /**
     * Convert Model → DTO
     */
    public function modelToDto(FavoriteCar $model, ?FavoriteCarDto $dto = null): FavoriteCarDto
    {
        $dto = $dto ?? new FavoriteCarDto();

                $dto->setId($model->id);
        $dto->setUserId($model->user_id);
        $dto->setCarId($model->car_id);
        $dto->setCreatedAt($model->created_at);

        return $dto;
    }

    /**
     * Convert DTO → Model
     */
    public function dtoToModel(FavoriteCarDto $dto, ?FavoriteCar $model = null): FavoriteCar
    {
        $model = $model ?? new FavoriteCar();

                $model->id = $dto->getId();
        $model->user_id = $dto->getUserId();
        $model->car_id = $dto->getCarId();
        $model->created_at = $dto->getCreatedAt();

        return $model;
    }

    /**
     * Convert Array → DTO
     */
    public function arrayToDto(array $data): FavoriteCarDto
    {
        $dto = new FavoriteCarDto();

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
    public function dtoToArray(FavoriteCarDto $dto): array
    {
        return [
                        'id' => $dto->getId(),
            'user_id' => $dto->getUserId(),
            'car_id' => $dto->getCarId(),
            'created_at' => $dto->getCreatedAt(),

        ];
    }
}

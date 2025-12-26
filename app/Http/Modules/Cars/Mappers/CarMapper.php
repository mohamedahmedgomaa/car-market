<?php

namespace App\Http\Modules\Cars\Mappers;

use App\Http\Modules\Cars\Models\Car;
use App\Http\Modules\Cars\Dtos\CarDto;

class CarMapper
{
    /**
     * Convert Model → DTO
     */
    public function modelToDto(Car $model, ?CarDto $dto = null): CarDto
    {
        $dto = $dto ?? new CarDto();

        $dto->setId($model->id);
        $dto->setSellerId($model->seller_id);
        $dto->setSeller($model->seller);
        $dto->setBrandId($model->brand_id);
        $dto->setBrand($model->brand);
        $dto->setModelId($model->model_id);
        $dto->setModel($model->model);
        $dto->setCityId($model->city_id);
        $dto->setCity($model->city);
        $dto->setCountryId($model->country_id);
        $dto->setCountry($model->country);
        $dto->setTitle($model->getTranslations('title'));
        $dto->setDescription($model->getTranslations('description'));
        $dto->setPrice($model->price);
        $dto->setYear($model->year);
        $dto->setMileage($model->mileage);
        $dto->setTransmission($model->transmission);
        $dto->setFuelType($model->fuel_type);
        $dto->setDrivetrain($model->drivetrain);
        $dto->setColor($model->color);
        $dto->setCondition($model->condition);
        $dto->setStatus($model->status);
        $dto->setImages($model->images->toArray());
        $dto->setFeatures($model->features->toArray());
        $dto->setFavorites($model->favorites->toArray());
        $dto->setCreatedAt($model->created_at);
        $dto->setUpdatedAt($model->updated_at);

        return $dto;
    }

    /**
     * Convert DTO → Model
     */
    public function dtoToModel(CarDto $dto, ?Car $model = null): Car
    {
        $model = $model ?? new Car();

        $model->id = $dto->getId();
        $model->seller_id = $dto->getSellerId();
        $model->brand_id = $dto->getBrandId();
        $model->model_id = $dto->getModelId();
        $model->city_id = $dto->getCityId();
        $model->country_id = $dto->getCountryId();
        $model->title = $dto->getTitle();
        $model->description = $dto->getDescription();
        $model->price = $dto->getPrice();
        $model->year = $dto->getYear();
        $model->mileage = $dto->getMileage();
        $model->transmission = $dto->getTransmission();
        $model->fuel_type = $dto->getFuelType();
        $model->drivetrain = $dto->getDrivetrain();
        $model->color = $dto->getColor();
        $model->condition = $dto->getCondition();
        $model->status = $dto->getStatus();
        $model->created_at = $dto->getCreatedAt();
        $model->updated_at = $dto->getUpdatedAt();

        return $model;
    }

    /**
     * Convert Array → DTO
     */
    public function arrayToDto(array $data): CarDto
    {
        $dto = new CarDto();

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
    public function dtoToArray(CarDto $dto): array
    {
        return [
            'id' => $dto->getId(),
            'seller_id' => $dto->getSellerId(),
            'brand_id' => $dto->getBrandId(),
            'model_id' => $dto->getModelId(),
            'city_id' => $dto->getCityId(),
            'country_id' => $dto->getCountryId(),
            'title' => $dto->getTitle(),
            'description' => $dto->getDescription(),
            'price' => $dto->getPrice(),
            'year' => $dto->getYear(),
            'mileage' => $dto->getMileage(),
            'transmission' => $dto->getTransmission(),
            'fuel_type' => $dto->getFuelType(),
            'drivetrain' => $dto->getDrivetrain(),
            'color' => $dto->getColor(),
            'condition' => $dto->getCondition(),
            'status' => $dto->getStatus(),
            'created_at' => $dto->getCreatedAt(),
            'updated_at' => $dto->getUpdatedAt(),

        ];
    }
}

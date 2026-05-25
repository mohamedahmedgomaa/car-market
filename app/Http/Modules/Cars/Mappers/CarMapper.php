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
        $dto->setType($model->type);
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

        $dto->setIsBestDeal($model->is_best_deal);
        $dto->setIsImport($model->is_import);
        $dto->setIsFeatured($model->is_featured);
        $dto->setShowOnHome($model->show_on_home);
        $dto->setIsGlobalAd($model->is_global_ad);
        $dto->setAdExpiry($model->ad_expiry);
        $dto->setFeaturedFee($model->featured_fee);

        $dto->setHorsepower($model->horsepower);
        $dto->setTorque($model->torque);
        $dto->setEngineCapacity($model->engine_capacity);
        $dto->setCylinders($model->cylinders);
        $dto->setPhoneNumber($model->phone_number);
        $dto->setWhatsappNumber($model->whatsapp_number);

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
        $model->type = $dto->getType();
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

        $model->is_best_deal = $dto->getIsBestDeal();
        $model->is_import = $dto->getIsImport();
        $model->is_featured = $dto->getIsFeatured();
        $model->show_on_home = $dto->getShowOnHome();
        $model->is_global_ad = $dto->getIsGlobalAd();
        $model->ad_expiry = $dto->getAdExpiry();
        $model->featured_fee = $dto->getFeaturedFee();

        $model->horsepower = $dto->getHorsepower();
        $model->torque = $dto->getTorque();
        $model->engine_capacity = $dto->getEngineCapacity();
        $model->cylinders = $dto->getCylinders();
        $model->phone_number = $dto->getPhoneNumber();
        $model->whatsapp_number = $dto->getWhatsappNumber();

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
            'type' => $dto->getType(),
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
            'is_best_deal' => $dto->getIsBestDeal(),
            'is_import' => $dto->getIsImport(),
            'is_featured' => $dto->getIsFeatured(),
            'show_on_home' => $dto->getShowOnHome(),
            'is_global_ad' => $dto->getIsGlobalAd(),
            'ad_expiry' => $dto->getAdExpiry(),
            'featured_fee' => $dto->getFeaturedFee(),
            'horsepower' => $dto->getHorsepower(),
            'torque' => $dto->getTorque(),
            'engine_capacity' => $dto->getEngineCapacity(),
            'cylinders' => $dto->getCylinders(),
            'phone_number' => $dto->getPhoneNumber(),
            'whatsapp_number' => $dto->getWhatsappNumber(),

        ];
    }
}

<?php

namespace App\Http\Modules\Cars\Mappers;

use App\Http\Modules\Cars\Dtos\CarDto;
use App\Http\Modules\Cars\Models\Car;

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
        if ($model->seller) {
            $model->seller->loadMissing(['city', 'governorate']);
        }
        $dto->setSeller($model->seller);
        $dto->setBrandId($model->brand_id);
        $dto->setBrand($model->brand);
        $dto->setModelId($model->model_id);
        $dto->setModel($model->model);
        $dto->setCityId($model->city_id);
        $dto->setCity($model->city);
        $dto->setGovernorateId($model->governorate_id);
        $dto->setGovernorate($model->governorate);
        $dto->setCountryId($model->country_id);
        $dto->setCountry($model->country);
        $dto->setType($model->type);
        $dto->setTitle($model->getTranslations('title'));
        $dto->setDescription($model->getTranslations('description'));
        $dto->setPrice($model->price);
        $dto->setOldPrice($model->old_price ?? null);
        $dto->setIsPriceDropped(!empty($model->old_price) && (float) $model->price < (float) $model->old_price);
        $dto->setSoldPrice($model->sold_price ?? null);
        $dto->setSoldAt($model->sold_at ?? null);
        $dto->setCurrency($model->currency ?? 'EGP');
        $dto->setYear($model->year);
        $dto->setMileage($model->mileage);
        $dto->setTransmission($model->transmission);
        $dto->setFuelType($model->fuel_type);
        $dto->setDrivetrain($model->drivetrain);
        $dto->setColor($model->color);
        $dto->setCondition($model->condition);
        $dto->setStatus($model->status);
        $dto->setRejectionReason($model->rejection_reason ?? null);
        $dto->setViewsCount($model->views_count ?? 0);
        $dto->setWhatsappClicksCount($model->whatsapp_clicks_count ?? 0);
        $dto->setPhoneClicksCount($model->phone_clicks_count ?? 0);
        $dto->setSharesCount($model->shares_count ?? 0);
        $dto->setImages($model->relationLoaded('images') ? $model->images->toArray() : []);
        $dto->setFeatures($model->relationLoaded('features') ? $model->features->toArray() : []);
        $dto->setFavorites($model->relationLoaded('favorites') ? $model->favorites->toArray() : []);
        $dto->setPriceHistories($model->relationLoaded('priceHistories') ? $model->priceHistories->toArray() : []);
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
        $dto->setAcceleration($model->acceleration);
        $dto->setWeight($model->weight);
        $dto->setPhoneNumber($model->phone_number);
        $dto->setWhatsappNumber($model->whatsapp_number);
        $dto->setYoutubeUrl($model->youtube_url);
        $dto->setInstagramUrl($model->instagram_url);
        $dto->setModel3dUrl($model->model_3d_url);
        $dto->setExterior360Images($model->exterior_360_images);
        $dto->setInterior360Url($model->interior_360_url);

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
        $model->governorate_id = $dto->getGovernorateId();
        $model->country_id = $dto->getCountryId();
        $model->type = $dto->getType();
        $model->title = $dto->getTitle();
        $model->description = $dto->getDescription();
        $model->price = $dto->getPrice();
        $model->old_price = $dto->getOldPrice();
        $model->sold_price = $dto->getSoldPrice();
        $model->sold_at = $dto->getSoldAt();
        $model->currency = $dto->getCurrency();
        $model->year = $dto->getYear();
        $model->mileage = $dto->getMileage();
        $model->transmission = $dto->getTransmission();
        $model->fuel_type = $dto->getFuelType();
        $model->drivetrain = $dto->getDrivetrain();
        $model->color = $dto->getColor();
        $model->condition = $dto->getCondition();
        $model->status = $dto->getStatus();
        $model->rejection_reason = $dto->getRejectionReason();
        $model->views_count = $dto->getViewsCount();
        $model->whatsapp_clicks_count = $dto->getWhatsappClicksCount();
        $model->phone_clicks_count = $dto->getPhoneClicksCount();
        $model->shares_count = $dto->getSharesCount();
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
        $model->acceleration = $dto->getAcceleration();
        $model->weight = $dto->getWeight();
        $model->phone_number = $dto->getPhoneNumber();
        $model->whatsapp_number = $dto->getWhatsappNumber();
        $model->youtube_url = $dto->getYoutubeUrl();
        $model->instagram_url = $dto->getInstagramUrl();
        $model->model_3d_url = $dto->getModel3dUrl();
        $model->exterior_360_images = $dto->getExterior360Images();
        $model->interior_360_url = $dto->getInterior360Url();

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
            'governorate_id' => $dto->getGovernorateId(),
            'country_id' => $dto->getCountryId(),
            'type' => $dto->getType(),
            'title' => $dto->getTitle(),
            'description' => $dto->getDescription(),
            'price' => $dto->getPrice(),
            'old_price' => $dto->getOldPrice(),
            'is_price_dropped' => $dto->getIsPriceDropped(),
            'sold_price' => $dto->getSoldPrice(),
            'sold_at' => $dto->getSoldAt(),
            'currency' => $dto->getCurrency(),
            'year' => $dto->getYear(),
            'mileage' => $dto->getMileage(),
            'transmission' => $dto->getTransmission(),
            'fuel_type' => $dto->getFuelType(),
            'drivetrain' => $dto->getDrivetrain(),
            'color' => $dto->getColor(),
            'condition' => $dto->getCondition(),
            'status' => $dto->getStatus(),
            'rejection_reason' => $dto->getRejectionReason(),
            'views_count' => $dto->getViewsCount(),
            'whatsapp_clicks_count' => $dto->getWhatsappClicksCount(),
            'phone_clicks_count' => $dto->getPhoneClicksCount(),
            'shares_count' => $dto->getSharesCount(),
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
            'acceleration' => $dto->getAcceleration(),
            'weight' => $dto->getWeight(),
            'phone_number' => $dto->getPhoneNumber(),
            'whatsapp_number' => $dto->getWhatsappNumber(),
            'youtube_url' => $dto->getYoutubeUrl(),
            'instagram_url' => $dto->getInstagramUrl(),
        ];
    }
}

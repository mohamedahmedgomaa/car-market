<?php

namespace App\Http\Modules\Sellers\Mappers;

use App\Http\Modules\Sellers\Dtos\SellerDto;
use App\Http\Modules\Sellers\Models\Seller;

class SellerMapper
{
    protected function canViewSensitive(Seller $model): bool
    {
        try {
            $shouldCheck = request()->bearerToken() !== null || app()->runningUnitTests() || app()->runningInConsole();
            if (!$shouldCheck) {
                return false;
            }

            if (auth('api_admin')->check()) {
                return true;
            }

            if (auth('api_seller')->check() && (int) auth('api_seller')->id() === (int) $model->id) {
                return true;
            }
        } catch (\Throwable $e) {
            return false;
        }

        return false;
    }

    /**
     * Convert Model → DTO
     */
    public function modelToDto(Seller $model, ?SellerDto $dto = null): SellerDto
    {
        $dto = $dto ?? new SellerDto();

        $canViewSensitive = $this->canViewSensitive($model);

        $dto->setId($model->id);
        $dto->setName($model->name);
        $dto->setEmail($model->email);
        $dto->setPhone($model->phone);
        $dto->setStoreName($model->getTranslations('store_name'));
        $dto->setStoreDescription($model->getTranslations('store_description'));
        $dto->setStoreLogo($model->store_logo);
        $dto->setCoverImage($model->cover_image);

        // Sensitive business/financial documents are only exposed to Admin or the Seller himself
        $dto->setBusinessLicense($canViewSensitive ? $model->business_license : null);
        $dto->setBankAccount($canViewSensitive ? $model->bank_account : null);
        $dto->setTaxNumber($canViewSensitive ? $model->tax_number : null);
        $dto->setTaxCardImage($canViewSensitive ? $model->tax_card_image : null);

        $dto->setIsVerified($model->is_verified);
        $dto->setIsActive($model->is_active);
        $dto->setCityId($model->city_id);
        $dto->setGovernorateId($model->governorate_id);
        $dto->setCity($model->city);
        $dto->setGovernorate($model->governorate);
        $dto->setAddress($model->getTranslations('address'));
        $dto->setMapUrl($model->map_url);
        $dto->setFacebook($model->facebook ?? $model->facebook_url);
        $dto->setInstagram($model->instagram ?? $model->instagram_url);
        $dto->setWebsite($model->website ?? $model->website_url);
        $dto->setYoutube($model->youtube ?? $model->youtube_url);
        $dto->setTiktok($model->tiktok ?? $model->tiktok_url);
        $dto->setSortOrder($model->sort_order);
        $dto->setTier($model->tier);
        $dto->setViewsCount($model->views_count ?? 0);
        $dto->setCarsCount($model->cars_count ?? $model->cars()->count());
        $dto->setCreatedAt($model->created_at);
        $dto->setUpdatedAt($model->updated_at);

        return $dto;
    }

    /**
     * Convert DTO → Model
     */
    public function dtoToModel(SellerDto $dto, ?Seller $model = null): Seller
    {
        $model = $model ?? new Seller();

        $model->id = $dto->getId();
        $model->name = $dto->getName();
        $model->email = $dto->getEmail();
        $model->phone = $dto->getPhone();
        $model->store_name = $dto->getStoreName();
        $model->store_description = $dto->getStoreDescription();
        $model->store_logo = $dto->getStoreLogo();
        $model->cover_image = $dto->getCoverImage();
        $model->business_license = $dto->getBusinessLicense();
        $model->bank_account = $dto->getBankAccount();
        $model->tax_number = $dto->getTaxNumber();
        $model->tax_card_image = $dto->getTaxCardImage();
        $model->is_verified = $dto->getIsVerified();
        $model->is_active = $dto->getIsActive();
        $model->city_id = $dto->getCityId();
        $model->governorate_id = $dto->getGovernorateId();
        $model->address = $dto->getAddress();
        $model->map_url = $dto->getMapUrl();
        $model->facebook = $dto->getFacebook();
        $model->instagram = $dto->getInstagram();
        $model->website = $dto->getWebsite();
        $model->youtube = $dto->getYoutube();
        $model->tiktok = $dto->getTiktok();
        $model->sort_order = $dto->getSortOrder();
        $model->tier = $dto->getTier();
        $model->views_count = $dto->getViewsCount();
        $model->created_at = $dto->getCreatedAt();
        $model->updated_at = $dto->getUpdatedAt();

        return $model;
    }

    /**
     * Convert Array → DTO
     */
    public function arrayToDto(array $data): SellerDto
    {
        $dto = new SellerDto();

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
    public function dtoToArray(SellerDto $dto): array
    {
        return [
            'id' => $dto->getId(),
            'name' => $dto->getName(),
            'email' => $dto->getEmail(),
            'phone' => $dto->getPhone(),
            'store_name' => $dto->getStoreName(),
            'store_description' => $dto->getStoreDescription(),
            'store_logo' => $dto->getStoreLogo(),
            'cover_image' => $dto->getCoverImage(),
            'business_license' => $dto->getBusinessLicense(),
            'bank_account' => $dto->getBankAccount(),
            'tax_number' => $dto->getTaxNumber(),
            'tax_card_image' => $dto->getTaxCardImage(),
            'is_verified' => $dto->getIsVerified(),
            'is_active' => $dto->getIsActive(),
            'city_id' => $dto->getCityId(),
            'governorate_id' => $dto->getGovernorateId(),
            'city' => $dto->getCity(),
            'governorate' => $dto->getGovernorate(),
            'address' => $dto->getAddress(),
            'map_url' => $dto->getMapUrl(),
            'facebook' => $dto->getFacebook(),
            'facebook_url' => $dto->getFacebook(),
            'instagram' => $dto->getInstagram(),
            'instagram_url' => $dto->getInstagram(),
            'website' => $dto->getWebsite(),
            'website_url' => $dto->getWebsite(),
            'youtube' => $dto->getYoutube(),
            'youtube_url' => $dto->getYoutube(),
            'tiktok' => $dto->getTiktok(),
            'tiktok_url' => $dto->getTiktok(),
            'sort_order' => $dto->getSortOrder(),
            'tier' => $dto->getTier(),
            'views_count' => $dto->getViewsCount(),
            'cars_count' => $dto->getCarsCount(),
            'created_at' => $dto->getCreatedAt(),
            'updated_at' => $dto->getUpdatedAt(),
        ];
    }
}

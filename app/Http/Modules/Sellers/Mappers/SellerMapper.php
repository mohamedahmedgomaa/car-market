<?php

namespace App\Http\Modules\Sellers\Mappers;

use App\Http\Modules\Sellers\Models\Seller;
use App\Http\Modules\Sellers\Dtos\SellerDto;

class SellerMapper
{
    /**
     * Convert Model → DTO
     */
    public function modelToDto(Seller $model, ?SellerDto $dto = null): SellerDto
    {
        $dto = $dto ?? new SellerDto();

        $dto->setId($model->id);
        $dto->setName($model->name);
        $dto->setEmail($model->email);
        $dto->setPassword($model->password);
        $dto->setPhone($model->phone);
        $dto->setStoreName($model->getTranslations('store_name'));
        $dto->setStoreDescription($model->getTranslations('store_description'));
        $dto->setStoreLogo($model->store_logo);
        $dto->setBusinessLicense($model->business_license);
        $dto->setBankAccount($model->bank_account);
        $dto->setIsVerified($model->is_verified);
        $dto->setIsActive($model->is_active);
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
        $model->password = $dto->getPassword();
        $model->phone = $dto->getPhone();
        $model->store_name = $dto->getStoreName();
        $model->store_description = $dto->getStoreDescription();
        $model->store_logo = $dto->getStoreLogo();
        $model->business_license = $dto->getBusinessLicense();
        $model->bank_account = $dto->getBankAccount();
        $model->is_verified = $dto->getIsVerified();
        $model->is_active = $dto->getIsActive();
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
            'password' => $dto->getPassword(),
            'phone' => $dto->getPhone(),
            'store_name' => $dto->getStoreName(),
            'store_description' => $dto->getStoreDescription(),
            'store_logo' => $dto->getStoreLogo(),
            'business_license' => $dto->getBusinessLicense(),
            'bank_account' => $dto->getBankAccount(),
            'is_verified' => $dto->getIsVerified(),
            'is_active' => $dto->getIsActive(),
            'created_at' => $dto->getCreatedAt(),
            'updated_at' => $dto->getUpdatedAt(),

        ];
    }
}

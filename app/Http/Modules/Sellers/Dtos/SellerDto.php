<?php

namespace App\Http\Modules\Sellers\Dtos;

class SellerDto implements \JsonSerializable
{
    private $id;
    private $name;
    private $email;
    private $password;
    private $phone;
    private $store_name;
    private $store_description;
    private $store_logo;
    private $business_license;
    private $bank_account;
    private $is_verified;
    private $is_active;
    private $created_at;
    private $city_id;
    private $governorate_id;
    private $city;
    private $governorate;
    private $address;
    private $map_url;
    private $sort_order;
    private $tier;

    public function getTier()
    {
        return $this->tier;
    }

    public function setTier($value): void
    {
        $this->tier = $value;
    }

    public function getSortOrder()
    {
        return $this->sort_order;
    }

    public function setSortOrder($value): void
    {
        $this->sort_order = $value;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($value): void
    {
        $this->address = $value;
    }

    public function getMapUrl()
    {
        return $this->map_url;
    }

    public function setMapUrl($value): void
    {
        $this->map_url = $value;
    }

    public function getCityId()
    {
        return $this->city_id;
    }

    public function setCityId($value): void
    {
        $this->city_id = $value;
    }

    public function getGovernorateId()
    {
        return $this->governorate_id;
    }

    public function setGovernorateId($value): void
    {
        $this->governorate_id = $value;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($value): void
    {
        $this->city = $value;
    }

    public function getGovernorate()
    {
        return $this->governorate;
    }

    public function setGovernorate($value): void
    {
        $this->governorate = $value;
    }


    public function getId()
    {
        return $this->id;
    }

    public function setId($value): void
    {
        $this->id = $value;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($value): void
    {
        $this->name = $value;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($value): void
    {
        $this->email = $value;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($value): void
    {
        $this->password = $value;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($value): void
    {
        $this->phone = $value;
    }

    public function getStoreName()
    {
        return $this->store_name;
    }

    public function setStoreName($value): void
    {
        $this->store_name = $value;
    }

    public function getStoreDescription()
    {
        return $this->store_description;
    }

    public function setStoreDescription($value): void
    {
        $this->store_description = $value;
    }

    public function getStoreLogo()
    {
        return $this->store_logo;
    }

    public function setStoreLogo($value): void
    {
        $this->store_logo = $value;
    }

    public function getBusinessLicense()
    {
        return $this->business_license;
    }

    public function setBusinessLicense($value): void
    {
        $this->business_license = $value;
    }

    public function getBankAccount()
    {
        return $this->bank_account;
    }

    public function setBankAccount($value): void
    {
        $this->bank_account = $value;
    }

    public function getIsVerified()
    {
        return $this->is_verified;
    }

    public function setIsVerified($value): void
    {
        $this->is_verified = $value;
    }

    public function getIsActive()
    {
        return $this->is_active;
    }

    public function setIsActive($value): void
    {
        $this->is_active = $value;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt($value): void
    {
        $this->created_at = $value;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function setUpdatedAt($value): void
    {
        $this->updated_at = $value;
    }


    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}

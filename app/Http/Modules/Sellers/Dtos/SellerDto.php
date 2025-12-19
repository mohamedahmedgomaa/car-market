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
    private $updated_at;


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

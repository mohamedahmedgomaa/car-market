<?php

namespace App\Http\Modules\Cars\Dtos;

class CarDto implements \JsonSerializable
{
    private $id;
    private $seller_id;
    private ?object $seller = null;
    private $brand_id;
    private ?object $brand = null;
    private $model_id;
    private ?object $model = null;
    private $city_id;
    private ?object $city = null;
    private $country_id;
    private ?object $country = null;
    private $type;
    private $title;
    private $description;
    private $price;
    private $year;
    private $mileage;
    private $transmission;
    private $fuel_type;
    private $drivetrain;
    private $color;
    private $condition;
    private $status;
    private ?array $images = [];
    private ?array $features = [];
    private ?array $favorites = [];
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

    public function getSellerId()
    {
        return $this->seller_id;
    }

    public function setSellerId($value): void
    {
        $this->seller_id = $value;
    }

    public function getBrandId()
    {
        return $this->brand_id;
    }

    public function setBrandId($value): void
    {
        $this->brand_id = $value;
    }

    public function getModelId()
    {
        return $this->model_id;
    }

    public function setModelId($value): void
    {
        $this->model_id = $value;
    }

    public function getCityId()
    {
        return $this->city_id;
    }

    public function setCityId($value): void
    {
        $this->city_id = $value;
    }

    public function getCountryId()
    {
        return $this->country_id;
    }

    public function setCountryId($value): void
    {
        $this->country_id = $value;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($value): void
    {
        $this->type = $value;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($value): void
    {
        $this->title = $value;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($value): void
    {
        $this->description = $value;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($value): void
    {
        $this->price = $value;
    }

    public function getYear()
    {
        return $this->year;
    }

    public function setYear($value): void
    {
        $this->year = $value;
    }

    public function getMileage()
    {
        return $this->mileage;
    }

    public function setMileage($value): void
    {
        $this->mileage = $value;
    }

    public function getTransmission()
    {
        return $this->transmission;
    }

    public function setTransmission($value): void
    {
        $this->transmission = $value;
    }

    public function getFuelType()
    {
        return $this->fuel_type;
    }

    public function setFuelType($value): void
    {
        $this->fuel_type = $value;
    }

    public function getDrivetrain()
    {
        return $this->drivetrain;
    }

    public function setDrivetrain($value): void
    {
        $this->drivetrain = $value;
    }

    public function getColor()
    {
        return $this->color;
    }

    public function setColor($value): void
    {
        $this->color = $value;
    }

    public function getCondition()
    {
        return $this->condition;
    }

    public function setCondition($value): void
    {
        $this->condition = $value;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($value): void
    {
        $this->status = $value;
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

    /**
     * @param object|null $brand
     */
    public function setBrand(?object $brand): void
    {
        $this->brand = $brand;
    }

    /**
     * @param object|null $city
     */
    public function setCity(?object $city): void
    {
        $this->city = $city;
    }

    /**
     * @param object|null $country
     */
    public function setCountry(?object $country): void
    {
        $this->country = $country;
    }

    /**
     * @param object|null $model
     */
    public function setModel(?object $model): void
    {
        $this->model = $model;
    }

    /**
     * @param object|null $seller
     */
    public function setSeller(?object $seller): void
    {
        $this->seller = $seller;
    }

    /**
     * @return object|null
     */
    public function getSeller(): ?object
    {
        return $this->seller;
    }

    /**
     * @return object|null
     */
    public function getBrand(): ?object
    {
        return $this->brand;
    }

    /**
     * @return object|null
     */
    public function getCity(): ?object
    {
        return $this->city;
    }

    /**
     * @return object|null
     */
    public function getCountry(): ?object
    {
        return $this->country;
    }

    /**
     * @return object|null
     */
    public function getModel(): ?object
    {
        return $this->model;
    }

    /**
     * @return array|null
     */
    public function getImages(): ?array
    {
        return $this->images;
    }

    /**
     * @param array|null $images
     */
    public function setImages(?array $images): void
    {
        $this->images = $images;
    }

    /**
     * @return array|null
     */
    public function getFeatures(): ?array
    {
        return $this->features;
    }

    /**
     * @param array|null $features
     */
    public function setFeatures(?array $features): void
    {
        $this->features = $features;
    }

    /**
     * @return array|null
     */
    public function getFavorites(): ?array
    {
        return $this->favorites;
    }

    /**
     * @param array|null $favorites
     */
    public function setFavorites(?array $favorites): void
    {
        $this->favorites = $favorites;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}

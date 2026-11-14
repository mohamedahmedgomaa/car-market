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
    private $governorate_id;
    private ?object $governorate = null;
    private $country_id;
    private ?object $country = null;
    private $type;
    private $title;
    private $description;
    private $price;
    private $old_price;
    private $is_price_dropped = false;
    private $sold_price;
    private $sold_at;
    private $currency;
    private $year;
    private $mileage;
    private $transmission;
    private $fuel_type;
    private $drivetrain;
    private $color;
    private $condition;
    private $status;
    private $rejection_reason;
    private $views_count = 0;
    private $whatsapp_clicks_count = 0;
    private $phone_clicks_count = 0;
    private $shares_count = 0;
    private ?array $images = [];
    private ?array $features = [];
    private ?array $favorites = [];
    private ?array $price_histories = [];
    private $created_at;
    private $updated_at;

    private $is_best_deal;
    private $is_import;
    private $is_featured;
    private $show_on_home;
    private $is_global_ad;
    private $ad_expiry;
    private $featured_fee;

    private $horsepower;
    private $torque;
    private $engine_capacity;
    private $cylinders;
    private $acceleration;
    private $weight;
    private $phone_number;
    private $whatsapp_number;
    private $youtube_url;
    private $instagram_url;
    private $model_3d_url;
    private $exterior_360_images;
    private $interior_360_url;

    public function getId() { return $this->id; }
    public function setId($value): void { $this->id = $value; }

    public function getSellerId() { return $this->seller_id; }
    public function setSellerId($value): void { $this->seller_id = $value; }

    public function getBrandId() { return $this->brand_id; }
    public function setBrandId($value): void { $this->brand_id = $value; }

    public function getModelId() { return $this->model_id; }
    public function setModelId($value): void { $this->model_id = $value; }

    public function getCityId() { return $this->city_id; }
    public function setCityId($value): void { $this->city_id = $value; }

    public function getGovernorateId() { return $this->governorate_id; }
    public function setGovernorateId($value): void { $this->governorate_id = $value; }

    public function getCountryId() { return $this->country_id; }
    public function setCountryId($value): void { $this->country_id = $value; }

    public function getType() { return $this->type; }
    public function setType($value): void { $this->type = $value; }

    public function getTitle() { return $this->title; }
    public function setTitle($value): void { $this->title = $value; }

    public function getDescription() { return $this->description; }
    public function setDescription($value): void { $this->description = $value; }

    public function getPrice() { return $this->price; }
    public function setPrice($value): void { $this->price = $value; }

    public function getOldPrice() { return $this->old_price; }
    public function setOldPrice($value): void { $this->old_price = $value; }

    public function getIsPriceDropped(): bool { return (bool) $this->is_price_dropped; }
    public function setIsPriceDropped($value): void { $this->is_price_dropped = (bool) $value; }

    public function getSoldPrice() { return $this->sold_price; }
    public function setSoldPrice($value): void { $this->sold_price = $value; }

    public function getSoldAt() { return $this->sold_at; }
    public function setSoldAt($value): void { $this->sold_at = $value; }

    public function getCurrency() { return $this->currency ?? 'EGP'; }
    public function setCurrency($value): void { $this->currency = $value; }

    public function getYear() { return $this->year; }
    public function setYear($value): void { $this->year = $value; }

    public function getMileage() { return $this->mileage; }
    public function setMileage($value): void { $this->mileage = $value; }

    public function getTransmission() { return $this->transmission; }
    public function setTransmission($value): void { $this->transmission = $value; }

    public function getFuelType() { return $this->fuel_type; }
    public function setFuelType($value): void { $this->fuel_type = $value; }

    public function getDrivetrain() { return $this->drivetrain; }
    public function setDrivetrain($value): void { $this->drivetrain = $value; }

    public function getColor() { return $this->color; }
    public function setColor($value): void { $this->color = $value; }

    public function getCondition() { return $this->condition; }
    public function setCondition($value): void { $this->condition = $value; }

    public function getStatus() { return $this->status; }
    public function setStatus($value): void { $this->status = $value; }

    public function getRejectionReason() { return $this->rejection_reason; }
    public function setRejectionReason($value): void { $this->rejection_reason = $value; }

    public function getViewsCount(): int { return (int) ($this->views_count ?? 0); }
    public function setViewsCount($value): void { $this->views_count = (int) $value; }

    public function getWhatsappClicksCount(): int { return (int) ($this->whatsapp_clicks_count ?? 0); }
    public function setWhatsappClicksCount($value): void { $this->whatsapp_clicks_count = (int) $value; }

    public function getPhoneClicksCount(): int { return (int) ($this->phone_clicks_count ?? 0); }
    public function setPhoneClicksCount($value): void { $this->phone_clicks_count = (int) $value; }

    public function getSharesCount(): int { return (int) ($this->shares_count ?? 0); }
    public function setSharesCount($value): void { $this->shares_count = (int) $value; }

    public function getCreatedAt() { return $this->created_at; }
    public function setCreatedAt($value): void { $this->created_at = $value; }

    public function getUpdatedAt() { return $this->updated_at; }
    public function setUpdatedAt($value): void { $this->updated_at = $value; }

    public function setBrand(?object $brand): void { $this->brand = $brand; }
    public function setCity(?object $city): void { $this->city = $city; }
    public function setGovernorate(?object $governorate): void { $this->governorate = $governorate; }
    public function setCountry(?object $country): void { $this->country = $country; }
    public function setModel(?object $model): void { $this->model = $model; }
    public function setSeller(?object $seller): void { $this->seller = $seller; }

    public function getSeller(): ?object { return $this->seller; }
    public function getBrand(): ?object { return $this->brand; }
    public function getCity(): ?object { return $this->city; }
    public function getGovernorate(): ?object { return $this->governorate; }
    public function getCountry(): ?object { return $this->country; }
    public function getModel(): ?object { return $this->model; }

    public function getImages(): ?array { return $this->images; }
    public function setImages(?array $images): void { $this->images = $images; }

    public function getFeatures(): ?array { return $this->features; }
    public function setFeatures(?array $features): void { $this->features = $features; }

    public function getFavorites(): ?array { return $this->favorites; }
    public function setFavorites(?array $favorites): void { $this->favorites = $favorites; }

    public function getPriceHistories(): ?array { return $this->price_histories; }
    public function setPriceHistories(?array $histories): void { $this->price_histories = $histories; }

    public function getIsBestDeal() { return $this->is_best_deal; }
    public function setIsBestDeal($v) { $this->is_best_deal = $v; }

    public function getIsImport() { return $this->is_import; }
    public function setIsImport($v) { $this->is_import = $v; }

    public function getIsFeatured() { return $this->is_featured; }
    public function setIsFeatured($v) { $this->is_featured = $v; }

    public function getShowOnHome() { return $this->show_on_home; }
    public function setShowOnHome($v) { $this->show_on_home = $v; }

    public function getIsGlobalAd() { return $this->is_global_ad; }
    public function setIsGlobalAd($v) { $this->is_global_ad = $v; }

    public function getAdExpiry() { return $this->ad_expiry; }
    public function setAdExpiry($v) { $this->ad_expiry = $v; }

    public function getFeaturedFee() { return $this->featured_fee; }
    public function setFeaturedFee($v) { $this->featured_fee = $v; }

    public function getHorsepower() { return $this->horsepower; }
    public function setHorsepower($v) { $this->horsepower = $v; }

    public function getTorque() { return $this->torque; }
    public function setTorque($v) { $this->torque = $v; }

    public function getEngineCapacity() { return $this->engine_capacity; }
    public function setEngineCapacity($v) { $this->engine_capacity = $v; }

    public function getCylinders() { return $this->cylinders; }
    public function setCylinders($v) { $this->cylinders = $v; }

    public function getAcceleration() { return $this->acceleration; }
    public function setAcceleration($v) { $this->acceleration = $v; }

    public function getWeight() { return $this->weight; }
    public function setWeight($v) { $this->weight = $v; }

    public function getPhoneNumber() { return $this->phone_number; }
    public function setPhoneNumber($v) { $this->phone_number = $v; }

    public function getWhatsappNumber() { return $this->whatsapp_number; }
    public function setWhatsappNumber($v) { $this->whatsapp_number = $v; }

    public function getYoutubeUrl() { return $this->youtube_url; }
    public function setYoutubeUrl($v) { $this->youtube_url = $v; }

    public function getInstagramUrl() { return $this->instagram_url; }
    public function setInstagramUrl($v) { $this->instagram_url = $v; }

    public function getModel3dUrl() { return $this->model_3d_url; }
    public function setModel3dUrl($v) { $this->model_3d_url = $v; }

    public function getExterior360Images() { return $this->exterior_360_images; }
    public function setExterior360Images($v) { $this->exterior_360_images = $v; }

    public function getInterior360Url() { return $this->interior_360_url; }
    public function setInterior360Url($v) { $this->interior_360_url = $v; }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}

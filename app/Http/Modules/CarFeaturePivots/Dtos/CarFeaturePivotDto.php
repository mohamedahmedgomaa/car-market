<?php

namespace App\Http\Modules\CarFeaturePivots\Dtos;

class CarFeaturePivotDto implements \JsonSerializable
{
    private $id;
    private $car_id;
    private $car_feature_id;
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

    public function getCarId()
    {
        return $this->car_id;
    }

    public function setCarId($value): void
    {
        $this->car_id = $value;
    }

    public function getCarFeatureId()
    {
        return $this->car_feature_id;
    }

    public function setCarFeatureId($value): void
    {
        $this->car_feature_id = $value;
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

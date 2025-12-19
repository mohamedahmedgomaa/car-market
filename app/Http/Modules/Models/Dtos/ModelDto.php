<?php

namespace App\Http\Modules\Models\Dtos;

class ModelDto implements \JsonSerializable
{
    private $id;
    private $brand_id;
    private ?object $brand = null;
    private $name;
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

    public function getBrandId()
    {
        return $this->brand_id;
    }

    public function setBrandId($value): void
    {
        $this->brand_id = $value;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($value): void
    {
        $this->name = $value;
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

    public function getBrand(): ?object
    {
        return $this->brand;
    }

    public function setBrand(?object $brand): void
    {
        $this->brand = $brand;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}

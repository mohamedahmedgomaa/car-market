<?php

namespace App\Http\Modules\Models\Dtos;

class ModelDto implements \JsonSerializable
{
    private $id;
    private $brand_id;
    private ?object $brand = null;
    private $name;
    private $type;
    private $model_3d_url;
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

    public function getType()
    {
        return $this->type;
    }

    public function setType($value): void
    {
        $this->type = $value;
    }

    public function getModel3dUrl()
    {
        return $this->model_3d_url;
    }

    public function setModel3dUrl($value): void
    {
        $this->model_3d_url = $value;
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

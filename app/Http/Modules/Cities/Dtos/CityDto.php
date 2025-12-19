<?php

namespace App\Http\Modules\Cities\Dtos;

class CityDto implements \JsonSerializable
{
    private int $id;
    private int $country_id;
    private $name;
    private string $created_at;
    private string $updated_at;
    private ?object $country = null;


    public function getId()
    {
        return $this->id;
    }

    public function setId($value): void
    {
        $this->id = $value;
    }

    public function getCountryId()
    {
        return $this->country_id;
    }

    public function setCountryId($value): void
    {
        $this->country_id = $value;
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

    public function getCountry(): ?object
    {
        return $this->country;
    }

    public function setCountry(?object $value): void
    {
        $this->country = $value;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}

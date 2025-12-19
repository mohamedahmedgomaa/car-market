<?php

namespace App\Http\Modules\Countries\Dtos;

class CountryDto implements \JsonSerializable
{
    private $id;
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


    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}

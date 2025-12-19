<?php

namespace App\Http\Modules\CarImages\Dtos;

class CarImageDto implements \JsonSerializable
{
    private $id;
    private $car_id;
    private $path;
    private $is_main;
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

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($value): void
    {
        $this->path = $value;
    }

    public function getIsMain()
    {
        return $this->is_main;
    }

    public function setIsMain($value): void
    {
        $this->is_main = $value;
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

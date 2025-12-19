<?php

namespace App\Http\Modules\FavoriteCars\Dtos;

class FavoriteCarDto implements \JsonSerializable
{
    private $id;
    private $user_id;
    private $car_id;
    private $created_at;


    public function getId()
    {
        return $this->id;
    }

    public function setId($value): void
    {
        $this->id = $value;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId($value): void
    {
        $this->user_id = $value;
    }

    public function getCarId()
    {
        return $this->car_id;
    }

    public function setCarId($value): void
    {
        $this->car_id = $value;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt($value): void
    {
        $this->created_at = $value;
    }


    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}

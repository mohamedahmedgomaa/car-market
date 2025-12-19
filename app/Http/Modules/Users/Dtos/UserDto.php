<?php

namespace App\Http\Modules\Users\Dtos;

class UserDto implements \JsonSerializable
{
    private $id;
    private $name;
    private $email;
    private $password;
    private $phone;
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

<?php

namespace App\Http\Modules\Users\Mappers;

use App\Http\Modules\Users\Models\User;
use App\Http\Modules\Users\Dtos\UserDto;

class UserMapper
{
    /**
     * Convert Model → DTO
     */
    public function modelToDto(User $model, ?UserDto $dto = null): UserDto
    {
        $dto = $dto ?? new UserDto();

        $dto->setId($model->id);
        $dto->setName($model->name);
        $dto->setEmail($model->email);
        $dto->setPassword($model->password);
        $dto->setPhone($model->phone);
        $dto->setIsActive($model->is_active);
        $dto->setCreatedAt($model->created_at);
        $dto->setUpdatedAt($model->updated_at);

        return $dto;
    }

    /**
     * Convert DTO → Model
     */
    public function dtoToModel(UserDto $dto, ?User $model = null): User
    {
        $model = $model ?? new User();

        $model->id = $dto->getId();
        $model->name = $dto->getName();
        $model->email = $dto->getEmail();
        $model->password = $dto->getPassword();
        $model->phone = $dto->getPhone();
        $model->is_active = $dto->getIsActive();
        $model->created_at = $dto->getCreatedAt();
        $model->updated_at = $dto->getUpdatedAt();

        return $model;
    }

    /**
     * Convert Array → DTO
     */
    public function arrayToDto(array $data): UserDto
    {
        $dto = new UserDto();

        foreach ($data as $key => $value) {
            $method = 'set' . \Illuminate\Support\Str::studly($key);
            if (method_exists($dto, $method)) {
                $dto->$method($value);
            }
        }

        return $dto;
    }

    /**
     * Convert DTO → Array
     */
    public function dtoToArray(UserDto $dto): array
    {
        return [
            'id' => $dto->getId(),
            'name' => $dto->getName(),
            'email' => $dto->getEmail(),
            'password' => $dto->getPassword(),
            'phone' => $dto->getPhone(),
            'is_active' => $dto->getIsActive(),
            'created_at' => $dto->getCreatedAt(),
            'updated_at' => $dto->getUpdatedAt(),

        ];
    }
}

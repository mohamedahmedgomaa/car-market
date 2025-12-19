<?php

namespace App\Http\Modules\Admins\Mappers;

use App\Http\Modules\Admins\Models\Admin;
use App\Http\Modules\Admins\Dtos\AdminDto;

class AdminMapper
{
    /**
     * Convert Model → DTO
     */
    public function modelToDto(Admin $model, ?AdminDto $dto = null): AdminDto
    {
        $dto = $dto ?? new AdminDto();

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
    public function dtoToModel(AdminDto $dto, ?Admin $model = null): Admin
    {
        $model = $model ?? new Admin();

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
    public function arrayToDto(array $data): AdminDto
    {
        $dto = new AdminDto();

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
    public function dtoToArray(AdminDto $dto): array
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

<?php

namespace App\Http\Modules\Notifications\Mappers;

use App\Http\Modules\Notifications\Models\Notification;
use App\Http\Modules\Notifications\Dtos\NotificationDto;

class NotificationMapper
{
    /**
     * Convert Model → DTO
     */
    public function modelToDto(Notification $model, ?NotificationDto $dto = null): NotificationDto
    {
        $dto = $dto ?? new NotificationDto();

                $dto->setId($model->id);
        $dto->setUserId($model->user_id);
        $dto->setTitle($model->title);
        $dto->setBody($model->body);
        $dto->setIsRead($model->is_read);
        $dto->setCreatedAt($model->created_at);
        $dto->setUpdatedAt($model->updated_at);

        return $dto;
    }

    /**
     * Convert DTO → Model
     */
    public function dtoToModel(NotificationDto $dto, ?Notification $model = null): Notification
    {
        $model = $model ?? new Notification();

                $model->id = $dto->getId();
        $model->user_id = $dto->getUserId();
        $model->title = $dto->getTitle();
        $model->body = $dto->getBody();
        $model->is_read = $dto->getIsRead();
        $model->created_at = $dto->getCreatedAt();
        $model->updated_at = $dto->getUpdatedAt();

        return $model;
    }

    /**
     * Convert Array → DTO
     */
    public function arrayToDto(array $data): NotificationDto
    {
        $dto = new NotificationDto();

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
    public function dtoToArray(NotificationDto $dto): array
    {
        return [
                        'id' => $dto->getId(),
            'user_id' => $dto->getUserId(),
            'title' => $dto->getTitle(),
            'body' => $dto->getBody(),
            'is_read' => $dto->getIsRead(),
            'created_at' => $dto->getCreatedAt(),
            'updated_at' => $dto->getUpdatedAt(),

        ];
    }
}

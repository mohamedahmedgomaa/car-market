<?php

namespace App\Http\Modules\Notifications\Dtos;

class NotificationDto implements \JsonSerializable
{
    private $id;
    private $user_id;
    private $seller_id;
    private $type;
    private $title;
    private $body;
    private $data;
    private $is_read;
    private $created_at;
    private $updated_at;

    public function getId() { return $this->id; }
    public function setId($value): void { $this->id = $value; }

    public function getUserId() { return $this->user_id; }
    public function setUserId($value): void { $this->user_id = $value; }

    public function getSellerId() { return $this->seller_id; }
    public function setSellerId($value): void { $this->seller_id = $value; }

    public function getType() { return $this->type; }
    public function setType($value): void { $this->type = $value; }

    public function getTitle() { return $this->title; }
    public function setTitle($value): void { $this->title = $value; }

    public function getBody() { return $this->body; }
    public function setBody($value): void { $this->body = $value; }

    public function getData() { return $this->data; }
    public function setData($value): void { $this->data = $value; }

    public function getIsRead() { return $this->is_read; }
    public function setIsRead($value): void { $this->is_read = (bool) $value; }

    public function getCreatedAt() { return $this->created_at; }
    public function setCreatedAt($value): void { $this->created_at = $value; }

    public function getUpdatedAt() { return $this->updated_at; }
    public function setUpdatedAt($value): void { $this->updated_at = $value; }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}

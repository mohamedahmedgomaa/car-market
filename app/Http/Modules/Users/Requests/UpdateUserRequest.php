<?php

namespace App\Http\Modules\Users\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class UpdateUserRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|string',
            'email' => 'nullable|string|unique:users,email,' . $this->user,
            'phone' => 'nullable|string|unique:users,phone,' . $this->user,
            'is_active' => 'nullable|boolean',
        ];
    }
}

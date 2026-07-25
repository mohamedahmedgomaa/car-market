<?php

namespace App\Http\Modules\Admins\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class CreateAdminRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|string|unique:admins,email',
            'password' => 'required|confirmed|string',
            'phone' => 'nullable|string',
        ];
    }
}

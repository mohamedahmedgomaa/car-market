<?php

namespace App\Http\Modules\Admins\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class UpdateAdminRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|string',
            'email' => 'nullable|string|unique:admins,email,' . $this->admin,
            'phone' => 'nullable|string',
        ];
    }
}

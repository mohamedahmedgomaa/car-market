<?php

namespace App\Http\Modules\Admins\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class LoginAdminRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:admins,email',
            'password' => 'required',
        ];
    }
}

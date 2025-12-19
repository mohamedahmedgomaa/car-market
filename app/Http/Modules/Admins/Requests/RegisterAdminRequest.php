<?php

namespace App\Http\Modules\Admins\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;
use Illuminate\Support\Facades\Hash;

class RegisterAdminRequest extends BaseRequest
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
        ];
    }

    public function passedValidation(){
        $this->merge([
            'password' => Hash::make($this->password),
        ]);
    }
}

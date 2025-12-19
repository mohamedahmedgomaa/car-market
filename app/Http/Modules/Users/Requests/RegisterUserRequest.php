<?php

namespace App\Http\Modules\Users\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;
use Illuminate\Support\Facades\Hash;

class RegisterUserRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'phone' => 'required|unique:users,phone',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|confirmed|string',
        ];
    }

    public function passedValidation(){
        $this->merge([
            'password' => Hash::make($this->password),
        ]);
    }
}

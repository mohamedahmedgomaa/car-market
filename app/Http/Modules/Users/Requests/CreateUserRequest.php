<?php

namespace App\Http\Modules\Users\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;
use Illuminate\Support\Facades\Hash;

class CreateUserRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'phone' => 'required|unique:users,phone',
            'password' => 'required|confirmed|string',
        ];
    }
    public function passedValidation(){
        $this->merge([
            'password' => Hash::make($this->password),
        ]);
    }
}

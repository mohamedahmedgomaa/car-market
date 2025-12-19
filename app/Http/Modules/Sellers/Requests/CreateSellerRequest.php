<?php

namespace App\Http\Modules\Sellers\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;
use Illuminate\Support\Facades\Hash;

class CreateSellerRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|string|unique:sellers,email',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string',
            'store_name_ar' => 'required',
            'store_name_en' => 'required',
            'store_description_ar' => 'nullable',
            'store_description_en' => 'nullable',
            'store_logo' => 'nullable|image|max:2048',
            'business_license' => 'nullable|string',
            'bank_account' => 'nullable|string',
            'is_verified' => 'nullable|boolean',
        ];
    }

    public function passedValidation()
    {
        $this->merge([
            'password' => Hash::make($this->password),
            'store_name' => [
                'en' => $this->store_name_en,
                'ar' => $this->store_name_ar,
            ],
            'store_description' => [
                'en' => $this->store_description_en,
                'ar' => $this->store_description_ar,
            ]
        ]);
    }
}

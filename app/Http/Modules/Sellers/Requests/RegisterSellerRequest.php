<?php

namespace App\Http\Modules\Sellers\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;
use Illuminate\Support\Facades\Hash;

class RegisterSellerRequest extends BaseRequest
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
            'phone' => 'nullable|string|max:20',
            'store_name_en' => 'required|string|max:255',
            'store_name_ar' => 'required|string|max:255',
            'store_description_en' => 'nullable|string',
            'store_description_ar' => 'nullable|string',
            'store_logo' => 'nullable|image|max:2048',
            'business_license' => 'nullable|string|max:255',
            'bank_account' => 'nullable|string|max:255',
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

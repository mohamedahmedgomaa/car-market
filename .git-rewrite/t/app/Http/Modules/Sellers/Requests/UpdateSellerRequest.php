<?php

namespace App\Http\Modules\Sellers\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;
use Illuminate\Support\Facades\Hash;

class UpdateSellerRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:sellers,email,' . $this->seller,
            'password' => 'nullable|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'store_name_ar' => 'nullable',
            'store_name_en' => 'nullable',
            'store_description_ar' => 'nullable',
            'store_description_en' => 'nullable',
            'store_logo' => 'nullable|image|max:2048',
            'business_license' => 'nullable|string|max:255',
            'bank_account' => 'nullable|string|max:255',
        ];
    }

    public function passedValidation()
    {
        $data = [];

        $storeName = [];
        if (!is_null($this->store_name_en)) {
            $storeName['en'] = $this->store_name_en;
        }
        if (!is_null($this->store_name_ar)) {
            $storeName['ar'] = $this->store_name_ar;
        }
        if (!empty($storeName)) {
            $data['store_name'] = $storeName;
        }

        $storeDescription = [];
        if (!is_null($this->store_description_en)) {
            $storeDescription['en'] = $this->store_description_en;
        }
        if (!is_null($this->store_description_ar)) {
            $storeDescription['ar'] = $this->store_description_ar;
        }
        if (!empty($storeDescription)) {
            $data['store_description'] = $storeDescription;
        }

        $this->merge($data);
    }
}

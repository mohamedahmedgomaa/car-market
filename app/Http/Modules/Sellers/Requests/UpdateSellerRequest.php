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
        $routeSeller = $this->route('seller');
        $sellerId = is_object($routeSeller) ? $routeSeller->id : ($routeSeller ?? $this->route('id') ?? $this->seller);

        return [
            'name' => 'sometimes|string|max:255',
            'email' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'store_name_ar' => 'nullable',
            'store_name_en' => 'nullable',
            'store_description_ar' => 'nullable',
            'store_description_en' => 'nullable',
            'store_logo' => 'nullable|image|max:2048',
            'cover_image' => 'nullable|image|max:2048',
            'business_license' => 'nullable|string|max:255',
            'bank_account' => 'nullable|string|max:255',
            'city_id' => 'nullable|exists:cities,id',
            'governorate_id' => 'nullable|exists:governorates,id',
            'address_ar' => 'nullable|string|max:255',
            'address_en' => 'nullable|string|max:255',
            'map_url' => 'nullable|string|max:2048',
            'facebook' => 'nullable|string|max:2048',
            'facebook_url' => 'nullable|string|max:2048',
            'instagram' => 'nullable|string|max:2048',
            'instagram_url' => 'nullable|string|max:2048',
            'website' => 'nullable|string|max:2048',
            'website_url' => 'nullable|string|max:2048',
            'youtube' => 'nullable|string|max:2048',
            'youtube_url' => 'nullable|string|max:2048',
            'tiktok' => 'nullable|string|max:2048',
            'tiktok_url' => 'nullable|string|max:2048',
            'sort_order' => 'nullable|integer',
            'is_verified' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'tier' => 'nullable|string|in:none,silver,gold,platinum,diamond',
        ];
    }

    protected function prepareForValidation()
    {
        $inputs = $this->all();
        foreach ($inputs as $key => $value) {
            if ($value === 'null' || $value === 'undefined' || $value === '') {
                $inputs[$key] = null;
            }
        }
        $this->replace($inputs);
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

        $address = [];
        if (!is_null($this->address_en)) {
            $address['en'] = $this->address_en;
        }
        if (!is_null($this->address_ar)) {
            $address['ar'] = $this->address_ar;
        }
        if (!empty($address)) {
            $data['address'] = $address;
        }

        if ($this->has('facebook')) {
            $data['facebook'] = $this->facebook;
        } elseif ($this->has('facebook_url')) {
            $data['facebook'] = $this->facebook_url;
        }

        if ($this->has('instagram')) {
            $data['instagram'] = $this->instagram;
        } elseif ($this->has('instagram_url')) {
            $data['instagram'] = $this->instagram_url;
        }

        if ($this->has('tiktok')) {
            $data['tiktok'] = $this->tiktok;
        } elseif ($this->has('tiktok_url')) {
            $data['tiktok'] = $this->tiktok_url;
        }

        if ($this->has('youtube')) {
            $data['youtube'] = $this->youtube;
        } elseif ($this->has('youtube_url')) {
            $data['youtube'] = $this->youtube_url;
        }

        if ($this->has('website')) {
            $data['website'] = $this->website;
        } elseif ($this->has('website_url')) {
            $data['website'] = $this->website_url;
        }

        $this->merge($data);
    }
}

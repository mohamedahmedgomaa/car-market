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
            'email' => 'nullable|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string',
            'store_name_ar' => 'required',
            'store_name_en' => 'required',
            'store_description_ar' => 'nullable',
            'store_description_en' => 'nullable',
            'store_logo' => 'nullable|image|max:2048',
            'cover_image' => 'nullable|image|max:2048',
            'business_license' => 'nullable|string',
            'bank_account' => 'nullable|string',
            'city_id' => 'nullable|exists:cities,id',
            'governorate_id' => 'nullable|exists:governorates,id',
            'address_en' => 'nullable|string|max:255',
            'address_ar' => 'nullable|string|max:255',
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
        $fb = $this->facebook ?? $this->facebook_url;
        $ig = $this->instagram ?? $this->instagram_url;
        $yt = $this->youtube ?? $this->youtube_url;
        $web = $this->website ?? $this->website_url;
        $tt = $this->tiktok ?? $this->tiktok_url;

        $this->merge([
            'password' => Hash::make($this->password),
            'store_name' => [
                'en' => $this->store_name_en,
                'ar' => $this->store_name_ar,
            ],
            'store_description' => [
                'en' => $this->store_description_en,
                'ar' => $this->store_description_ar,
            ],
            'address' => [
                'en' => $this->address_en,
                'ar' => $this->address_ar,
            ],
            'facebook' => $fb,
            'instagram' => $ig,
            'website' => $web,
            'youtube' => $yt,
            'tiktok' => $tt,
        ]);
    }
}

<?php

namespace App\Http\Modules\Cars\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class CreateCarRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'seller_id' => 'required|exists:sellers,id',
            'brand_id' => 'required|exists:brands,id',
            'model_id' => 'required|exists:models,id',

            'country_id' => 'required|exists:countries,id',
            'governorate_id' => 'required|exists:governorates,id',
            'city_id' => 'required|exists:cities,id',

            'type' => 'required|in:car,motorcycle',

            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',

            'description_ar' => 'required|string',
            'description_en' => 'required|string',

            'price' => 'required|numeric|min:0',
            'currency' => 'nullable|string|in:EGP,USD,SYP',
            'year' => 'required|digits:4|integer',
            'mileage' => 'required|integer|min:0',

            'transmission' => 'required|in:manual,automatic',
            'fuel_type' => 'required|in:petrol,diesel,electric,hybrid,mild_hybrid,reev',
            'drivetrain' => 'required|in:fwd,rwd,awd,4wd',

            'color' => 'nullable|string|max:50',
            'condition' => 'required|in:new,used',

            'horsepower' => 'nullable|string|max:50',
            'torque' => 'nullable|string|max:50',
            'engine_capacity' => 'nullable|string|max:50',
            'cylinders' => 'nullable|string|max:50',
            'acceleration' => 'nullable|string|max:50',
            'weight' => 'nullable|string|max:50',

            'phone_number' => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'youtube_url' => 'nullable|string|max:2048',
            'instagram_url' => 'nullable|string|max:2048',
            'model_3d_url' => 'nullable|string|max:2048',
            'model_3d_file' => 'nullable|file|max:30720',
            'remove_model_3d' => 'nullable|boolean',
            'exterior_360_images' => 'nullable',
            'exterior_360_files' => 'nullable|array',
            'exterior_360_files.*' => 'file|mimes:jpg,jpeg,png,webp|max:10240',
            'exterior_360_zip' => 'nullable|file|mimes:zip|max:61440',
            'remove_exterior_360' => 'nullable|boolean',
            'interior_360_url' => 'nullable|string|max:2048',
            'interior_360_image' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:20480',
            'remove_interior_360' => 'nullable|boolean',

            'is_best_deal' => 'nullable|boolean',
            'is_import' => 'nullable',
            'is_featured' => 'nullable|boolean',
            'show_on_home' => 'nullable|boolean',
            'is_global_ad' => 'nullable|boolean',
            'ad_expiry' => 'nullable|date',
            'featured_fee' => 'nullable|numeric|min:0',

            // Features
            'features' => 'nullable|array',
            'features.*' => 'exists:car_features,id',

            // Images
            'images' => 'nullable|array',
            'images.*' => 'file|mimes:jpg,jpeg,png,webp,heic,heif|max:20480',
            'main_image' => 'nullable|integer', // index
        ];
    }

    protected function prepareForValidation(): void
    {
        $inputs = $this->all();
        foreach ($inputs as $key => $value) {
            if ($value === 'null' || $value === 'undefined' || $value === '') {
                $inputs[$key] = null;
            }
        }
        $this->replace($inputs);
    }

    protected function passedValidation(): void
    {
        $isAdmin = auth('api_admin')->check();

        // Sellers cannot self-promote or self-approve their cars
        if (!$isAdmin) {
            $this->request->remove('is_featured');
            $this->request->remove('show_on_home');
            $this->request->remove('is_best_deal');
            $this->request->remove('is_global_ad');
            $this->request->remove('ad_expiry');
            $this->request->remove('featured_fee');
        }

        $this->merge([
            'status' => $isAdmin && $this->filled('status') ? $this->status : 'pending',
            'title' => [
                'ar' => $this->title_ar,
                'en' => $this->title_en,
            ],
            'description' => [
                'ar' => $this->description_ar,
                'en' => $this->description_en,
            ],
        ]);
    }
}

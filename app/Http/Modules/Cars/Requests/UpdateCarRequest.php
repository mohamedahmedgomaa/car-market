<?php

namespace App\Http\Modules\Cars\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class UpdateCarRequest extends BaseRequest
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
            'city_id' => 'required|exists:cities,id',

            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',

            'description_ar' => 'required|string',
            'description_en' => 'required|string',

            'price' => 'required|numeric|min:0',
            'year' => 'required|digits:4|integer',
            'mileage' => 'required|integer|min:0',

            'transmission' => ['required', Rule::in(['manual', 'automatic'])],
            'fuel_type' => ['required', Rule::in(['petrol', 'diesel', 'electric', 'hybrid'])],
            'drivetrain' => ['required', Rule::in(['fwd', 'rwd', 'awd', '4wd'])],

            // اللون هيجي من الفرونت كـ "#RRGGBB" (نخليه string)
            'color' => 'nullable|string|max:50',

            'condition' => ['required', Rule::in(['new', 'used'])],

            // Features
            'features' => 'nullable|array',
            'features.*' => 'exists:car_features,id',

            // Old images to keep
            'keep_images' => 'nullable|array',
            'keep_images.*' => 'integer|exists:car_images,id',

            // New images
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp', // |max:2048

            // Main image selection
            'main_image_id' => 'nullable|integer|exists:car_images,id',      // old image id
            'main_image_new_index' => 'nullable|integer|min:0',             // index in uploaded images[]
        ];
    }

    protected function passedValidation(): void
    {
        $this->merge([
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

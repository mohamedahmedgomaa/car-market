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
            'city_id' => 'required|exists:cities,id',

            'type' => 'required|in:car,motorcycle',

            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',

            'description_ar' => 'required|string',
            'description_en' => 'required|string',

            'price' => 'required|numeric|min:0',
            'year' => 'required|digits:4|integer',
            'mileage' => 'required|integer|min:0',

            'transmission' => 'required|in:manual,automatic',
            'fuel_type' => 'required|in:petrol,diesel,electric,hybrid',
            'drivetrain' => 'required|in:fwd,rwd,awd,4wd',

            'color' => 'nullable|string|max:50',
            'condition' => 'required|in:new,used',

            // 🔥 Features
            'features' => 'nullable|array',
            'features.*' => 'exists:car_features,id',

            // 🔥 Images
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp', // |max:2048
            'main_image' => 'nullable|integer', // index
        ];
    }

    protected function passedValidation(): void
    {
        $this->merge([
            'status' => 'pending',

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

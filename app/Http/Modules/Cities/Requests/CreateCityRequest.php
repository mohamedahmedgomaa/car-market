<?php

namespace App\Http\Modules\Cities\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class CreateCityRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'country_id' => 'required|exists:countries,id',
            'name_ar' => 'required',
            'name_en' => 'required',
        ];
    }
    public function passedValidation()
    {
        $this->merge([
            'name' => [
                'en' => $this->name_en,
                'ar' => $this->name_ar,
            ]
        ]);
    }
}

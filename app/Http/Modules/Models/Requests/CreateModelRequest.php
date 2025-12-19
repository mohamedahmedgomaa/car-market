<?php

namespace App\Http\Modules\Models\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class CreateModelRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'brand_id' => 'required|exists:brands,id',
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

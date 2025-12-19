<?php

namespace App\Http\Modules\Brands\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class CreateBrandRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
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

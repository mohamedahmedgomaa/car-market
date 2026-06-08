<?php

namespace App\Http\Modules\Governorates\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class CreateGovernorateRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'country_id' => 'required|exists:countries,id',
            'name_ar' => 'required|string',
            'name_en' => 'required|string',
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

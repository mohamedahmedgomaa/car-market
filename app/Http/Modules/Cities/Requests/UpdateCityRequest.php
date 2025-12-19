<?php

namespace App\Http\Modules\Cities\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class UpdateCityRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'country_id' => 'required|exists:countries,id',
            'name_ar' => 'nullable',
            'name_en' => 'nullable',
        ];
    }

    public function passedValidation()
    {
        $data = [];

        $storeName = [];
        if (!is_null($this->name_en)) {
            $storeName['en'] = $this->name_en;
        }
        if (!is_null($this->name_ar)) {
            $storeName['ar'] = $this->name_ar;
        }
        if (!empty($storeName)) {
            $data['name'] = $storeName;
        }

        $this->merge($data);
    }
}

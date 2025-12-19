<?php

namespace App\Http\Modules\Brands\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class UpdateBrandRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
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

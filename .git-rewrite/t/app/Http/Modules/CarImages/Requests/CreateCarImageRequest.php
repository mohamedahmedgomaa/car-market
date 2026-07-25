<?php

namespace App\Http\Modules\CarImages\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class CreateCarImageRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
                        'car_id' => 'required|integer',
            'path' => 'required|string',
            'is_main' => 'required|boolean',
        ];
    }
}

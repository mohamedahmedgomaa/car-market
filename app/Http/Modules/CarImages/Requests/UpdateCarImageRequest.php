<?php

namespace App\Http\Modules\CarImages\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class UpdateCarImageRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
                        'car_id' => 'nullable|integer',
            'path' => 'nullable|string',
            'is_main' => 'nullable|boolean',
        ];
    }
}

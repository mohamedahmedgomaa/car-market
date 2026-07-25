<?php

namespace App\Http\Modules\CarFeaturePivots\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class CreateCarFeaturePivotRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
                        'car_id' => 'required|integer',
            'car_feature_id' => 'required|integer',
        ];
    }
}

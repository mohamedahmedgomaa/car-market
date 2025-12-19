<?php

namespace App\Http\Modules\CarFeaturePivots\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class UpdateCarFeaturePivotRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
                        'car_id' => 'nullable|integer',
            'car_feature_id' => 'nullable|integer',
        ];
    }
}

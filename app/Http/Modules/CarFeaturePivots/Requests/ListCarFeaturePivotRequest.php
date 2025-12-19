<?php

namespace App\Http\Modules\CarFeaturePivots\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class ListCarFeaturePivotRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            
        ];
    }
}

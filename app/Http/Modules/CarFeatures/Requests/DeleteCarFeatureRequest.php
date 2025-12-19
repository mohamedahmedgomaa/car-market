<?php

namespace App\Http\Modules\CarFeatures\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class DeleteCarFeatureRequest extends BaseRequest
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

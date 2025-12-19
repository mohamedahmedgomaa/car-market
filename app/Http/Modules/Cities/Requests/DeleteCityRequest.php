<?php

namespace App\Http\Modules\Cities\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class DeleteCityRequest extends BaseRequest
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

<?php

namespace App\Http\Modules\Cities\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class ListCityRequest extends BaseRequest
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

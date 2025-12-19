<?php

namespace App\Http\Modules\Countries\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class ListCountryRequest extends BaseRequest
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

<?php

namespace App\Http\Modules\Countries\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class DeleteCountryRequest extends BaseRequest
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

<?php

namespace App\Http\Modules\Brands\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class ListBrandRequest extends BaseRequest
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

<?php

namespace App\Http\Modules\Sellers\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class ShowSellerRequest extends BaseRequest
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

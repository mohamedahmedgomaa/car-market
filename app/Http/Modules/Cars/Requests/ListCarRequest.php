<?php

namespace App\Http\Modules\Cars\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class ListCarRequest extends BaseRequest
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

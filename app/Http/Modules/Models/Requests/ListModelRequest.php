<?php

namespace App\Http\Modules\Models\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class ListModelRequest extends BaseRequest
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

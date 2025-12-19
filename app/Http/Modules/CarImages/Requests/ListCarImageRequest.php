<?php

namespace App\Http\Modules\CarImages\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class ListCarImageRequest extends BaseRequest
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

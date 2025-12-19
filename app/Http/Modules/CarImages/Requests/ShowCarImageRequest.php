<?php

namespace App\Http\Modules\CarImages\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class ShowCarImageRequest extends BaseRequest
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

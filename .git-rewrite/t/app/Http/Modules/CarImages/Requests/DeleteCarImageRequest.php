<?php

namespace App\Http\Modules\CarImages\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class DeleteCarImageRequest extends BaseRequest
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

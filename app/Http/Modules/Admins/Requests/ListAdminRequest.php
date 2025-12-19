<?php

namespace App\Http\Modules\Admins\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class ListAdminRequest extends BaseRequest
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

<?php

namespace App\Http\Modules\Users\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class DeleteUserRequest extends BaseRequest
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

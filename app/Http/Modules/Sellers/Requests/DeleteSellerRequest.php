<?php

namespace App\Http\Modules\Sellers\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class DeleteSellerRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return auth('api_admin')->check();
    }

    public function rules(): array
    {
        return [];
    }
}

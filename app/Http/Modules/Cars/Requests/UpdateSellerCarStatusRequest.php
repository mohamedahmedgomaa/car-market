<?php

namespace App\Http\Modules\Cars\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class UpdateSellerCarStatusRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return auth('api_seller')->check() || auth('api_admin')->check();
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:sold,archived,available',
            'sold_price' => 'nullable|numeric|min:0',
        ];
    }
}


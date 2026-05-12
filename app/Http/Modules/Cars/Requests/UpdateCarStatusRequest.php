<?php

namespace App\Http\Modules\Cars\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class UpdateCarStatusRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'sometimes|in:pending,approved,rejected',
            'is_featured' => 'sometimes|boolean',
            'is_best_deal' => 'sometimes|boolean',
            'is_import' => 'sometimes|boolean',
            'show_on_home' => 'sometimes|boolean',
            'is_global_ad' => 'sometimes|boolean',
            'ad_expiry' => 'sometimes|nullable|date',
            'featured_fee' => 'sometimes|nullable|numeric',
        ];
    }
}

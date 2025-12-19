<?php

namespace App\Http\Modules\FavoriteCars\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class UpdateFavoriteCarRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
                        'user_id' => 'nullable|integer',
            'car_id' => 'nullable|integer',
        ];
    }
}

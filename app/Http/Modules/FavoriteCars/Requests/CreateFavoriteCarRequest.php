<?php

namespace App\Http\Modules\FavoriteCars\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class CreateFavoriteCarRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
                        'user_id' => 'required|integer',
            'car_id' => 'required|integer',
        ];
    }
}

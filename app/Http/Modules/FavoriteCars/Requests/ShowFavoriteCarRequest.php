<?php

namespace App\Http\Modules\FavoriteCars\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class ShowFavoriteCarRequest extends BaseRequest
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

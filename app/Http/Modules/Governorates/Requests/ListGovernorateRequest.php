<?php

namespace App\Http\Modules\Governorates\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class ListGovernorateRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }
}

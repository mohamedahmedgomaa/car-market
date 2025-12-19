<?php

namespace App\Http\Modules\Notifications\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class DeleteNotificationRequest extends BaseRequest
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

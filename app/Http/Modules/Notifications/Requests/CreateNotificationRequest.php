<?php

namespace App\Http\Modules\Notifications\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class CreateNotificationRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
                        'user_id' => 'required|integer',
            'title' => 'required|json',
            'body' => 'required|json',
            'is_read' => 'required|boolean',
        ];
    }
}

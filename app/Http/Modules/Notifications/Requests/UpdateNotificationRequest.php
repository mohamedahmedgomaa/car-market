<?php

namespace App\Http\Modules\Notifications\Requests;

use Gomaa\Base\Base\Requests\BaseRequest;

class UpdateNotificationRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
                        'user_id' => 'nullable|integer',
            'title' => 'nullable|json',
            'body' => 'nullable|json',
            'is_read' => 'nullable|boolean',
        ];
    }
}

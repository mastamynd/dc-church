<?php

namespace Modules\PaymentSchedules\Http\Requests;

use App\Abstracts\Http\FormRequest;

class Approve extends FormRequest
{
    public function rules()
    {
        return [
            'comment' => 'nullable|string|max:1000',
        ];
    }
}

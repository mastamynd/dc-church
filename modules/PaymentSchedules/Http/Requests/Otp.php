<?php

namespace Modules\PaymentSchedules\Http\Requests;

use App\Abstracts\Http\FormRequest;

class Otp extends FormRequest
{
    public function rules()
    {
        return [
            'code' => 'required|string|size:6',
        ];
    }
}

<?php

namespace Modules\PaymentSchedules\Http\Requests;

use App\Abstracts\Http\FormRequest;
use Modules\PaymentSchedules\Utilities\UnpaidDistributions;

class Schedule extends FormRequest
{
    public function rules()
    {
        return [
            'demarcations' => 'required|array|min:1',
            'demarcations.*' => 'integer',
            'type' => 'nullable|in:' . implode(',', UnpaidDistributions::TYPES),
        ];
    }
}

<?php

namespace Modules\PaymentSchedules\Http\Requests;

use App\Abstracts\Http\FormRequest;
use Modules\PaymentSchedules\Models\BillingCycle;

class Cycle extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:191',
            'scheduled_date' => 'required|date',
            'notes' => 'nullable|string',
            'status' => 'nullable|in:' . BillingCycle::STATUS_OPEN . ',' . BillingCycle::STATUS_CLOSED,
        ];
    }
}

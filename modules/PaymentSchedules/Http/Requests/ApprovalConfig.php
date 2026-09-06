<?php

namespace Modules\PaymentSchedules\Http\Requests;

use App\Abstracts\Http\FormRequest;

class ApprovalConfig extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:191',
            'enabled' => 'nullable',
            'steps' => 'required|array|min:1',
            'steps.*.name' => 'required|string|max:191',
            'steps.*.approver_type' => 'required|in:user,role',
            'steps.*.approver_id' => 'required|integer',
        ];
    }
}

<?php

namespace Modules\PaymentSchedules\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Models\Auth\Role;
use Modules\PaymentSchedules\Http\Requests\ApprovalConfig as ConfigRequest;
use Modules\PaymentSchedules\Models\ApprovalConfig;
use Modules\PaymentSchedules\Models\ApprovalStep;

class Approvals extends Controller
{
    public function edit()
    {
        $config = ApprovalConfig::with('steps')->orderBy('id')->first();
        $users = company()->users()->get()->sortBy('name')->values();
        $roles = Role::orderBy('display_name')->get();

        if ($roles->isEmpty() && method_exists(Role::class, 'allCompanies')) {
            $roles = Role::allCompanies()->orderBy('display_name')->get();
        }

        $userOptions = $users->map(function ($user) {
            return ['id' => $user->id, 'name' => $user->name];
        })->values();

        $roleOptions = $roles->map(function ($role) {
            return ['id' => $role->id, 'name' => $role->display_name ?: $role->name];
        })->values();

        $existingSteps = $config
            ? $config->steps->map(function ($step) {
                return [
                    'name' => $step->name,
                    'approver_type' => $step->approver_type,
                    'approver_id' => $step->approver_id,
                ];
            })->values()
            : collect();

        return $this->response('payment-schedules::approvals.edit', compact(
            'config',
            'userOptions',
            'roleOptions',
            'existingSteps'
        ));
    }

    public function update(ConfigRequest $request)
    {
        $config = ApprovalConfig::orderBy('id')->first();

        $payload = [
            'company_id' => company_id(),
            'name' => $request->name,
            'applies_to' => 'both',
            'enabled' => $request->get('enabled', 1) ? 1 : 0,
            'created_from' => 'payment-schedules::ui',
            'created_by' => user_id(),
        ];

        if ($config) {
            $config->update($payload);
        } else {
            $config = ApprovalConfig::create($payload);
        }

        $config->steps()->delete();

        $steps = array_values($request->input('steps', []));
        $last = count($steps) - 1;

        foreach ($steps as $index => $step) {
            ApprovalStep::create([
                'company_id' => company_id(),
                'config_id' => $config->id,
                'level' => $index + 1,
                'name' => $step['name'],
                'approver_type' => $step['approver_type'],
                'approver_id' => $step['approver_id'],
                'is_final' => $index === $last,
            ]);
        }

        $message = trans('messages.success.updated', ['type' => trans('payment-schedules::general.approval_configuration')]);

        flash($message)->success();

        return redirect()->route('payment-schedules.approvals.edit');
    }
}

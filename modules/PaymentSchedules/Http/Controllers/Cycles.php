<?php

namespace Modules\PaymentSchedules\Http\Controllers;

use App\Abstracts\Http\Controller;
use Modules\PaymentSchedules\Http\Requests\Cycle as Request;
use Modules\PaymentSchedules\Models\BillingCycle;

class Cycles extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('permission:update-payment-schedules-cycles')->only('close');
    }

    public function index()
    {
        $cycles = BillingCycle::withCount('schedules')->collect('scheduled_date');

        return $this->response('payment-schedules::cycles.index', compact('cycles'));
    }

    public function create()
    {
        return $this->response('payment-schedules::cycles.create');
    }

    public function store(Request $request)
    {
        $cycle = BillingCycle::create([
            'company_id' => company_id(),
            'name' => $request->name,
            'scheduled_date' => $request->scheduled_date,
            'notes' => $request->notes,
            'status' => BillingCycle::STATUS_OPEN,
            'created_from' => 'payment-schedules::ui',
            'created_by' => user_id(),
        ]);

        $message = trans('messages.success.added', ['type' => trans_choice('payment-schedules::general.billing_cycle', 1)]);

        flash($message)->success();

        return redirect()->route('payment-schedules.cycles.show', $cycle->id);
    }

    public function show(BillingCycle $cycle)
    {
        $cycle->load(['schedules.items', 'creator']);

        return $this->response('payment-schedules::cycles.show', compact('cycle'));
    }

    public function edit(BillingCycle $cycle)
    {
        return $this->response('payment-schedules::cycles.edit', compact('cycle'));
    }

    public function update(BillingCycle $cycle, Request $request)
    {
        $cycle->update($request->only('name', 'scheduled_date', 'notes'));

        $message = trans('messages.success.updated', ['type' => $cycle->name]);

        flash($message)->success();

        return redirect()->route('payment-schedules.cycles.show', $cycle->id);
    }

    public function close(BillingCycle $cycle)
    {
        $cycle->update(['status' => BillingCycle::STATUS_CLOSED]);

        flash(trans('messages.success.updated', ['type' => $cycle->name]))->success();

        return redirect()->route('payment-schedules.cycles.show', $cycle->id);
    }

    public function destroy(BillingCycle $cycle)
    {
        if ($cycle->schedules()->exists()) {
            $message = trans('payment-schedules::general.cannot_delete_cycle');

            flash($message)->error()->important();

            return response()->json([
                'success' => false,
                'error' => true,
                'redirect' => route('payment-schedules.cycles.index'),
                'message' => $message,
            ]);
        }

        $cycle->delete();

        $message = trans('messages.success.deleted', ['type' => $cycle->name]);

        flash($message)->success();

        return response()->json([
            'success' => true,
            'error' => false,
            'redirect' => route('payment-schedules.cycles.index'),
            'data' => $cycle,
            'message' => $message,
        ]);
    }
}

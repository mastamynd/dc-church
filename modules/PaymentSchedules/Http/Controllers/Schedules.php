<?php

namespace Modules\PaymentSchedules\Http\Controllers;

use App\Abstracts\Http\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Church\Models\PaymentDivision;
use Modules\Demarcations\Models\Demarcation;
use Modules\PaymentSchedules\Http\Requests\Approve as ApproveRequest;
use Modules\PaymentSchedules\Http\Requests\Otp as OtpRequest;
use Modules\PaymentSchedules\Http\Requests\Schedule as ScheduleRequest;
use Modules\PaymentSchedules\Models\BillingCycle;
use Modules\PaymentSchedules\Models\PaymentSchedule;
use Modules\PaymentSchedules\Models\PaymentScheduleItem;
use Modules\PaymentSchedules\Utilities\ScheduleService;
use Modules\PaymentSchedules\Utilities\UnpaidDistributions;
use RuntimeException;

class Schedules extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('permission:update-payment-schedules-schedules')->only([
            'submit', 'approve', 'reject', 'cancel',
        ]);
        $this->middleware('permission:create-payment-schedules-schedules')->only([
            'sendOtp', 'verifyOtp',
        ]);
        $this->middleware('permission:read-payment-schedules-schedules')->only('item');
    }

    public function create(Request $request, BillingCycle $cycle, string $type)
    {
        abort_unless(in_array($type, UnpaidDistributions::TYPES, true), 404);
        abort_unless($cycle->isOpen(), 403, trans('payment-schedules::general.cycle_closed'));

        $regionId = $request->filled('region') ? (int) $request->region : null;
        $rows = UnpaidDistributions::groupedByDemarcation($type, $regionId);
        $regions = Demarcation::where('type', 'Region')->orderBy('name')->get();
        $pageTitle = $type === 'region'
            ? trans('payment-schedules::general.region_batch')
            : trans('payment-schedules::general.sub_region_batch');

        return $this->response('payment-schedules::schedules.create', compact(
            'cycle',
            'type',
            'rows',
            'regions',
            'pageTitle'
        ));
    }

    public function store(ScheduleRequest $request, BillingCycle $cycle, string $type)
    {
        abort_unless(in_array($type, UnpaidDistributions::TYPES, true), 404);

        try {
            $schedule = (new ScheduleService())->create($cycle, $type, $request->input('demarcations', []), user());
        } catch (RuntimeException $e) {
            flash($e->getMessage())->error()->important();

            return redirect()->back()->withInput();
        }

        flash(trans('messages.success.added', ['type' => trans_choice('payment-schedules::general.payment_schedule', 1)]))->success();

        return redirect()->route('payment-schedules.schedules.show', $schedule->id);
    }

    public function show(PaymentSchedule $schedule)
    {
        $schedule->load([
            'cycle',
            'items.demarcation',
            'approvals.actor',
            'approvals.user',
            'approvals.role',
            'transactions',
            'otps.user',
            'creator',
        ]);

        $currentApproval = $schedule->currentApproval();
        $canApprove = $currentApproval && user() && $currentApproval->userCanAct(user());
        $latestOtp = $schedule->otps->sortByDesc('id')->first();
        $canEnterOtp = $schedule->status === PaymentSchedule::STATUS_OTP_SENT
            && $latestOtp
            && (int) $latestOtp->user_id === (int) user_id();

        return $this->response('payment-schedules::schedules.show', compact(
            'schedule',
            'currentApproval',
            'canApprove',
            'latestOtp',
            'canEnterOtp'
        ));
    }

    public function item(PaymentSchedule $schedule, PaymentScheduleItem $item)
    {
        abort_unless((int) $item->payment_schedule_id === (int) $schedule->id, 404);

        $divisionIds = $item->divisions()->pluck('payment_divisions.id');
        $prefix = DB::getTablePrefix();

        $lines = collect();

        if ($divisionIds->isNotEmpty()) {
            $lines = PaymentDivision::query()
            ->leftJoin('churches as c', 'c.id', '=', 'payment_divisions.church_id')
            ->whereIn('payment_divisions.id', $divisionIds)
            ->selectRaw(
                $prefix . 'c.name as church_name, ' .
                $prefix . 'payment_divisions.month, ' .
                $prefix . 'payment_divisions.year, ' .
                'SUM(' . $prefix . 'payment_divisions.amount) as total, ' .
                'SUM(' . $prefix . 'payment_divisions.topup) as topup'
            )
            ->groupBy('payment_divisions.church_id', 'payment_divisions.month', 'payment_divisions.year')
            ->orderBy('c.name')
            ->get();
        }

        return $this->response('payment-schedules::schedules.item', compact('schedule', 'item', 'lines'));
    }

    public function submit(PaymentSchedule $schedule)
    {
        try {
            (new ScheduleService())->submit($schedule);
            flash(trans('messages.success.updated', ['type' => trans_choice('payment-schedules::general.payment_schedule', 1)]))->success();
        } catch (RuntimeException $e) {
            flash($e->getMessage())->error()->important();
        }

        return redirect()->route('payment-schedules.schedules.show', $schedule->id);
    }

    public function approve(ApproveRequest $request, PaymentSchedule $schedule)
    {
        try {
            (new ScheduleService())->approve($schedule, user(), $request->comment);
            flash(trans('payment-schedules::general.approve'))->success();
        } catch (RuntimeException $e) {
            flash($e->getMessage())->error()->important();
        }

        return redirect()->route('payment-schedules.schedules.show', $schedule->id);
    }

    public function reject(ApproveRequest $request, PaymentSchedule $schedule)
    {
        try {
            (new ScheduleService())->reject($schedule, user(), $request->comment);
            flash(trans('payment-schedules::general.reject'))->success();
        } catch (RuntimeException $e) {
            flash($e->getMessage())->error()->important();
        }

        return redirect()->route('payment-schedules.schedules.show', $schedule->id);
    }

    public function cancel(PaymentSchedule $schedule)
    {
        try {
            (new ScheduleService())->cancel($schedule);
            flash(trans('messages.success.deleted', ['type' => trans_choice('payment-schedules::general.payment_schedule', 1)]))->success();
        } catch (RuntimeException $e) {
            flash($e->getMessage())->error()->important();
        }

        return redirect()->route('payment-schedules.schedules.show', $schedule->id);
    }

    public function sendOtp(PaymentSchedule $schedule)
    {
        if (!in_array($schedule->status, [PaymentSchedule::STATUS_APPROVED, PaymentSchedule::STATUS_OTP_SENT], true)) {
            flash(trans('payment-schedules::general.cannot_edit_locked'))->error()->important();

            return redirect()->route('payment-schedules.schedules.show', $schedule->id);
        }

        try {
            $otp = (new ScheduleService())->issueOtp($schedule, user());
            $email = $otp->user->email ?? user()->email;
            flash(trans('payment-schedules::general.otp_sent', ['email' => $email]))->success();
        } catch (\Throwable $e) {
            report($e);
            flash($e->getMessage() ?: trans('payment-schedules::general.otp_invalid'))->error()->important();
        }

        return redirect()->route('payment-schedules.schedules.show', $schedule->id);
    }

    public function verifyOtp(OtpRequest $request, PaymentSchedule $schedule)
    {
        try {
            (new ScheduleService())->verifyOtp($schedule, user(), $request->code);
            flash(trans('payment-schedules::general.queued_for_mpesa'))->success();
        } catch (RuntimeException $e) {
            flash($e->getMessage())->error()->important();
        }

        return redirect()->route('payment-schedules.schedules.show', $schedule->id);
    }
}

<?php

namespace Modules\PaymentSchedules\Utilities;

use App\Models\Auth\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Church\Models\PaymentDivision;
use Modules\Demarcations\Models\Demarcation;
use Modules\PaymentSchedules\Jobs\DispatchMpesaPayouts;
use Modules\PaymentSchedules\Models\ApprovalConfig;
use Modules\PaymentSchedules\Models\BillingCycle;
use Modules\PaymentSchedules\Models\PaymentSchedule;
use Modules\PaymentSchedules\Models\PaymentScheduleItem;
use Modules\PaymentSchedules\Models\ScheduleApproval;
use Modules\PaymentSchedules\Models\ScheduleOtp;
use Modules\PaymentSchedules\Notifications\PaymentScheduleOtp;
use RuntimeException;

class ScheduleService
{
    public function create(BillingCycle $cycle, string $type, array $demarcationIds, User $user): PaymentSchedule
    {
        if (!$cycle->isOpen()) {
            throw new RuntimeException(trans('payment-schedules::general.cycle_closed'));
        }

        $demarcationIds = array_values(array_unique(array_filter(array_map('intval', $demarcationIds))));

        if ($demarcationIds === []) {
            throw new RuntimeException(trans('payment-schedules::general.select_at_least_one'));
        }

        return DB::transaction(function () use ($cycle, $type, $demarcationIds, $user) {
            $schedule = PaymentSchedule::create([
                'company_id' => company_id(),
                'billing_cycle_id' => $cycle->id,
                'type' => $type,
                'scheduled_date' => $cycle->scheduled_date,
                'status' => PaymentSchedule::STATUS_DRAFT,
                'total_amount' => 0,
                'created_from' => 'payment-schedules::ui',
                'created_by' => $user->id,
            ]);

            $total = 0;

            foreach ($demarcationIds as $demarcationId) {
                $demarcation = Demarcation::with('bank')->find($demarcationId);

                if (!$demarcation || !filled($demarcation->paybill) || !filled($demarcation->account_number)) {
                    continue;
                }

                $divisions = UnpaidDistributions::unpaidForDemarcation($type, $demarcationId)->lockForUpdate()->get();

                if ($divisions->isEmpty()) {
                    continue;
                }

                $amount = (float) $divisions->sum('amount');

                $item = PaymentScheduleItem::create([
                    'company_id' => company_id(),
                    'payment_schedule_id' => $schedule->id,
                    'demarcation_id' => $demarcation->id,
                    'payment_type' => $type,
                    'amount' => $amount,
                    'bank_id' => $demarcation->bank_id,
                    'bank_name' => $demarcation->bank->name ?? null,
                    'paybill' => $demarcation->paybill,
                    'account_number' => $demarcation->account_number,
                    'status' => PaymentScheduleItem::STATUS_PENDING,
                ]);

                try {
                    $item->divisions()->attach($divisions->pluck('id')->all());
                } catch (\Illuminate\Database\QueryException $e) {
                    $item->delete();
                    continue;
                }

                $total += $amount;
            }

            if ($total <= 0) {
                throw new RuntimeException(trans('payment-schedules::general.select_at_least_one'));
            }

            $schedule->update(['total_amount' => $total]);

            return $schedule->fresh(['items']);
        });
    }

    public function submit(PaymentSchedule $schedule): PaymentSchedule
    {
        if (!$schedule->isDraft()) {
            throw new RuntimeException(trans('payment-schedules::general.cannot_edit_locked'));
        }

        $config = ApprovalConfig::current();

        if (!$config || $config->steps->isEmpty()) {
            throw new RuntimeException(trans('payment-schedules::general.no_approval_config'));
        }

        return DB::transaction(function () use ($schedule, $config) {
            $steps = $config->steps->values();
            $lastIndex = $steps->count() - 1;

            foreach ($steps as $index => $step) {
                ScheduleApproval::create([
                    'company_id' => company_id(),
                    'payment_schedule_id' => $schedule->id,
                    'step_id' => $step->id,
                    'level' => $index + 1,
                    'name' => $step->name,
                    'approver_type' => $step->approver_type,
                    'approver_id' => $step->approver_id,
                    'is_final' => $index === $lastIndex,
                    'status' => ScheduleApproval::STATUS_PENDING,
                ]);
            }

            $schedule->update([
                'status' => PaymentSchedule::STATUS_PENDING_APPROVAL,
                'submitted_at' => now(),
            ]);

            return $schedule->fresh(['approvals']);
        });
    }

    public function approve(PaymentSchedule $schedule, User $user, ?string $comment = null): PaymentSchedule
    {
        $approval = $schedule->currentApproval();

        if (!$approval || !$approval->userCanAct($user)) {
            throw new RuntimeException(trans('payment-schedules::general.not_your_turn'));
        }

        return DB::transaction(function () use ($schedule, $approval, $user, $comment) {
            $approval->update([
                'status' => ScheduleApproval::STATUS_APPROVED,
                'approved_by' => $user->id,
                'approved_at' => now(),
                'comment' => $comment,
            ]);

            if ($approval->is_final) {
                $schedule->update(['status' => PaymentSchedule::STATUS_APPROVED]);
                $this->issueOtp($schedule->fresh(), $user);
            }

            return $schedule->fresh(['approvals', 'otps']);
        });
    }

    public function reject(PaymentSchedule $schedule, User $user, ?string $comment = null): PaymentSchedule
    {
        $approval = $schedule->currentApproval();

        if (!$approval || !$approval->userCanAct($user)) {
            throw new RuntimeException(trans('payment-schedules::general.not_your_turn'));
        }

        $approval->update([
            'status' => ScheduleApproval::STATUS_REJECTED,
            'approved_by' => $user->id,
            'approved_at' => now(),
            'comment' => $comment,
        ]);

        return $this->cancel($schedule);
    }

    public function cancel(PaymentSchedule $schedule): PaymentSchedule
    {
        if (!$schedule->isCancellable()) {
            throw new RuntimeException(trans('payment-schedules::general.cannot_edit_locked'));
        }

        return DB::transaction(function () use ($schedule) {
            foreach ($schedule->items as $item) {
                $item->divisions()->detach();
                $item->update(['status' => PaymentScheduleItem::STATUS_SKIPPED]);
            }

            $schedule->update(['status' => PaymentSchedule::STATUS_CANCELLED]);

            return $schedule->fresh();
        });
    }

    public function issueOtp(PaymentSchedule $schedule, User $user): ScheduleOtp
    {
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $otp = ScheduleOtp::create([
            'company_id' => company_id(),
            'payment_schedule_id' => $schedule->id,
            'user_id' => $user->id,
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes(ScheduleOtp::TTL_MINUTES),
            'attempts' => 0,
        ]);

        $schedule->update(['status' => PaymentSchedule::STATUS_OTP_SENT]);

        try {
            $user->notify(new PaymentScheduleOtp($code, $schedule->loadMissing('cycle')));
        } catch (\Throwable $e) {
            report($e);
        }

        return $otp;
    }

    public function verifyOtp(PaymentSchedule $schedule, User $user, string $code): PaymentSchedule
    {
        $otp = $schedule->otps()->where('user_id', $user->id)->latest('id')->first();

        if (!$otp || !$otp->isUsable()) {
            throw new RuntimeException(trans('payment-schedules::general.otp_invalid'));
        }

        if (!$otp->matches(trim($code))) {
            $otp->increment('attempts');

            if ($otp->attempts >= ScheduleOtp::MAX_ATTEMPTS) {
                throw new RuntimeException(trans('payment-schedules::general.otp_locked'));
            }

            throw new RuntimeException(trans('payment-schedules::general.otp_invalid'));
        }

        $otp->update(['used_at' => now()]);

        $schedule->update([
            'triggered_by' => $user->id,
            'triggered_at' => now(),
        ]);

        (new DispatchMpesaPayouts($schedule->id))->handle();

        return $schedule->fresh();
    }

    public function markDivisionsPaid(PaymentScheduleItem $item, ?string $receipt = null): void
    {
        PaymentDivision::whereIn('id', $item->divisions()->pluck('payment_divisions.id'))
            ->whereNull('payment_date')
            ->update([
                'payment_date' => now(),
                'mpesa_receipt' => $receipt,
            ]);
    }
}

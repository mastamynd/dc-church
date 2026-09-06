<?php

namespace Modules\PaymentSchedules\Jobs;

use Illuminate\Support\Str;
use Modules\PaymentSchedules\Models\MpesaTransaction;
use Modules\PaymentSchedules\Models\PaymentSchedule;
use Modules\PaymentSchedules\Models\PaymentScheduleItem;

class DispatchMpesaPayouts
{
    public function __construct(protected int $scheduleId)
    {
    }

    public function handle()
    {
        $schedule = PaymentSchedule::with(['items', 'cycle'])->find($this->scheduleId);

        if (!$schedule) {
            return;
        }

        foreach ($schedule->items as $item) {
            if ($item->status === PaymentScheduleItem::STATUS_SKIPPED) {
                continue;
            }

            $originator = (string) Str::uuid();

            $payload = [
                'InitiatorName' => setting('payment-schedules.initiator', ''),
                'SecurityCredential' => setting('payment-schedules.security_credential', ''),
                'CommandID' => 'BusinessPayBill',
                'SenderIdentifierType' => '4',
                'RecieverIdentifierType' => '4',
                'Amount' => (float) $item->amount,
                'PartyA' => setting('payment-schedules.shortcode', ''),
                'PartyB' => $item->paybill,
                'AccountReference' => $item->account_number,
                'Remarks' => 'Billing cycle ' . ($schedule->cycle->name ?? $schedule->id),
                'QueueTimeOutURL' => setting('payment-schedules.timeout_url', ''),
                'ResultURL' => setting('payment-schedules.result_url', ''),
                'Occasion' => 'schedule-' . $schedule->id . '-item-' . $item->id,
                'OriginatorConversationID' => $originator,
            ];

            MpesaTransaction::create([
                'company_id' => company_id(),
                'payment_schedule_id' => $schedule->id,
                'schedule_item_id' => $item->id,
                'originator_conversation_id' => $originator,
                'command_id' => 'BusinessPayBill',
                'party_a' => $payload['PartyA'],
                'party_b' => $item->paybill,
                'account_reference' => $item->account_number,
                'amount' => $item->amount,
                'request_payload' => $payload,
                'status' => MpesaTransaction::STATUS_PENDING,
                'result_desc' => 'Queued locally. M-Pesa API will be connected in a later phase.',
            ]);

            $item->update(['status' => PaymentScheduleItem::STATUS_QUEUED]);
        }

        $schedule->update(['status' => PaymentSchedule::STATUS_QUEUED]);
    }
}

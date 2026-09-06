<?php

namespace Modules\PaymentSchedules\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\PaymentSchedules\Models\PaymentSchedule;

class PaymentScheduleOtp extends Notification
{
    public function __construct(public string $code, public PaymentSchedule $schedule)
    {
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        if (function_exists('company_id') && company_id()) {
            app('url')->defaults(['company_id' => company_id()]);
        }

        $cycleName = $this->schedule->cycle->name ?? trans_choice('payment-schedules::general.payment_schedule', 1);

        return (new MailMessage)
            ->subject(trans('payment-schedules::general.verify_otp') . ' — ' . $cycleName)
            ->line(trans('payment-schedules::general.final_step_hint'))
            ->line(trans('payment-schedules::general.otp_code') . ': **' . $this->code . '**')
            ->line(trans('payment-schedules::general.total') . ': ' . number_format((float) $this->schedule->total_amount, 2))
            ->action(
                trans_choice('payment-schedules::general.payment_schedule', 1),
                route('payment-schedules.schedules.show', $this->schedule->id)
            )
            ->line(trans('auth.notification.message_2'));
    }
}

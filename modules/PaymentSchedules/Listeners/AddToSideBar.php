<?php

namespace Modules\PaymentSchedules\Listeners;

use App\Events\Menu\AdminCreated as Event;

class AddToSideBar
{
    public function handle(Event $event)
    {
        $user = user();

        if (!$user) {
            return;
        }

        $canCycles = $user->can('read-payment-schedules-cycles');
        $canApprovals = $user->can('read-payment-schedules-approvals');

        if (!$canCycles && !$canApprovals) {
            return;
        }

        $event->menu->dropdown(trans_choice('payment-schedules::general.name', 2), function ($sub) use ($canCycles, $canApprovals) {
            if ($canCycles) {
                $sub->route('payment-schedules.cycles.index', trans_choice('payment-schedules::general.billing_cycle', 2), [], 10, []);
            }

            if ($canApprovals) {
                $sub->route('payment-schedules.approvals.edit', trans('payment-schedules::general.approval_configuration'), [], 20, []);
            }
        }, 22, ['icon' => 'fas fa-mobile-alt']);
    }
}

<?php

namespace Modules\PaymentSchedules\Listeners;

use App\Events\Module\Installed as Event;
use App\Traits\Permissions;
use Modules\PaymentSchedules\Utilities\InstallModule;

class FinishInstallation
{
    use Permissions;

    public $alias = 'payment-schedules';

    public function handle(Event $event)
    {
        if ($event->alias != $this->alias) {
            return;
        }

        (new InstallModule())->ensure(true);
    }
}

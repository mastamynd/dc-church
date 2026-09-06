<?php

namespace Modules\Church\Listeners;

use App\Events\Module\Installed as Event;
use App\Traits\Permissions;
use Modules\Church\Utilities\InstallDashboard;

class FinishInstallation
{
    use Permissions;

    public $alias = 'church';

    /**
     * Handle the event.
     *
     * @param  Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        if ($event->alias != $this->alias) {
            return;
        }

        $this->updatePermissions();

        try {
            (new InstallDashboard())->ensure();
        } catch (\Throwable $e) {
            report($e);
        }
    }

    protected function updatePermissions()
    {
        $this->attachPermissionsToAdminRoles([
            $this->alias . '-main' => 'c,r,u,d',
        ]);

        $this->attachModuleWidgetPermissions($this->alias);
    }
}

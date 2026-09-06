<?php

namespace Modules\PaymentSchedules\Utilities;

use App\Models\Module\Module;
use App\Traits\Permissions;

class InstallModule
{
    use Permissions;

    public const VERSION = 1;

    public $alias = 'payment-schedules';

    protected static $ran = false;

    public function ensure(bool $force = false): void
    {
        if (!$force && self::$ran) {
            return;
        }

        self::$ran = true;

        if (!function_exists('module') || !module($this->alias)) {
            return;
        }

        $this->ensureEnabled();

        if (!$force && (int) setting('payment-schedules.installed_version') >= self::VERSION) {
            return;
        }

        $this->attachPermissionsToAdminRoles([
            $this->alias . '-cycles' => 'c,r,u,d',
            $this->alias . '-schedules' => 'c,r,u,d',
            $this->alias . '-approvals' => 'c,r,u,d',
        ]);

        setting(['payment-schedules.installed_version' => self::VERSION])->save();
    }

    protected function ensureEnabled(): void
    {
        if (!company_id()) {
            return;
        }

        Module::firstOrCreate(
            [
                'company_id' => company_id(),
                'alias' => $this->alias,
            ],
            [
                'enabled' => 1,
                'created_from' => 'payment-schedules::seed',
                'created_by' => user_id() ?: 1,
            ]
        );
    }
}

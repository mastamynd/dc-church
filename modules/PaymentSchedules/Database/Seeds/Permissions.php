<?php

namespace Modules\PaymentSchedules\Database\Seeds;

use App\Abstracts\Model;
use App\Traits\Permissions as Helper;
use Illuminate\Database\Seeder;

class Permissions extends Seeder
{
    use Helper;

    public $alias = 'payment-schedules';

    public function run()
    {
        Model::unguard();

        $this->attachPermissionsToAdminRoles([
            $this->alias . '-cycles' => 'c,r,u,d',
            $this->alias . '-schedules' => 'c,r,u,d',
            $this->alias . '-approvals' => 'c,r,u,d',
        ]);

        Model::reguard();
    }
}

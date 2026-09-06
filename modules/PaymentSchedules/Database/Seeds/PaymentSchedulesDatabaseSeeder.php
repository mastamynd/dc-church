<?php

namespace Modules\PaymentSchedules\Database\Seeds;

use App\Abstracts\Model;
use Illuminate\Database\Seeder;

class PaymentSchedulesDatabaseSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();

        $this->call(Permissions::class);

        Model::reguard();
    }
}

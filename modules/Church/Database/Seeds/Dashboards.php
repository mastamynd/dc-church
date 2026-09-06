<?php

namespace Modules\Church\Database\Seeds;

use App\Abstracts\Model;
use Illuminate\Database\Seeder;
use Modules\Church\Utilities\InstallDashboard;

class Dashboards extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        (new InstallDashboard())->ensure();

        Model::reguard();
    }
}

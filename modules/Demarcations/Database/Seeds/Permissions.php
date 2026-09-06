<?php

namespace Modules\Demarcations\Database\Seeds;

use App\Abstracts\Model;
use App\Traits\Permissions as Helper;
use Illuminate\Database\Seeder;

class Permissions extends Seeder
{
    use Helper;

    public $alias = 'demarcations';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->attachPermissionsToAdminRoles([
            $this->alias . '-main' => 'c,r,u,d',
            $this->alias . '-banks' => 'c,r,u,d',
        ]);

        $this->attachPermissionsByRoleNames([
            'manage-demarcations' => [
                $this->alias . '-banks' => 'c,r,u,d',
            ],
        ]);

        Model::reguard();
    }
}

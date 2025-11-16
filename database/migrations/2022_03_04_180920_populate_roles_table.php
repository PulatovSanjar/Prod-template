<?php
declare(strict_types=1);

use App\Models\Role;
use App\Models\Permission;
use App\Utilities\PermissionHelper;
use Illuminate\Database\Migrations\Migration;

class PopulateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $roles = [
            [
                'title' => 'Admin',
                'key'   => 'admin',

                'permissions' => array_merge(
                    PermissionHelper::make('dashboard'),
                    PermissionHelper::make('roles'),
                    PermissionHelper::make('users'),
                ),
            ],
            [
                'title' => 'Customer',
                'key'   => 'customer',

                'permissions' => [],
            ],
        ];

        foreach ($roles as $roleData) {

            /** @var Role $role */
            $role = Role::query()->create($roleData);

            foreach ($roleData['permissions'] as $permissionTitle) {
                $role->permissions()->attach(Permission::query()->where('title', $permissionTitle)->first()->id);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Role::query()
            ->whereIn('key', [
                'admin',
                'customer',
            ])->delete();
    }
}

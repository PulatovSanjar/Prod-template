<?php
declare(strict_types=1);

use App\Models\Permission;
use App\Utilities\PermissionHelper;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permissions = array_merge(
            PermissionHelper::make('dashboard'),
            PermissionHelper::make('roles'),
            PermissionHelper::make('users'),
        );

        foreach ($permissions as $permission) {
            Permission::query()->create(['title' => $permission]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $permissions = array_merge(
            PermissionHelper::make('dashboard'),
            PermissionHelper::make('roles'),
            PermissionHelper::make('users'),
        );

        Permission::query()
            ->whereIn('title', $permissions)
            ->delete();
    }
};

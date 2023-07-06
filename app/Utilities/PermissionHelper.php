<?php

namespace App\Utilities;

use App\Exceptions\RoleNotFound;
use App\Models\Permission;
use App\Models\Role;

class PermissionHelper
{
    /**
     * @var array|string[]
     */
    protected static array $default = [
        'access',
        'create',
        'edit',
        'delete'
    ];

    /**
     * @var string
     */
    protected static string $adminRole = 'admin';

    /**
     * @var string
     */
    protected static string $delimiter = '_';

    /**
     * @param string $title
     * @return array
     */
    public static function make(string $title): array
    {
        $permissions = [];

        foreach (self::$default as $item) {
            $permissions[] = $title . self::$delimiter . $item;
        }

        return $permissions;
    }

    /**
     * @param string $title
     * @return void
     * @throws RoleNotFound
     */
    public static function apply(string $title): void
    {
        $permissions = self::make($title);

        foreach ($permissions as $permission) {

            /** @var Permission $permission */
            $permission = Permission::query()->firstOrCreate(['title' => $permission]);
            self::assignToAdmin($permission);
        }
    }

    /**
     * @param Permission $permission
     * @return void
     * @throws RoleNotFound
     */
    public static function assignToAdmin(Permission $permission): void
    {
        /** @var Role $role */
        $role = Role::query()->where('key', self::$adminRole)->first();

        if (!$role) {
            throw new RoleNotFound('Admin role not found');
        }

        if (!$role->permissions()->find($permission)) {
            $role->permissions()->attach($permission);
        }
    }
}

<?php
declare(strict_types=1);

namespace App\Utilities;

use App\Models\Role;
use App\Models\Permission;
use App\Exceptions\RoleNotFound;

class PermissionHelper
{
    protected static array $default = [
        'access',
        'create',
        'edit',
        'delete',
    ];

    protected static string $adminRole = 'admin';
    protected static string $delimiter = '_';

    public static function make(string $title): array
    {
        $permissions = [];
        foreach (self::$default as $item) {
            $permissions[] = $title . self::$delimiter . $item;
        }

        return $permissions;
    }

    /**
     * Генерирует пермишены и назначает их админ-роли.
     *
     * @throws RoleNotFound
     */
    public static function apply(string $title): void
    {
        $permissionTitles = self::make($title);

        foreach ($permissionTitles as $permTitle) {
            $permModel = Permission::query()->firstOrCreate(['title' => $permTitle]);
            self::assignToAdmin($permModel);
        }
    }

    /**
     * Назначает один пермишен админ-роли (без дублей).
     *
     * @throws RoleNotFound
     */
    public static function assignToAdmin(Permission $permission): void
    {
        $role = Role::query()->where('key', self::$adminRole)->first();

        if ($role === null) {
            throw new RoleNotFound('Admin role not found');
        }

        // Без предварительных find/if: безопасно добавит, если ещё нет.
        $role->permissions()->syncWithoutDetaching([$permission->getKey()]);
    }
}

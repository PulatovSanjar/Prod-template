<?php
declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use App\Utilities\PermissionHelper;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Создаём роли (как было в миграции)
        $admin = Role::query()->firstOrCreate(
            ['key' => 'admin'],
            ['title' => 'Admin']
        );

        Role::query()->firstOrCreate(
            ['key' => 'customer'],
            ['title' => 'Customer']
        );

        // Навешиваем на admin нужные пермишены (как было в миграции)
        $adminPermissionTitles = array_merge(
            PermissionHelper::make('dashboard'),
            PermissionHelper::make('roles'),
            PermissionHelper::make('users'),
        );

        $permIds = Permission::query()
            ->whereIn('title', $adminPermissionTitles)
            ->pluck('id')
            ->all();

        // Без дублей
        $admin->permissions()->syncWithoutDetaching($permIds);
    }
}

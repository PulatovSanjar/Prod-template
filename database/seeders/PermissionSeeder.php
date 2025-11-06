<?php
declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use App\Utilities\PermissionHelper;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = array_merge(
            PermissionHelper::make('dashboard'),
            PermissionHelper::make('roles'),
            PermissionHelper::make('users'),
        );

        // Повторно безопасно: создаём только отсутствующие
        foreach ($permissions as $title) {
            Permission::query()->firstOrCreate(['title' => $title]);
        }
    }
}

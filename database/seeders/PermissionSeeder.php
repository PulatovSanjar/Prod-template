<?php
declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use App\Exceptions\RoleNotFound;
use App\Utilities\PermissionHelper;

class PermissionSeeder extends Seeder
{
    /**
     * @throws RoleNotFound
     */
    public function run(): void
    {
        $permissions = array_merge(
            PermissionHelper::make('dashboard'),
            PermissionHelper::make('roles'),
            PermissionHelper::make('users'),
            PermissionHelper::make('translators'),
            PermissionHelper::make('variables'),
    );

        // Повторно безопасно: создаём только отсутствующие
        foreach ($permissions as $title) {
            Permission::query()->firstOrCreate(['title' => $title]);
        }
    }
}

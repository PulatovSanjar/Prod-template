<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class, // сначала права
            RoleSeeder::class,       // потом роли + привязка прав
            AdminUserSeeder::class,  // потом админ и привязка роли
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name'              => 'Admin',
                'email_verified_at' => now(),
                'password'          => Hash::make('admin'),
            ]
        );

        if ($roleId = Role::query()->where('key', 'admin')->value('id')) {
            $user->roles()->syncWithoutDetaching([$roleId]);
        }
    }
}

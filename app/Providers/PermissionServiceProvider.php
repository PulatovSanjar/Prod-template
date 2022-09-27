<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (isDatabaseAvailable()) {
            $this->registerPermissions();
        }
    }

    private function registerPermissions()
    {
        $permissions = Permission::query()->get();

        foreach ($permissions as $permission) {

            Gate::define($permission->title, function (User $user) use ($permission) {
                return $user->roles()->withWhereHas('permissions', fn ($query) => $query->where('title', $permission->title))->exists();
            });

        }
    }
}

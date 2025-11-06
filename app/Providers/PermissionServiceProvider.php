<?php
declare(strict_types=1);

namespace App\Providers;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class PermissionServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (isDatabaseAvailable('permissions')) {
            $this->registerPermissions();
        }
    }

    private function registerPermissions(): void
    {
        $permissions = Permission::query()->get();

        foreach ($permissions as $permission) {
            $callback = new class($permission) {
                public function __construct(private Permission $permission) {}

                public function __invoke(User $user): bool
                {
                    return $user->roles()
                        ->withWhereHas(
                            'permissions',
                            fn ($q) => $q->where('title', $this->permission->title)
                        )
                        ->exists();
                }
            };

            Gate::define($permission->title, [$callback, '__invoke']);
        }
    }
}

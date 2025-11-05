<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use App\Exceptions\ModuleNotFoundException;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\Traits\HasJsonResponseTrait;
use App\Http\Controllers\Traits\HasNotificationTrait;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests,
        HasJsonResponseTrait, HasNotificationTrait;

    /**
     * @param string $view
     * @param array $withData
     * @return Factory|View|Application
     * @throws ModuleNotFoundException
     */
    protected function view(string $view, array $withData = []): Factory|View|Application
    {

        if (!property_exists($this, 'module')) {
            throw new ModuleNotFoundException('The $module property not found in child controller');
        }

        return view($view, array_merge([
            'module' => $this->module,
        ], $withData));
    }

    /**
     * @param string $guard
     * @return Authenticatable|User|null
     */
    protected function user(string $guard = 'api'): null|Authenticatable|User
    {
        return auth($guard)->user();
    }
}

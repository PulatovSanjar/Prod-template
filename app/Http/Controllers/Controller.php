<?php

namespace App\Http\Controllers;

use App\Exceptions\ModuleNotFoundException;
use App\Http\Controllers\Traits\HasJsonResponseTrait;
use App\Http\Controllers\Traits\HasNotificationTrait;
use App\Http\Controllers\Traits\HasPermissionTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests,
        HasJsonResponseTrait, HasNotificationTrait, HasPermissionTrait;

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
            'module' => $this->module
        ], $withData));
    }
}

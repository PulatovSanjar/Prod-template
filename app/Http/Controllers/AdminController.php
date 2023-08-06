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

class AdminController extends Controller
{
    use HasNotificationTrait, HasPermissionTrait;

    protected const ACTION_INDEX = 0;
    protected const ACTION_CREATE = 1;
    protected const ACTION_EDIT = 2;

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

    /**
     * @param int $action
     * @return string
     */
    protected function getActionView(int $action): string
    {
        $views = [
            'admin.views.' . $this->module . '.index',
            'admin.views.' . $this->module . '.store',
            'admin.views.' . $this->module . '.update',
        ];

        return $views[$action];
    }

    /**
     * @return Factory|View|Application
     * @throws ModuleNotFoundException
     */
    public function index(): Factory|View|Application
    {
        return $this->view($this->getActionView(self::ACTION_INDEX));
    }
}

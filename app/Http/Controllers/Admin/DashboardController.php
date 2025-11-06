<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use App\Http\Controllers\AdminController;
use Illuminate\Contracts\Foundation\Application;

Class DashboardController extends AdminController
{
    /**
     * @var string
     */
    protected string $module = 'dashboard';

    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        /**
         * @phpstan-ignore-next-line
         */
        return view('admin.views.' . $this->module . '.index');
    }
}

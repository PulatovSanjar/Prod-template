<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

Class DashboardController extends Controller
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
        return view('admin.views.' . $this->module . '.index');
    }
}

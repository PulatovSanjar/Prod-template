<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ModuleNotFoundException;
use App\Http\Controllers\AdminController;
use App\Models\Variable;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VariableController extends AdminController
{
    protected string $module = 'variables';

    /**
     * @return Factory|View|Application
     * @throws ModuleNotFoundException
     */
    public function index(): Factory|View|Application
    {
        return $this->view('admin.views.' . $this->module . '.index', [
            'variables' => Variable::query()->get()
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $inputs = $request->except(['_token'])['key'] ?? [];

        foreach ($inputs as $key => $data) {

            Variable::query()->updateOrCreate(['key' => $key], $data);

        }

        $this->notify()->addUpdated($this->module);

        return redirect()->route('admin.' . $this->module . '.index');
    }
}

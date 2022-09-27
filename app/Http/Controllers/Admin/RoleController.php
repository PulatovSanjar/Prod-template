<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ModuleNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Roles\CreateRoleRequest;
use App\Http\Requests\Admin\Roles\UpdateRoleRequest;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class RoleController extends Controller
{
    /**
     * @var string
     */
    public string $module = 'roles';

    /**
     * @return Factory|View|Application
     * @throws ModuleNotFoundException
     */
    public function index(): Factory|View|Application
    {
        return $this->view('admin.views.' . $this->module . '.index');
    }

    /**
     * @return Factory|View|Application
     * @throws ModuleNotFoundException
     */
    public function create(): Factory|View|Application
    {
        $permissions = Permission::query()->pluck('title', 'id');

        return $this->view('admin.views.' . $this->module . '.store', [
            'permissions' => $permissions
        ]);
    }

    /**
     * @param CreateRoleRequest $request
     * @return RedirectResponse
     */
    public function store(CreateRoleRequest $request): RedirectResponse
    {
        $inputs = $request->validated();

        /** @var Role $role */
        $role = Role::query()->create($inputs);

        $role->permissions()->sync($inputs['permissions'] ?? []);

        $this->notify()->addCreated($this->module);

        return redirect()->route('admin.' . $this->module . '.index');
    }

    /**
     * @param Role $role
     * @return Factory|View|Application
     * @throws ModuleNotFoundException
     */
    public function edit(Role $role): Factory|View|Application
    {
        $permissions = Permission::query()->pluck('title', 'id');

        return $this->view('admin.views.' . $this->module . '.update', [
            'permissions' => $permissions,
            'model'       => $role
        ]);
    }

    /**
     * @param UpdateRoleRequest $request
     * @param Role $role
     * @return RedirectResponse
     */
    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $inputs = $request->validated();

        $role->update($inputs);

        $role->permissions()->sync($inputs['permissions'] ?? []);

        $this->notify()->addUpdated($this->module);

        return redirect()->route('admin.' . $this->module . '.index');
    }

    /**
     * @param Role $role
     * @return RedirectResponse
     */
    public function destroy(Role $role): RedirectResponse
    {
        $role->delete();

        $this->notify()->addDeleted($this->module);

        return redirect()->route('admin.' . $this->module . '.index');
    }
}

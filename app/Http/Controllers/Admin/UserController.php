<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use App\Http\Controllers\AdminController;
use App\Exceptions\ModuleNotFoundException;
use Illuminate\Contracts\Foundation\Application;
use App\Http\Requests\Admin\Users\CreateUserRequest;
use App\Http\Requests\Admin\Users\UpdateUserRequest;

class UserController extends AdminController
{
    protected string $module = 'users';

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
        $roles = Role::query()->pluck('title', 'id');

        return $this->view('admin.views.' . $this->module . '.store', [
            'roles' => $roles,
        ]);
    }

    /**
     * @param CreateUserRequest $request
     * @return RedirectResponse
     */
    public function store(CreateUserRequest $request): RedirectResponse
    {
        $inputs = $request->validated();

        $inputs['password'] = Hash::make($inputs['password']);

        /** @var User $user */
        $user = User::query()->create($inputs);

        $user->roles()->sync($inputs['roles']);

        $this->notify()->addCreated($this->module);

        return redirect()->route('admin.' . $this->module . '.index');
    }

    /**
     * @param User $user
     * @return Factory|View|Application
     * @throws ModuleNotFoundException
     */
    public function edit(User $user): Factory|View|Application
    {
        $roles = Role::query()->pluck('title', 'id');

        return $this->view('admin.views.' . $this->module . '.update', [
            'roles' => $roles,
            'model' => $user,
        ]);
    }

    /**
     * @param UpdateUserRequest $request
     * @param User $user
     * @return RedirectResponse
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $inputs = $request->validated();

        $inputs['email_verified_at'] = $inputs['email_verified_at'] ? now() : NULL;

        $user->update($inputs);

        $user->roles()->sync($inputs['roles']);

        $this->notify()->addUpdated($this->module);

        return redirect()->route('admin.' . $this->module . '.index');
    }

    /**
     * @param User $user
     * @return RedirectResponse
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        $this->notify()->addDeleted($this->module);

        return redirect()->route('admin.' . $this->module . '.index');
    }
}

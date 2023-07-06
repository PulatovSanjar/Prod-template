<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

class AuthController extends AdminController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * @return Factory|View|Application
     */
    public function showLoginForm(): Factory|View|Application
    {
        return view('admin.auth.login');
    }

    public function login(LoginRequest $request): Redirector|Application|RedirectResponse
    {
        if(auth('web')->attempt($request->validated())) {
            if (auth('web')->user()->roles()->where('key', 'admin')->count() > 0) {

                return redirect('/admin');
            } else {

                auth('web')->logout();

            }
        }

        $errors = new MessageBag(['email' => ['These credentials do not match our records.']]);

        return back()->withErrors($errors);
    }

    public function logout(): Redirector|Application|RedirectResponse
    {
        Auth::logout();

        return redirect('/admin/login');
    }
}

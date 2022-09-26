<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {

    Route::middleware(['auth:web'])->group(function () {

        Route::get('/', [DashboardController::class, 'index'])->name('home');

        Route::resource('roles', RoleController::class);

        Route::resource('users', UserController::class);

    });

    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');

    Route::post('login', [AuthController::class, 'login']);

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');


});

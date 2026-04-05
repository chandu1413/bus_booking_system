<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\Operator\AuthenticatedOperatorSessionController;
use App\Http\Controllers\Auth\Operator\RegisteredOperatorController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OperatorController;


Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
Route::post('/reset-password', [NewPasswordController::class, 'update'])->name('password.update');

Route::get('operator/login', [AuthenticatedOperatorSessionController::class, 'create'])->name('operator.login');
Route::post('operator/login', [AuthenticatedOperatorSessionController::class, 'store'])->name('operator.store.login');
Route::post('operator/logout', [AuthenticatedOperatorSessionController::class, 'destroy'])->name('operator.logout');
Route::get('operator/register', [RegisteredOperatorController::class, 'create'])->name('operator.register');
Route::post('operator/register', [RegisteredOperatorController::class, 'store'])->name('operator.store.register');

Route::middleware('auth')->group(function () {

    // Route::middleware(['role:Admin|super_admin|Operator', 'is_operator_login_first_time'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // });

    Route::prefix('admin')->middleware(['role:Admin|SuperAdmin'])->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('admin.users.index');
            Route::get('/create', [UserController::class, 'create'])->name('admin.users.create');
            Route::post('/', [UserController::class, 'store'])->name('admin.users.store');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('admin.users.update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
        });

        Route::prefix('roles')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->name('admin.roles.index');
            Route::get('/create', [RoleController::class, 'create'])->name('admin.roles.create');
            Route::post('/', [RoleController::class, 'store'])->name('admin.roles.store');
            Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('admin.roles.edit');
            Route::put('/{role}', [RoleController::class, 'update'])->name('admin.roles.update');
            Route::delete('/{role}', [RoleController::class, 'destroy'])->name('admin.roles.destroy');
        });

        Route::prefix('operators')->group(function () {
            Route::get('/', [OperatorController::class, 'index'])->name('admin.operators.index');
            Route::get('create', [OperatorController::class, 'create'])->name('admin.operators.create');
            Route::post('create', [OperatorController::class, 'store'])->name('admin.operators.store');
             Route::get('edit/{operator}', [OperatorController::class, 'edit'])->name('admin.operators.edit');
        });

        Route::get('reports', [ReportController::class, 'index'])->name('admin.reports.index');
    });

    Route::prefix('operator')->middleware(['role:Operator|Admin|SuperAdmin'])->group(function () {

        Route::get('profile-message/{operator}', [OperatorController::class, 'profileMessage'])->name('operator.profile.message');
        Route::put('profile/{operator}', [OperatorController::class, 'updateProfile'])->name('operator.update.profile');
       
        Route::middleware(['is_operator_login_first_time', 'check.operator'])->group(function () {
            Route::get('/dashboard', [OperatorController::class, 'dashboard'])->name('operator.dashboard');
            Route::get('profile/{operator}', [OperatorController::class, 'profile'])->name('operator.profile');

            Route::prefix('buses')->group(function () {
                Route::get('/', [BusController::class, 'index'])->name('operator.buses.index');
                Route::get('/create', [BusController::class, 'create'])->name('operator.buses.create');
                Route::post('/', [BusController::class, 'store'])->name('operator.buses.store');
                Route::get('/{bus}/edit', [BusController::class, 'edit'])->name('operator.buses.edit');
                Route::put('/{bus}', [BusController::class, 'update'])->name('operator.buses.update');
                Route::delete('/{bus}', [BusController::class, 'destroy'])->name('operator.buses.destroy');
            });


        });
    });

    Route::prefix('customer')->middleware(['role:user'])->group(function () {});
});

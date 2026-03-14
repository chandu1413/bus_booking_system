<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectMemberController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\SubtaskController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ReportController;

// Root redirect
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Auth routes (Breeze)
require __DIR__.'/auth.php';

// Authenticated routes
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Projects
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
Route::patch('/projects/{project}/restore', [ProjectController::class, 'restore'])->name('projects.restore');

// Project Members
Route::get('/projects/{project}/members', [ProjectMemberController::class, 'index'])->name('projects.members.index');
Route::post('/projects/{project}/members', [ProjectMemberController::class, 'store'])->name('projects.members.store');
Route::delete('/projects/{project}/members/{user}', [ProjectMemberController::class, 'destroy'])->name('projects.members.destroy');

// Tasks
Route::get('/projects/{project}/tasks', [TaskController::class, 'index'])->name('projects.tasks.index');
Route::get('/projects/{project}/tasks/create', [TaskController::class, 'create'])->name('projects.tasks.create');
Route::post('/projects/{project}/tasks', [TaskController::class, 'store'])->name('projects.tasks.store');
Route::get('/projects/{project}/tasks/{task}', [TaskController::class, 'show'])->name('projects.tasks.show');
Route::get('/projects/{project}/tasks/{task}/edit', [TaskController::class, 'edit'])->name('projects.tasks.edit');
Route::put('/projects/{project}/tasks/{task}', [TaskController::class, 'update'])->name('projects.tasks.update');
Route::delete('/projects/{project}/tasks/{task}', [TaskController::class, 'destroy'])->name('projects.tasks.destroy');
Route::patch('/projects/{project}/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('projects.tasks.status');

// Subtasks
Route::post('/tasks/{task}/subtasks', [SubtaskController::class, 'store'])->name('subtasks.store');
Route::patch('/tasks/{task}/subtasks/{subtask}', [SubtaskController::class, 'update'])->name('subtasks.update');
Route::delete('/tasks/{task}/subtasks/{subtask}', [SubtaskController::class, 'destroy'])->name('subtasks.destroy');

// Comments
Route::post('/tasks/{task}/comments', [CommentController::class, 'store'])->name('comments.store');
Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

// Attachments
Route::post('/tasks/{task}/attachments', [AttachmentController::class, 'store'])->name('attachments.store');
Route::get('/attachments/{attachment}/download', [AttachmentController::class, 'download'])->name('attachments.download');
Route::delete('/attachments/{attachment}', [AttachmentController::class, 'destroy'])->name('attachments.destroy');

// Activity Logs
Route::get('/activity', [ActivityLogController::class, 'index'])->name('activity.index');

// Admin Panel
Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
Route::get('/admin/roles', [RoleController::class, 'index'])->name('admin.roles.index');
Route::get('/admin/roles/create', [RoleController::class, 'create'])->name('admin.roles.create');
Route::post('/admin/roles', [RoleController::class, 'store'])->name('admin.roles.store');
Route::get('/admin/roles/{role}/edit', [RoleController::class, 'edit'])->name('admin.roles.edit');
Route::put('/admin/roles/{role}', [RoleController::class, 'update'])->name('admin.roles.update');
Route::delete('/admin/roles/{role}', [RoleController::class, 'destroy'])->name('admin.roles.destroy');
Route::get('/admin/reports', [ReportController::class, 'index'])->name('admin.reports.index');
<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Project;
use App\Models\Task;
use App\Models\Comment;
use App\Policies\ProjectPolicy;
use App\Policies\TaskPolicy;
use App\Policies\CommentPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Project::class => ProjectPolicy::class,
        Task::class    => TaskPolicy::class,
        Comment::class => CommentPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
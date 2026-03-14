<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\TaskAssigned;
use App\Events\TaskStatusChanged;
use App\Listeners\SendTaskAssignedNotification;
use App\Listeners\SendTaskStatusChangedNotification;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        TaskAssigned::class => [
            SendTaskAssignedNotification::class,
        ],
        TaskStatusChanged::class => [
            SendTaskStatusChangedNotification::class,
        ],
    ];

    public function boot(): void {}
}
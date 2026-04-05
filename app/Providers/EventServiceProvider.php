<?php

namespace App\Providers;

use App\Events\OperatorRegistered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\ProjectCreated;
use App\Events\TaskAssigned;
use App\Events\TaskStatusChanged;
use App\Listeners\SendOperatorWelcomeEmail;
use App\Listeners\SendProjectCreatedNotification;
use App\Listeners\SendTaskAssignedNotification;
use App\Listeners\SendTaskStatusChangedNotification;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ProjectCreated::class => [
            SendProjectCreatedNotification::class,
        ],
        TaskAssigned::class => [
            SendTaskAssignedNotification::class,
        ],
        TaskStatusChanged::class => [
            SendTaskStatusChangedNotification::class,
        ],

        OperatorRegistered::class => [
            SendOperatorWelcomeEmail::class,
        ]
    ];

    public function boot(): void {}
}
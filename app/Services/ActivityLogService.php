<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ActivityLogService
{
    public function log(User $user, Model $loggable, string $action, string $description, array $properties = []): void
    {
        ActivityLog::create([
            'user_id'       => $user->id,
            'loggable_id'   => $loggable->getKey(),
            'loggable_type' => get_class($loggable),
            'action'        => $action,
            'description'   => $description,
            'properties'    => $properties,
            'created_at'    => now(),
        ]);
    }
}
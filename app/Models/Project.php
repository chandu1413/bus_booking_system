<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_ACTIVE    = 'Active';
    const STATUS_ON_HOLD   = 'OnHold';
    const STATUS_COMPLETED = 'Completed';

    protected $fillable = [
        'name',
        'description',
        'status',
        'owner_id',
        'start_date',
        'end_date',
        'color',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function projectMembers()
    {
        return $this->hasMany(ProjectMember::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'loggable');
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_ACTIVE    => 'green',
            self::STATUS_ON_HOLD   => 'yellow',
            self::STATUS_COMPLETED => 'blue',
            default                => 'gray',
        };
    }

    public function getCompletionPercentageAttribute(): int
    {
        $total = $this->tasks()->count();
        if ($total === 0) return 0;
        $done = $this->tasks()->where('status', Task::STATUS_DONE)->count();
        return (int) round(($done / $total) * 100);
    }

    public function scopeForUser($query, User $user)
    {
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return $query;
        }
        return $query->where('owner_id', $user->id)
            ->orWhereHas('members', fn($q) => $q->where('users.id', $user->id));
    }
}
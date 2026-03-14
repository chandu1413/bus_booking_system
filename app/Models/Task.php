<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_TODO        = 'todo';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_IN_REVIEW   = 'in_review';
    const STATUS_DONE        = 'done';

    const PRIORITY_LOW    = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH   = 'high';
    const PRIORITY_URGENT = 'urgent';

    protected $fillable = [
        'project_id',
        'assignee_id',
        'created_by',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'estimated_hours',
        'order',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function subtasks()
    {
        return $this->hasMany(Subtask::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'loggable');
    }

    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            self::PRIORITY_LOW    => 'gray',
            self::PRIORITY_MEDIUM => 'blue',
            self::PRIORITY_HIGH   => 'orange',
            self::PRIORITY_URGENT => 'red',
            default               => 'gray',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_TODO        => 'To Do',
            self::STATUS_IN_PROGRESS => 'In Progress',
            self::STATUS_IN_REVIEW   => 'In Review',
            self::STATUS_DONE        => 'Done',
            default                  => $this->status,
        };
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date && $this->due_date->isPast() && $this->status !== self::STATUS_DONE;
    }

    public function scopeForUser($query, User $user)
    {
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return $query;
        }
        return $query->whereHas('project', fn($q) => $q->forUser($user));
    }
}
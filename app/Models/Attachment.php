<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    protected $fillable = ['task_id', 'user_id', 'file_name', 'file_path', 'file_type', 'file_size', 'disk'];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes < 1024) return $bytes . ' B';
        if ($bytes < 1048576) return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1048576, 1) . ' MB';
    }

    public function getIconAttribute(): string
    {
        $type = $this->file_type;
        if (str_contains($type, 'image')) return '🖼️';
        if (str_contains($type, 'pdf'))   return '📄';
        if (str_contains($type, 'zip') || str_contains($type, 'rar')) return '🗜️';
        if (str_contains($type, 'word') || str_contains($type, 'document')) return '📝';
        if (str_contains($type, 'sheet') || str_contains($type, 'excel')) return '📊';
        return '📎';
    }
}
<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Task $task, public string $oldStatus) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Task Status Updated: ' . $this->task->title)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A task status has been updated.')
            ->line('**Task:** ' . $this->task->title)
            ->line('**Status:** ' . ucwords(str_replace('_', ' ', $this->oldStatus)) . ' → ' . $this->task->status_label)
            ->action('View Task', route('projects.tasks.show', [$this->task->project_id, $this->task->id]))
            ->line('ProjectFlow — Stay on top of your work.');
    }

    public function toArray($notifiable): array
    {
        return [
            'task_id'    => $this->task->id,
            'task_title' => $this->task->title,
            'old_status' => $this->oldStatus,
            'new_status' => $this->task->status,
            'message'    => 'Task status changed: ' . $this->task->title,
        ];
    }
}
<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Task $task) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Task Assigned: ' . $this->task->title)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You have been assigned a new task.')
            ->line('**Task:** ' . $this->task->title)
            ->line('**Project:** ' . $this->task->project->name)
            ->line('**Priority:** ' . ucfirst($this->task->priority))
            ->line('**Due Date:** ' . ($this->task->due_date ? $this->task->due_date->format('M d, Y') : 'Not set'))
            ->action('View Task', route('projects.tasks.show', [$this->task->project_id, $this->task->id]))
            ->line('Thank you for using ProjectFlow!');
    }

    public function toArray($notifiable): array
    {
        return [
            'task_id'      => $this->task->id,
            'task_title'   => $this->task->title,
            'project_id'   => $this->task->project_id,
            'project_name' => $this->task->project->name,
            'message'      => 'You were assigned to: ' . $this->task->title,
        ];
    }
}
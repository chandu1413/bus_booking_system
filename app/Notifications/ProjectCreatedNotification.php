<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProjectCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Project $project) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Project Created: ' . $this->project->name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new project has been created.')
            ->line('**Project:** ' . $this->project->name)
            ->line('**Owner:** ' . $this->project->owner->name)
            ->line('**Status:** ' . $this->project->status)
            ->when($this->project->description, function ($mail) {
                return $mail->line('**Description:** ' . $this->project->description);
            })
            ->when($this->project->start_date, function ($mail) {
                return $mail->line('**Start Date:** ' . $this->project->start_date->format('M d, Y'));
            })
            ->when($this->project->end_date, function ($mail) {
                return $mail->line('**End Date:** ' . $this->project->end_date->format('M d, Y'));
            })
            ->action('View Project', route('projects.show', $this->project))
            ->line('Thank you for using ProjectFlow!');
    }

    public function toArray($notifiable): array
    {
        return [
            'project_id'     => $this->project->id,
            'project_name'   => $this->project->name,
            'owner_id'       => $this->project->owner_id,
            'owner_name'     => $this->project->owner->name,
            'status'         => $this->project->status,
            'message'        => 'New project created: ' . $this->project->name,
        ];
    }
}

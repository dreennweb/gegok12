<?php

namespace App\Notifications;

use App\Models\DepartmentApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ApplicationQueriedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected DepartmentApplication $departmentApplication)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Action Required: Application Query from ' . $this->departmentApplication->department->name)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your registration application has a query from ' . $this->departmentApplication->department->name . '.')
            ->line('Query Details:')
            ->line($this->departmentApplication->query_notes)
            ->action('View Application', route('applications.show', $this->departmentApplication->application_id))
            ->line('Please respond with the required information as soon as possible.');
    }
}

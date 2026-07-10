<?php

namespace App\Notifications;

use App\Models\DepartmentApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ApplicationApprovedNotification extends Notification implements ShouldQueue
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
            ->subject('Registration Approved: ' . $this->departmentApplication->department->name)
            ->greeting('Congratulations ' . $notifiable->name . '!')
            ->line('Your registration with ' . $this->departmentApplication->department->name . ' has been approved.')
            ->line('Certificate Number: ' . $this->departmentApplication->certificate_number)
            ->action('View Certificate', route('certificates.show', $this->departmentApplication->id))
            ->line('Thank you for registering with Balochistan Business Registration Portal.');
    }
}

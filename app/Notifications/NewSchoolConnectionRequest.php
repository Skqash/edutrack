<?php

namespace App\Notifications;

use App\Models\SchoolRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewSchoolConnectionRequest extends Notification
{
    use Queueable;

    public SchoolRequest $schoolRequest;

    public function __construct(SchoolRequest $schoolRequest)
    {
        $this->schoolRequest = $schoolRequest;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $user = $this->schoolRequest->user;
        $url = route('admin.school-requests.show', $this->schoolRequest->id);

        return (new MailMessage)
            ->subject('New School Connection Request')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("A new school connection request has been submitted by {$user->name} ({$user->email}).")
            ->line('Requested Institution: ' . $this->schoolRequest->school_name)
            ->line('Submitted on: ' . $this->schoolRequest->created_at->format('F j, Y g:i A'))
            ->action('View Request', $url)
            ->line('Thank you for keeping the system up to date.');
    }
}

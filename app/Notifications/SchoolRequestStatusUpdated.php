<?php

namespace App\Notifications;

use App\Models\SchoolRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SchoolRequestStatusUpdated extends Notification
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
        $url = route('teacher.school-requests.history');
        $status = ucfirst($this->schoolRequest->status);

        return (new MailMessage)
            ->subject("Your school connection request has been {$status}")
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your request to connect to:')
            ->line('**' . $this->schoolRequest->school_name . '**')
            ->line('Status: **' . $status . '**')
            ->when($this->schoolRequest->admin_note, function ($mail) {
                $mail->line('Administrator note:')
                    ->line($this->schoolRequest->admin_note);
            })
            ->action('View request history', $url)
            ->line('Thank you for using EduTrack.');
    }
}

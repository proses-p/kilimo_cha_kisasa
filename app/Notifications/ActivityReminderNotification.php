<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActivityReminderNotification extends Notification
{
    use Queueable;

    public $activity;

    /**
     * Create a new notification instance.
     */
    public function __construct($activity)
    {
        $this->activity = $activity;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable) : array
    {
        return [
            'title' => 'Ukumbusho wa Shughuli',
            'message' => "Shughuli yako ya '{$this->activity->activity_type}' imepangwa kufanyika tarehe {$this->activity->scheduled_date->format('Y-m-d')}",
            'activity_id' => $this->activity->id,
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

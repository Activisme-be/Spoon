<?php

namespace App\Domain\Announcements\Notifications;

use App\Domain\Announcements\Models\SystemAlert as Alert;
use App\Domain\Auth\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Class SystemAlert
 *
 * @package App\Notifications
 */
class SystemAlert extends Notification implements ShouldQueue
{
    use Queueable;

    public Alert $notificationData;
    public User $creator;

    public function __construct(Alert $notificationData, User $creator)
    {
        $this->creator = $creator;
        $this->notificationData = $notificationData;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(): array
    {
        return [$this->notificationData->driver];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(): MailMessage
    {
        return (new MailMessage())
            ->subject('Systeem notificatie van '.config('app.name'))
            ->markdown('notifications.kiosk.mail', ['data' => $this->notificationData, 'user' => $this->creator]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(): array
    {
        return [
            'sender' => $this->creator,
            'title' => $this->notificationData->title,
            'message' => $this->notificationData->message,
            'action_url' => $this->notificationData->action_url,
            'action_text' => $this->notificationData->action_title,
        ];
    }
}

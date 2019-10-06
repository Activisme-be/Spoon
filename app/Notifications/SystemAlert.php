<?php

namespace App\Notifications;

use App\Models\SystemAlert as Alert;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Class SystemAlert.
 */
class SystemAlert extends Notification implements ShouldQueue
{
    use Queueable;

    /** @var Alert $notificationData */
    public $notificationData;

    /** @var User $user */
    public $creator;

    /**
     * Create a new notification instance.
     *
     * @param  Alert $notificationData
     * @param  User  $creator
     * @return void
     */
    public function __construct(Alert $notificationData, User $creator)
    {
        $this->creator = $creator;
        $this->notificationData = $notificationData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via(): array
    {
        return [$this->notificationData->driver];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject('Systeem notificatie van '.config('app.name'))
            ->markdown('notifications.kiosk.mail', ['data' => $this->notificationData, 'user' => $this->creator]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'sender'        => $this->creator,
            'title'         => $this->notificationData->title,
            'message'       => $this->notificationData->message,
            'action_url'    => $this->notificationData->action_url,
            'action_text'   => $this->notificationData->action_title,
        ];
    }
}

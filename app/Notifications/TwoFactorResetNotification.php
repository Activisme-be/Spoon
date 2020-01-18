<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

/**
 * Class TwoFactorResetNotification
 *
 * @package App\Notifications
 */
class TwoFactorResetNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via(): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  User $notifiable The entity from the user where the notification will be send to.
     * @return MailMessage
     */
    public function toMail(User $notifiable): MailMessage
    {
        $resetUrl = URL::temporarySignedRoute('2fa.reset', now()->addHour(), ['user' => $notifiable]);

        return (new MailMessage())
            ->subject('Aanvraag voor het resetten van je 2FA.')
            ->markdown('email.reset-2fa', ['user' => $notifiable, 'resetRoute' => $resetUrl]);
    }
}

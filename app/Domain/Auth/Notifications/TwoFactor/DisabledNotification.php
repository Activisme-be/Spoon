<?php

namespace App\Domain\Auth\Notifications\TwoFactor;

use App\Domain\Auth\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Class DisabledNotification
 *
 * @package App\Notifications\Users\TwoFactor
 */
class DisabledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(): array
    {
        return ['mail', 'database'];
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage())
            ->subject('2FA is gedeactiveerd op uw account')
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->markdown('email.disable-2fa');
    }

    public function toArray(User $notifiable): array
    {
        return [
            'sender' => $notifiable,
            'title' => '2fa is gedeactiveerd',
            'message' => '2fa is met success gedeactiveerd op jouw account.',
        ];
    }
}

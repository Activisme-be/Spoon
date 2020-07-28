<?php

namespace App\Domain\Auth\Notifications\TwoFactor;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Class EnabledNotification
 *
 * @package App\Notifications\Users\TwoFactor
 */
class EnabledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public array $twoFactorTokens;

    public function __construct(array $twoFactorTokens)
    {
        $this->twoFactorTokens = $twoFactorTokens;
    }

    public function via()
    {
        return ['mail'];
    }

    public function toMail(User $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('2FA authenticatie is geactiveerd op uw account')
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->markdown('email.enable-2fa', ['user' => $notifiable, 'tokens' => $this->twoFactorTokens]);
    }
}

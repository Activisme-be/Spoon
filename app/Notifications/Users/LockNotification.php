<?php

namespace App\Notifications\Users;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Class LockNotification
 *
 * @package App\Notifications\Users
 */
class LockNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $user;

    public function __construct(string $user)
    {
        // $this->>user stands for the user who set the active to non-active
        // in the application.
        $this->user = $user;
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        $appName = config('app.name');

        return (new MailMessage)
            ->subject('Uw account is tijdelijk gedeactiveerd')
            ->greeting('Geachte,')
            ->line("Via deze weg willen we je laten weten dat je account tijdelijk gedeactiveerd is op {$appName}")
            ->line('Indien je denkt dat dit een misverstand is kan je contact opnemen met ' . $this->user);
    }
}

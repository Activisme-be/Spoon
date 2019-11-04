<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Class LoginCreated
 *
 * @package App\Notifications
 */
class LoginCreated extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The variable that holds all the form inputs.
     *
     * @var array
     */
    public $input;

    /**
     * Create a new notification instance.
     *
     * @param  array $input The variable that holds the form request inputs.
     * @return void
     */
    public function __construct(array $input)
    {
        $this->input = $input;
    }

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
     * @return MailMessage
     */
    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject('There is created an login for u on '.config('app.name'))
            ->greeting('Hello,')
            ->line('A administrator has created an login for u on '.config('app.name'))
            ->line('You can login with the following password: '.$this->input['password'])
            ->action('login', route('login'));
    }
}

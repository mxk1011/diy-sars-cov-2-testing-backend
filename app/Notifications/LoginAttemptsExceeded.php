<?php

namespace App\Notifications;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Notifications\Dispatcher;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Events\AttemptsExceededEvent;
use App\Model\User;

/**
 * Class LoginAttemptsExceeded
 * @package App\Notifications
 */
class LoginAttemptsExceeded extends Notification implements ShouldQueue
{
    use Queueable;

    public $maxAttempts;

    /**
     * @param AttemptsExceededEvent $attemptsExceededEvent
     */
    public function handle(AttemptsExceededEvent $attemptsExceededEvent)
    {
        $this->maxAttempts = $attemptsExceededEvent->getMaxAttempts();
        $user = User::whereEmail($attemptsExceededEvent->getRequestData()['email'])
            ->first();

        if (isset($user)) {
            \Log::info(sprintf('Sending login attempts exceeded mail to %s', $user->email));
            app(Dispatcher::class)->send($user, $this);
        }
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject(trans('nice-auth::email.login.subject'))
            ->markdown(
                'nice-auth::emails.login-attempts-exceeded',
                [
                    'user' => $notifiable,
                    'maxAttempts' => $this->maxAttempts,
                ]
            );
    }
}

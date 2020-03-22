<?php

namespace App\Notifications;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Notifications\Dispatcher;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use App\Events\UserLoggedInEvent;
use App\Model\User;

/**
 * Class LoggedIn
 * @package App\Notifications
 */
class LoggedIn extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;

    /**
     * Usage of intern laravel login event is not possible since
     * auth guard will also be called in middleware to throttle login
     *
     * @param UserLoggedInEvent $event
     */
    public function handle(UserLoggedInEvent $event)
    {
        $this->user = $event->getUser();

        Log::info('User logged in');

        if (config('nice-auth.login.sendEmail')) {
            Log::debug(sprintf('Sending logged in email to %s', $event->getUser()));
            app(Dispatcher::class)->send($event->getUser(), $this);
        }
    }

    /**
     * @param User $notifiable
     * @return array
     */
    public function via(User $notifiable)
    {
        return ['mail'];
    }

    /**
     * @param User $notifiable
     */
    public function toMail(User $notifiable)
    {
        return (new MailMessage())
            ->subject(trans('nice-auth::email.loggedIn.subject'))
            ->markdown(
                'nice-auth::emails.loggedIn',
                [
                    'user' => $notifiable,
                    'ipAddress' => request()->ip(),
                    'userAgent' => request()->userAgent(),
                ]
            );
    }
}

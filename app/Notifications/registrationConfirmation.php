<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class registrationConfirmation extends Notification
{
    use Queueable;

    /**
     * The email validation token.
     *
     * @var string
     */
    public $token, $email, $user;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($array)
    {
        $this->user = $array['user'];
        $this->email=$array['email'];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via()
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail()
    {

        return (new MailMessage)
            ->success()
            ->to($this->email)
            ->subject('Registration')
            ->line('Dear'.$this->user->name.',')
            ->line('You have created an account on our website: we are happy to see you here.')
//            ->line('To validate your registration, please click on the button below :')
            ->action('Go to site', url('/'));
    }
}

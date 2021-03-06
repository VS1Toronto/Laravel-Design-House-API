<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Auth\Notifications\ResetPassword as Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends Notification
{
    //  This class extends the Notification class which gives 
    //  access to its methods and the ability to override them
    //
    public function toMail($notifiable)
    {
        $url = url(config('app.client_url').'/password/reset/'.$this->token).
                    '?email='.urlencode($notifiable->email);

        return (new MailMessage)
                    ->line('You are receiving this email because we received a password reset request for your account.')
                    ->action('Notification Action', $url)
                    ->line('If you did not request a password reset, no further action is required.');
    }

}

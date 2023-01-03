<?php

namespace App\Notifications;

use App\Mail\otpEmail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class optlNotification extends Notification
{
    use Queueable;
    protected $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * @param User $notifiable
     * @return otpEmail
     */
    public function toMail(User $notifiable)
    {
        new MailMessage;
        return (new otpEmail($this->code))
            ->subject('verify code')
            ->to($notifiable->email);
    }
}

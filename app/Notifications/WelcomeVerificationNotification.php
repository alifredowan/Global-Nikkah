<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class WelcomeVerificationNotification extends Notification
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Welcome to Global Nikkah - Verify Your Email Address')
            ->greeting("Assalamu Alaikum, {$notifiable->name}!")
            ->line('Alhamdulillah and welcome to **Global Nikkah**, the ethical, Shariah-grounded matrimonial platform designed for sincere Muslim seekers and their families.')
            ->line('Please confirm your email address to verify your account and begin your blessed journey toward Nikah.')
            ->action('Verify Email & Activate Profile', $verificationUrl)
            ->line('This verification link will remain valid for 48 hours.')
            ->line('At Global Nikkah, we honor Islamic modesty (Haya), guardian (Wali) supervision, and strict photo privacy controls to protect you every step of the way.')
            ->salutation('Warm regards, The Global Nikkah Team');
    }

    /**
     * Get the verification URL for the given notifiable.
     */
    protected function verificationUrl(object $notifiable): string
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 2880)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}

<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\WaliLink;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WaliConnectionRequestNotification extends Notification
{
    use Queueable;

    public function __construct(
        public WaliLink $waliLink,
        public User $seeker
    ) {}

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
        $waliName = $this->waliLink->wali_name ?: 'Respected Guardian';
        $relationship = ucfirst(str_replace('_', ' ', $this->waliLink->relationship_type));
        $wardGender = $this->seeker->gender === 'female' ? 'sister/daughter' : 'brother/son';

        $actionUrl = route('wali.invite.show', $this->waliLink->invite_token);

        return (new MailMessage)
            ->subject("Guardian (Wali) Connection Request from {$this->seeker->name} - Global Nikkah")
            ->greeting("Assalamu Alaikum, {$waliName}!")
            ->line("Your {$wardGender}, **{$this->seeker->name}**, has designated you as their **Guardian (Wali - {$relationship})** on Global Nikkah.")
            ->line('As their Wali, you will have the religious honor and responsibility to:')
            ->line('• Supervise and approve all suitor interest requests before discussions commence.')
            ->line('• Be present in chaperoned conversations to safeguard modesty and Islamic etiquette.')
            ->line('• Help review family matrimonial biodatas and ensure genuine compatibility.')
            ->action('Review & Approve Guardian Connection', $actionUrl)
            ->line('If this request was made by mistake or you are not able to act as guardian, you may decline the request via the link above.')
            ->salutation('Warm regards, The Global Nikkah Team');
    }
}

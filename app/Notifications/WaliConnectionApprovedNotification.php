<?php

namespace App\Notifications;

use App\Models\WaliLink;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WaliConnectionApprovedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public WaliLink $waliLink
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $waliName = $this->waliLink->wali_name ?: 'Your Guardian';
        $relationship = ucfirst(str_replace('_', ' ', $this->waliLink->relationship_type));

        return (new MailMessage)
            ->subject('Alhamdulillah! Guardian (Wali) Connection Approved - Global Nikkah')
            ->greeting("Assalamu Alaikum, {$notifiable->name}!")
            ->line("Alhamdulillah! Your {$relationship}, **{$waliName}**, has officially approved your Guardian (Wali) connection request on Global Nikkah.")
            ->line('Your account is now fully protected under Shariah guardian supervision:')
            ->line('• Inbound and outbound matrimonial interests will be chaperoned with their knowledge.')
            ->line('• Your guardian can review candidate profiles and support you in family consultations.')
            ->action('View Guardian Settings', route('wali.link'))
            ->line('May Allah (SWT) grant barakah in your journey and guide you to a pious, loving spouse.')
            ->salutation('Warm regards, The Global Nikkah Team');
    }

    /**
     * Get array representation for database notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $waliName = $this->waliLink->wali_name ?: 'Your Guardian';

        return [
            'type' => 'wali_connection_approved',
            'title' => '🛡️ Guardian (Wali) Connection Active',
            'message' => "{$waliName} has approved your Guardian connection request. Your profile is now under active Wali supervision.",
            'wali_link_id' => $this->waliLink->id,
            'icon' => '🛡️',
            'action_url' => route('wali.link'),
        ];
    }
}

<?php

namespace App\Notifications;

use App\Models\Marriage;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NikahMilestoneNotification extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    public function __construct(
        public Marriage $marriage,
        public string $event = 'initiated' // 'initiated', 'confirmed', 'declined'
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        if (! $notifiable->allowsNotification('marriage_notifications')) {
            return [];
        }

        return $notifiable->allowsNotification('email_notifications')
            ? ['database', 'broadcast', 'mail']
            : ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $initiator = $this->marriage->initiator;
        $url = route('marriages.celebration');

        if ($this->event === 'confirmed') {
            return (new MailMessage)
                ->subject('Nikah Mubarak! Your Marriage Milestone is Confirmed - Global Nikkah')
                ->greeting("Assalamu Alaikum, {$notifiable->name}!")
                ->line('Alhamdulillah wa Shukrulillah! Your Nikah milestone has been officially confirmed on Global Nikkah.')
                ->line('May Allah (SWT) bless your marriage, shower His barakah upon you both, and unite you in goodness and piety.')
                ->action('View Celebration & Marriage Certificate', $url)
                ->line('Your Single Marriage Pass has successfully concluded. Jazakum Allahu Khairan for honoring us to be a part of your journey.')
                ->salutation('With warmest duas, The Global Nikkah Team');
        }

        return (new MailMessage)
            ->subject('Nikah Milestone Declared - Action Required on Global Nikkah')
            ->greeting("Assalamu Alaikum, {$notifiable->name}!")
            ->line("**{$initiator->name}** has declared that your Nikah (marriage) has been completed!")
            ->line('Please review and confirm this blessed milestone to finalize your matrimonial journey.')
            ->action('Review & Confirm Nikah', $url)
            ->line('Upon mutual confirmation, all third-party suitors and ongoing discovery requests will be gracefully concluded.')
            ->salutation('Warm regards, The Global Nikkah Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $initiator = $this->marriage->initiator;

        if ($this->event === 'initiated') {
            return [
                'type' => 'nikah_milestone_initiated',
                'title' => '💍 Nikah Milestone Declared',
                'message' => "{$initiator->name} has declared that you have completed your Nikah! Please review and confirm this milestone.",
                'marriage_id' => $this->marriage->id,
                'icon' => '💍',
                'url' => route('marriages.celebration'),
            ];
        }

        if ($this->event === 'confirmed') {
            return [
                'type' => 'nikah_milestone_confirmed',
                'title' => '🎉 Nikah Mubarak!',
                'message' => 'Alhamdulillah! Your Nikah milestone is confirmed. May Allah bless your union with peace and barakah.',
                'marriage_id' => $this->marriage->id,
                'icon' => '🎉',
                'url' => route('marriages.celebration'),
            ];
        }

        return [
            'type' => 'nikah_milestone_declined',
            'title' => 'Nikah Declaration Status',
            'message' => 'The Nikah milestone confirmation request was declined.',
            'marriage_id' => $this->marriage->id,
            'icon' => 'ℹ️',
            'url' => route('chat.index'),
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'id' => $this->id,
            'type' => 'nikah_milestone',
            'created_at' => now()->diffForHumans(),
            'read_at' => null,
            'data' => $this->toArray($notifiable),
        ]);
    }

    /**
     * Broadcast channel for the recipient.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('App.Models.User.'.$this->marriage->groom_id),
            new PrivateChannel('App.Models.User.'.$this->marriage->bride_id),
        ];
    }
}

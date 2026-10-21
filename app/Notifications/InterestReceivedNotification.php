<?php

namespace App\Notifications;

use App\Models\Interest;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InterestReceivedNotification extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    public int $targetUserId;

    public function __construct(
        public Interest $interest,
        public bool $isWali = false,
        public ?User $forSeeker = null,
        public bool $isSenderWali = false,
        ?int $targetUserId = null
    ) {
        $this->targetUserId = $targetUserId ?? $this->interest->recipient_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        if (! $notifiable->allowsNotification('interest_notifications')) {
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
        $sender = $this->interest->sender;
        $url = route('interests.index');

        if ($this->isWali) {
            $seekerName = $this->forSeeker?->name ?: 'your ward';

            return (new MailMessage)
                ->subject("New Matrimonial Interest for {$seekerName} - Global Nikkah")
                ->greeting("Assalamu Alaikum, {$notifiable->name}!")
                ->line("A candidate suitor, **{$sender->name}**, has sent an expression of interest to your {$seekerName}.")
                ->line('As their designated Wali, you can review this proposal, inspect religious compatibility, and authorize chaperoned communication.')
                ->action('Review Proposal on Wali Console', route('wali.dashboard'))
                ->line('Global Nikkah ensures full modesty and guardian supervision at every stage.')
                ->salutation('Warm regards, The Global Nikkah Team');
        }

        return (new MailMessage)
            ->subject("New Halal Expression of Interest from {$sender->name} - Global Nikkah")
            ->greeting("Assalamu Alaikum, {$notifiable->name}!")
            ->line("**{$sender->name}** has expressed serious matrimonial interest in your profile on Global Nikkah.")
            ->line('You can review their biodata, religious practice, and family background.')
            ->action('View Interest Request', $url)
            ->line('Your privacy and blurred photo controls remain strictly protected until mutual acceptance.')
            ->salutation('Warm regards, The Global Nikkah Team');
    }

    /**
     * Data stored in database.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $sender = $this->interest->sender;
        $recipient = $this->interest->recipient;

        if ($this->isSenderWali) {
            return [
                'type' => 'wali_interest_sent',
                'title' => 'Dependent Expressed Interest',
                'message' => "Your dependent {$sender->name} sent an interest request to {$recipient->name}.",
                'interest_id' => $this->interest->id,
                'sender_id' => $sender->id,
                'recipient_id' => $recipient->id,
                'action_url' => route('wali.dashboard'),
                'icon' => '🛡️',
            ];
        }

        if ($this->isWali) {
            return [
                'type' => 'wali_interest_received',
                'title' => 'New Interest for Dependent',
                'message' => "A new interest request has been sent to your dependent {$recipient->name} by {$sender->name}.",
                'interest_id' => $this->interest->id,
                'sender_id' => $sender->id,
                'recipient_id' => $recipient->id,
                'action_url' => route('wali.dashboard'),
                'icon' => '🛡️',
            ];
        }

        return [
            'type' => 'interest_received',
            'title' => 'New Interest Request Received',
            'message' => "{$sender->name} has sent you an expression of interest!",
            'note' => $this->interest->message_note,
            'interest_id' => $this->interest->id,
            'sender_id' => $sender->id,
            'sender_name' => $sender->name,
            'action_url' => route('discovery.show', $sender->id),
            'icon' => '💌',
        ];
    }

    /**
     * Real-time Broadcast payload via Laravel Reverb.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        $data = $this->toArray($notifiable);
        $data['id'] = $this->id;
        $data['created_at'] = now()->diffForHumans();

        return new BroadcastMessage($data);
    }

    /**
     * The channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('App.Models.User.'.$this->targetUserId),
            new PrivateChannel('user.'.$this->targetUserId),
        ];
    }
}

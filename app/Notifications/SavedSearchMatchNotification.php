<?php

namespace App\Notifications;

use App\Models\SavedSearch;
use App\Models\User;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class SavedSearchMatchNotification extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    public function __construct(
        public SavedSearch $savedSearch,
        public User $candidate,
        public int $targetUserId
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return $notifiable->allowsNotification('saved_search_notifications')
            ? ['database', 'broadcast']
            : [];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'saved_search_match',
            'title' => 'A new profile matches your saved search',
            'message' => "{$this->candidate->name} matches your saved search, {$this->savedSearch->title}.",
            'candidate_id' => $this->candidate->id,
            'saved_search_id' => $this->savedSearch->id,
            'saved_search_title' => $this->savedSearch->title,
            'action_url' => route('discovery.show', $this->candidate->id),
            'icon' => '🔎',
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        $data = $this->toArray($notifiable);
        $data['id'] = $this->id;
        $data['created_at'] = now()->diffForHumans();

        return new BroadcastMessage($data);
    }

    /**
     * @return array<int, PrivateChannel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('App.Models.User.'.$this->targetUserId),
            new PrivateChannel('user.'.$this->targetUserId),
        ];
    }
}

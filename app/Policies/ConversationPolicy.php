<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\User;

class ConversationPolicy
{
    /**
     * Determine whether the user can view the conversation and its messages.
     */
    public function view(User $user, Conversation $conversation): bool
    {
        if ($user->isSuperAdmin() || $user->isModerator()) {
            return true;
        }

        return $conversation->participants()->where('users.id', $user->id)->exists();
    }

    /**
     * Determine whether the user can post a message in the conversation.
     */
    public function sendMessage(User $user, Conversation $conversation): bool
    {
        // 1. Conversation must not be locked
        if ($conversation->isLocked()) {
            return false;
        }

        // 2. User must be an active participant
        $isParticipant = $conversation->participants()->where('users.id', $user->id)->exists();
        if (! $isParticipant) {
            return false;
        }

        // 3. Neither participant has blocked the other
        $otherParticipants = $conversation->participants()->where('users.id', '!=', $user->id)->get();
        foreach ($otherParticipants as $other) {
            if ($user->hasBlockedRelationshipWith($other)) {
                return false;
            }
        }

        return true;
    }
}


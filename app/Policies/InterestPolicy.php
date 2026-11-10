<?php

namespace App\Policies;

use App\Models\Interest;
use App\Models\User;

class InterestPolicy
{
    /**
     * Determine whether the user can view the proposal.
     */
    public function view(User $user, Interest $interest): bool
    {
        if ($user->isSuperAdmin() || $user->isModerator()) {
            return true;
        }

        // Sender, Recipient, or linked Wali
        if ($interest->sender_id === $user->id || $interest->recipient_id === $user->id) {
            return true;
        }

        if ($interest->wali_id === $user->id) {
            return true;
        }

        // Check if user is linked Wali for recipient or sender
        return $user->waliLinksAsWali()
            ->where(function ($query) use ($interest) {
                $query->where('seeker_user_id', $interest->recipient_id)
                    ->orWhere('seeker_user_id', $interest->sender_id);
            })
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Determine whether the user can respond to the proposal (Accept / Decline).
     */
    public function respond(User $user, Interest $interest): bool
    {
        // 1. Recipient can respond if not exclusively locked by full proxy Wali
        if ($interest->recipient_id === $user->id) {
            $isFullProxy = $user->waliLinksAsSeeker()
                ->where('status', 'active')
                ->where('permission_level', 'full_proxy')
                ->exists();

            return ! $isFullProxy;
        }

        // 2. Linked Wali of recipient can respond
        return $user->waliLinksAsWali()
            ->where('seeker_user_id', $interest->recipient_id)
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Determine whether the user can withdraw the proposal.
     */
    public function withdraw(User $user, Interest $interest): bool
    {
        return $interest->sender_id === $user->id && $interest->status === 'pending';
    }
}


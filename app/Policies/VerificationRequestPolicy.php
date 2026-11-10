<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VerificationRequest;

class VerificationRequestPolicy
{
    /**
     * Determine whether the user can view verification queues.
     */
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || ($user->isModerator() && $user->hasPermission('manage_verifications'));
    }

    /**
     * Determine whether the user can view the specific verification request.
     */
    public function view(User $user, VerificationRequest $request): bool
    {
        if ($user->id === $request->user_id) {
            return true;
        }

        return $this->viewAny($user);
    }

    /**
     * Determine whether the user can review/approve/reject the verification request.
     */
    public function review(User $user, VerificationRequest $request): bool
    {
        return $this->viewAny($user);
    }
}


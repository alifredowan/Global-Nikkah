<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the current user can update the target user profile/settings.
     */
    public function update(User $currentUser, User $targetUser): bool
    {
        return $currentUser->id === $targetUser->id || $currentUser->isSuperAdmin();
    }

    /**
     * Determine whether the current user can delete or deactivate the target user account.
     */
    public function delete(User $currentUser, User $targetUser): bool
    {
        return $currentUser->id === $targetUser->id || $currentUser->isSuperAdmin();
    }
}


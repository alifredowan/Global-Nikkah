<?php

namespace App\Policies;

use App\Models\Photo;
use App\Models\User;

class PhotoPolicy
{
    /**
     * Determine whether the user can view the unblurred raw photo.
     */
    public function view(User $user, Photo $photo): bool
    {
        return (bool) $photo->profile?->isPhotoVisibleTo($user);
    }

    /**
     * Determine whether the user can delete the photo.
     */
    public function delete(User $user, Photo $photo): bool
    {
        return $photo->profile && $photo->profile->user_id === $user->id;
    }

    /**
     * Determine whether the user can set the photo as primary.
     */
    public function setPrimary(User $user, Photo $photo): bool
    {
        return $photo->profile && $photo->profile->user_id === $user->id;
    }
}

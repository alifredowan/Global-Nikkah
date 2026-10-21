<?php

namespace App\Services;

use App\Models\SavedSearch;
use App\Models\User;
use App\Notifications\SavedSearchMatchNotification;

class SavedSearchAlertService
{
    public function notifyFor(User $candidate): void
    {
        if (! $candidate->isSeeker() || ! $candidate->is_active || ! $candidate->profile) {
            return;
        }

        $savedSearches = SavedSearch::with('user')
            ->where('alerts_enabled', true)
            ->whereHas('user', function ($query) use ($candidate) {
                $query->where('id', '!=', $candidate->id)
                    ->where('role', 'seeker')
                    ->where('is_active', true)
                    ->where('gender', '!=', $candidate->gender);
            })
            ->get();

        foreach ($savedSearches as $savedSearch) {
            if (! $this->matches($savedSearch, $candidate) || $this->alreadyNotified($savedSearch, $candidate)) {
                continue;
            }

            $savedSearch->user->notify(new SavedSearchMatchNotification(
                $savedSearch,
                $candidate,
                $savedSearch->user_id
            ));
        }
    }

    private function matches(SavedSearch $savedSearch, User $candidate): bool
    {
        $filters = collect($savedSearch->filters ?? [])->filter(fn ($value): bool => filled($value));
        $profile = $candidate->profile;

        if ($filters->has('country') && ! $this->contains($profile->country, $filters->get('country'))) {
            return false;
        }

        if ($filters->has('city') && ! $this->contains($profile->city, $filters->get('city'))) {
            return false;
        }

        if ($filters->has('marital_status') && $candidate->marital_status !== $filters->get('marital_status')) {
            return false;
        }

        if ($filters->has('age_min') && ($candidate->age === null || $candidate->age < (int) $filters->get('age_min'))) {
            return false;
        }

        if ($filters->has('age_max') && ($candidate->age === null || $candidate->age > (int) $filters->get('age_max'))) {
            return false;
        }

        foreach (['sect' => 'sect_madhhab', 'prayer_frequency' => 'prayer_frequency', 'education_level' => 'education_level'] as $filter => $field) {
            if ($filters->has($filter) && $profile->{$field} !== $filters->get($filter)) {
                return false;
            }
        }

        return ! $filters->has('wali_required') || $profile->wali_required === filter_var($filters->get('wali_required'), FILTER_VALIDATE_BOOLEAN);
    }

    private function contains(?string $value, mixed $search): bool
    {
        return $value !== null && str_contains(strtolower($value), strtolower((string) $search));
    }

    private function alreadyNotified(SavedSearch $savedSearch, User $candidate): bool
    {
        return $savedSearch->user->notifications()
            ->where('type', SavedSearchMatchNotification::class)
            ->where('data', 'like', '%"saved_search_id":'.$savedSearch->id.'%')
            ->where('data', 'like', '%"candidate_id":'.$candidate->id.'%')
            ->exists();
    }
}

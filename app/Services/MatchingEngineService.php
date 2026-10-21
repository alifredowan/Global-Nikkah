<?php

namespace App\Services;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Collection;

class MatchingEngineService
{
    /**
     * Compute compatibility percentage between two seeker profiles.
     * Weights religiosity fields higher than personal/lifestyle preferences (FR-3.2, FR-6.4).
     */
    public function computeCompatibility(Profile $p1, Profile $p2): int
    {
        $u1 = $p1->user;
        $u2 = $p2->user;

        // Non-seekers (e.g. super_admin, moderator, wali) cannot be matched
        if (($u1->role && $u1->role !== 'seeker') || ($u2->role && $u2->role !== 'seeker')) {
            return 0;
        }

        if ($u1->gender && $u2->gender && $u1->gender === $u2->gender) {
            return 0;
        }

        $score = 0;
        $maxPossible = 100;

        // 1. Religious Alignment (Weight: 45 points)
        // Sect / Madhhab (20 points)
        if ($p1->sect_madhhab && $p2->sect_madhhab) {
            if (strtolower($p1->sect_madhhab) === strtolower($p2->sect_madhhab)) {
                $score += 20;
            } elseif (str_contains(strtolower($p1->sect_madhhab), 'sunni') && str_contains(strtolower($p2->sect_madhhab), 'sunni')) {
                $score += 15;
            }
        } else {
            $score += 10;
        }

        // Prayer frequency (15 points)
        if ($p1->prayer_frequency && $p2->prayer_frequency) {
            if ($p1->prayer_frequency === $p2->prayer_frequency) {
                $score += 15;
            } elseif ($p1->prayer_frequency === '5x_daily' || $p2->prayer_frequency === '5x_daily') {
                $score += 10;
            } else {
                $score += 5;
            }
        } else {
            $score += 8;
        }

        // Halal dietary adherence (5 points)
        if ($p1->halal_dietary_adherence && $p2->halal_dietary_adherence) {
            if ($p1->halal_dietary_adherence === $p2->halal_dietary_adherence) {
                $score += 5;
            } else {
                $score += 3;
            }
        } else {
            $score += 3;
        }

        // Quran knowledge / practice (5 points)
        if ($p1->quran_knowledge && $p2->quran_knowledge) {
            if ($p1->quran_knowledge === $p2->quran_knowledge) {
                $score += 5;
            } else {
                $score += 3;
            }
        } else {
            $score += 3;
        }

        // 2. Family & Values Alignment (Weight: 20 points)
        if ($p1->desired_family_structure && $p2->desired_family_structure) {
            if ($p1->desired_family_structure === $p2->desired_family_structure) {
                $score += 10;
            }
        } else {
            $score += 5;
        }

        if ($p1->family_religiosity && $p2->family_religiosity) {
            if ($p1->family_religiosity === $p2->family_religiosity) {
                $score += 10;
            } else {
                $score += 5;
            }
        } else {
            $score += 5;
        }

        // 3. Geographic / Location Alignment (Weight: 15 points)
        if ($p1->country && $p2->country) {
            if (strtolower($p1->country) === strtolower($p2->country)) {
                $score += 10;
                if ($p1->city && $p2->city && strtolower($p1->city) === strtolower($p2->city)) {
                    $score += 5;
                }
            }
        } else {
            $score += 5;
        }

        // 4. Education & Profession Alignment (Weight: 10 points)
        if ($p1->education_level && $p2->education_level) {
            if ($p1->education_level === $p2->education_level) {
                $score += 10;
            } else {
                $score += 5;
            }
        } else {
            $score += 5;
        }

        // 5. Age Preference Alignment (Weight: 10 points)
        $prefs1 = $p1->partner_preferences ?? [];
        $age2 = $u2->age;
        if ($age2 && ! empty($prefs1['age_min']) && ! empty($prefs1['age_max'])) {
            if ($age2 >= $prefs1['age_min'] && $age2 <= $prefs1['age_max']) {
                $score += 10;
            } elseif (abs($age2 - $prefs1['age_min']) <= 2 || abs($age2 - $prefs1['age_max']) <= 2) {
                $score += 5;
            }
        } else {
            $score += 7;
        }

        return min(100, max(15, $score));
    }

    /**
     * Explain the strongest compatibility signals shared by two profiles.
     *
     * @return array<int, array{icon: string, label: string, detail: string}>
     */
    public function getCompatibilityHighlights(Profile $p1, Profile $p2): array
    {
        if ($this->computeCompatibility($p1, $p2) === 0) {
            return [];
        }

        $highlights = [];

        if ($p1->sect_madhhab && $p2->sect_madhhab && strtolower($p1->sect_madhhab) === strtolower($p2->sect_madhhab)) {
            $highlights[] = ['icon' => '🕌', 'label' => 'Shared madhhab', 'detail' => "Both profiles indicate {$p1->sect_madhhab}."];
        }

        if ($p1->prayer_frequency && $p2->prayer_frequency && $p1->prayer_frequency === $p2->prayer_frequency) {
            $highlights[] = ['icon' => '🤲', 'label' => 'Prayer alignment', 'detail' => 'A similar daily prayer practice is listed.'];
        }

        if ($p1->halal_dietary_adherence && $p2->halal_dietary_adherence && $p1->halal_dietary_adherence === $p2->halal_dietary_adherence) {
            $highlights[] = ['icon' => '🌿', 'label' => 'Shared halal practice', 'detail' => 'Both profiles list the same dietary standard.'];
        }

        if ($p1->desired_family_structure && $p2->desired_family_structure && $p1->desired_family_structure === $p2->desired_family_structure) {
            $highlights[] = ['icon' => '🏡', 'label' => 'Family vision', 'detail' => 'Your preferred family structure is aligned.'];
        }

        if ($p1->family_religiosity && $p2->family_religiosity && $p1->family_religiosity === $p2->family_religiosity) {
            $highlights[] = ['icon' => '🤝', 'label' => 'Family values', 'detail' => 'Both profiles describe a similar family-religiosity level.'];
        }

        if ($p1->country && $p2->country && strtolower($p1->country) === strtolower($p2->country)) {
            $highlights[] = ['icon' => '📍', 'label' => 'Shared country', 'detail' => "Both profiles are based in {$p1->country}."];
        }

        if ($p1->education_level && $p2->education_level && $p1->education_level === $p2->education_level) {
            $highlights[] = ['icon' => '🎓', 'label' => 'Education match', 'detail' => 'Both profiles list a similar education level.'];
        }

        $candidateAge = $p2->user->age;
        $preferences = $p1->partner_preferences ?? [];
        if ($candidateAge && ! empty($preferences['age_min']) && ! empty($preferences['age_max']) && $candidateAge >= $preferences['age_min'] && $candidateAge <= $preferences['age_max']) {
            $highlights[] = ['icon' => '🎯', 'label' => 'Within your preference', 'detail' => 'Their age is within your selected partner range.'];
        }

        return array_slice($highlights, 0, 4);
    }

    /**
     * Get ranked match suggestions for a given user.
     */
    public function getDailyMatches(User $user, int $limit = 12): Collection
    {
        $profile = $user->profile;
        if (! $profile) {
            return collect();
        }

        $targetGender = $user->gender === 'male' ? 'female' : ($user->gender === 'female' ? 'male' : null);
        if (! $targetGender) {
            return collect();
        }

        $excludedUserIds = $user->blockedUsers()
            ->pluck('users.id')
            ->merge($user->blockedByUsers()->pluck('users.id'))
            ->unique()
            ->values();

        $query = Profile::with(['user.activeSubscription', 'primaryPhoto'])
            ->where('user_id', '!=', $user->id)
            ->whereNotIn('user_id', $excludedUserIds)
            ->whereHas('user', function ($q) use ($targetGender) {
                $q->where('is_active', true)
                    ->where('role', 'seeker')
                    ->where('marital_status', '!=', 'married')
                    ->where('gender', $targetGender);
            });

        $candidates = $query->get();

        return $candidates->map(function (Profile $candidate) use ($profile) {
            $candidate->compatibility_score = $this->computeCompatibility($profile, $candidate);
            $candidate->compatibility_highlights = $this->getCompatibilityHighlights($profile, $candidate);

            return $candidate;
        })->sortByDesc(function (Profile $candidate) {
            $boostScore = $candidate->user->isPremium() ? 1000 : 0;

            return $boostScore + $candidate->compatibility_score;
        })->take($limit)->values();
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use App\Services\MatchingEngineService;
use App\Services\QuotaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DiscoveryApiController extends Controller
{
    public function __construct(
        protected MatchingEngineService $matchingEngine,
        protected QuotaService $quotaService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'country' => ['nullable', 'string', 'max:255'],
            'sect' => ['nullable', 'string', 'in:Sunni - Hanafi,Sunni - Shafi\'i,Sunni - Maliki,Sunni - Hanbali,Shia - Jafari,Muslim (Unspecified)'],
        ]);

        $user = $request->user();
        $targetGender = $user->gender === 'male' ? 'female' : ($user->gender === 'female' ? 'male' : 'female');

        $query = Profile::with(['user', 'primaryPhoto'])
            ->where('user_id', '!=', $user->id)
            ->whereNotIn('user_id', $user->blockedUsers()->pluck('users.id'))
            ->whereNotIn('user_id', $user->blockedByUsers()->pluck('users.id'))
            ->whereHas('user', function ($q) use ($targetGender) {
                $q->where('is_active', true)
                    ->where('role', 'seeker')
                    ->where('gender', $targetGender);
            });

        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }
        if ($request->filled('sect')) {
            $query->where('sect_madhhab', $request->sect);
        }

        $profiles = $query->paginate(15);

        if ($user->profile) {
            $profiles->getCollection()->transform(function ($p) use ($user) {
                $p->compatibility_score = $this->matchingEngine->computeCompatibility($user->profile, $p);
                $this->hidePrivateCandidateFields($p->user);

                return $p;
            });
        }

        return response()->json([
            'status' => 'success',
            'data' => $profiles,
        ]);
    }

    public function dailyMatches(Request $request): JsonResponse
    {
        $user = $request->user();
        $matches = $this->matchingEngine->getDailyMatches($user, 10);

        return response()->json([
            'status' => 'success',
            'data' => $matches,
        ]);
    }

    public function show(Request $request, int $userId): JsonResponse
    {
        $user = $request->user();
        $targetUser = User::with(['profile.photos', 'profile.primaryPhoto'])->findOrFail($userId);

        if ($targetUser->id === $user->id || ! $targetUser->is_active || $targetUser->role !== 'seeker' || ! $targetUser->profile) {
            return response()->json(['status' => 'error', 'message' => 'Profile not found or not available for matrimonial matching.'], 404);
        }

        if ($user->hasBlockedRelationshipWith($targetUser)) {
            return response()->json([
                'status' => 'error',
                'message' => 'This profile is unavailable because one of the profiles has been blocked.',
            ], 403);
        }

        if ($targetUser->isMarried() && $user->spouse()?->id !== $targetUser->id) {
            return response()->json(['status' => 'error', 'message' => 'This candidate is no longer available for matching.'], 404);
        }

        if (! $this->quotaService->canViewProfile($user, $targetUser->id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Daily profile view limit reached. Upgrade to Premium for unlimited views.',
            ], 403);
        }

        $this->quotaService->recordView($user, $targetUser->id);

        $compatibility = null;
        if ($user->profile && $targetUser->profile) {
            $compatibility = $this->matchingEngine->computeCompatibility($user->profile, $targetUser->profile);
        }

        $isPhotoVisible = $targetUser->profile?->isPhotoVisibleTo($user);
        $this->hidePrivateCandidateFields($targetUser);

        return response()->json([
            'status' => 'success',
            'user' => $targetUser,
            'compatibility_score' => $compatibility,
            'is_photo_visible' => $isPhotoVisible,
        ]);
    }

    private function hidePrivateCandidateFields(User $candidate): void
    {
        $candidate->makeHidden([
            'email',
            'phone',
            'dob',
            'remember_token',
            'two_factor_secret',
        ]);
    }
}

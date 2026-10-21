<?php

namespace App\Http\Controllers;

use App\Models\Interest;
use App\Models\Profile;
use App\Models\SavedSearch;
use App\Models\User;
use App\Services\MatchingEngineService;
use App\Services\QuotaService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DiscoveryController extends Controller
{
    public function __construct(
        protected MatchingEngineService $matchingEngine,
        protected QuotaService $quotaService
    ) {}

    public function index(Request $request): View
    {
        $request->validate([
            'gender' => ['nullable', 'in:male,female'],
            'country' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'marital_status' => ['nullable', 'in:never_married,divorced,widowed,annulled'],
            'age_min' => ['nullable', 'integer', 'min:18', 'max:100', 'lte:age_max'],
            'age_max' => ['nullable', 'integer', 'min:18', 'max:100'],
            'sect' => ['nullable', 'string', 'in:Sunni - Hanafi,Sunni - Shafi\'i,Sunni - Maliki,Sunni - Hanbali,Shia - Jafari,Muslim (Unspecified)'],
            'prayer_frequency' => ['nullable', 'in:5x_daily,occasional,rarely'],
            'education_level' => ['nullable', 'string', 'max:255'],
            'wali_required' => ['nullable', 'boolean'],
            'sort' => ['nullable', 'in:newest,verified,complete'],
        ]);

        $currentUser = Auth::user();
        $userProfile = $currentUser->profile;

        // Determine strict target opposite gender
        // Male logged in -> ONLY female seeker profiles
        // Female logged in -> ONLY male seeker profiles
        if ($currentUser->gender === 'male') {
            $targetGender = 'female';
        } elseif ($currentUser->gender === 'female') {
            $targetGender = 'male';
        } else {
            $targetGender = $request->input('gender', 'female');
        }

        $query = Profile::with(['user.activeSubscription', 'primaryPhoto', 'photos'])
            ->where('user_id', '!=', $currentUser->id)
            ->whereNotIn('user_id', $currentUser->blockedUsers()->pluck('users.id'))
            ->whereNotIn('user_id', $currentUser->blockedByUsers()->pluck('users.id'))
            ->whereHas('user', function ($q) use ($targetGender) {
                $q->where('is_active', true)
                    ->where('role', 'seeker')
                    ->where('marital_status', '!=', 'married')
                    ->where('gender', $targetGender);
            });

        // Advanced filter gating check:
        $isSearching = $request->filled('sect') || $request->filled('prayer_frequency');
        if ($isSearching && ! $currentUser->isPremium() && ! $currentUser->isAdmin()) {
            // Free members can still filter by basic fields: age, country, marital_status
        }

        // Basic Filters
        if ($request->filled('country')) {
            $query->where('country', 'like', '%'.$request->country.'%');
        }
        if ($request->filled('city')) {
            $query->where('city', 'like', '%'.$request->city.'%');
        }
        if ($request->filled('marital_status')) {
            $query->whereHas('user', fn ($q) => $q->where('marital_status', $request->marital_status));
        }

        // Age filter
        if ($request->filled('age_min')) {
            $maxDob = Carbon::now()->subYears((int) $request->age_min)->toDateString();
            $query->whereHas('user', fn ($q) => $q->where('dob', '<=', $maxDob));
        }
        if ($request->filled('age_max')) {
            $minDob = Carbon::now()->subYears((int) $request->age_max + 1)->toDateString();
            $query->whereHas('user', fn ($q) => $q->where('dob', '>=', $minDob));
        }

        // Advanced Filters (religiosity & education)
        if ($request->filled('sect')) {
            $query->where('sect_madhhab', $request->sect);
        }
        if ($request->filled('prayer_frequency')) {
            $query->where('prayer_frequency', $request->prayer_frequency);
        }
        if ($request->filled('education_level')) {
            $query->where('education_level', $request->education_level);
        }
        if ($request->boolean('wali_required')) {
            $query->where('wali_required', true);
        }

        $sort = in_array($request->input('sort'), ['newest', 'verified', 'complete'], true)
            ? $request->input('sort')
            : 'newest';

        if ($sort === 'verified') {
            $query->join('users as sort_users', 'sort_users.id', '=', 'profiles.user_id')
                ->select('profiles.*')
                ->orderByDesc('sort_users.is_verified')
                ->orderByDesc('profiles.created_at');
        } elseif ($sort === 'complete') {
            $query->orderByDesc('profiles.completeness_percentage')
                ->orderByDesc('profiles.created_at');
        } else {
            $query->orderByDesc('profiles.created_at');
        }

        $profiles = $query->paginate(12)->withQueryString();

        // Calculate compatibility score for each result
        if ($userProfile) {
            $profiles->getCollection()->transform(function ($profile) use ($userProfile) {
                $profile->compatibility_score = $this->matchingEngine->computeCompatibility($userProfile, $profile);
                $profile->compatibility_highlights = $this->matchingEngine->getCompatibilityHighlights($userProfile, $profile);

                return $profile;
            });
        }

        // Top algorithmic daily recommendations
        $dailyRecommendations = $this->matchingEngine->getDailyMatches($currentUser, 6);

        // Daily quotas for current user
        $quotaStats = $this->quotaService->getUsageStats($currentUser);

        $bookmarkedUserIds = $currentUser->bookmarks()->pluck('bookmarked_user_id')->all();
        $savedSearches = $currentUser->savedSearches()->latest()->get();

        return view('discovery.index', compact('profiles', 'dailyRecommendations', 'quotaStats', 'bookmarkedUserIds', 'savedSearches', 'userProfile', 'sort'));
    }

    public function show(int $userId): View|RedirectResponse
    {
        $currentUser = Auth::user();

        $targetUser = User::with(['profile.photos', 'profile.primaryPhoto'])->findOrFail($userId);
        $profile = $targetUser->profile;

        if ($currentUser->hasBlockedRelationshipWith($targetUser)) {
            return redirect()->route('discovery.index')->with('error', 'This profile is blocked from your discovery experience.');
        }

        if (! $profile || ! $targetUser->is_active || $targetUser->role !== 'seeker' || $targetUser->id === $currentUser->id) {
            return redirect()->route('discovery.index')->with('error', 'Profile not found or not available for matrimonial matching.');
        }

        if ($targetUser->isMarried() && $currentUser->spouse()?->id !== $targetUser->id) {
            return redirect()->route('discovery.index')->with('info', 'Alhamdulillah! This candidate has successfully completed their Nikah and is no longer available for matching.');
        }

        if ($currentUser->role === 'seeker' && $currentUser->gender && $targetUser->gender && $currentUser->gender === $targetUser->gender) {
            return redirect()->route('discovery.index')->with('error', 'You can only view candidate profiles of the opposite gender.');
        }

        // Enforce daily profile view limit (FR-3.4)
        if (! $this->quotaService->canViewProfile($currentUser, $targetUser->id)) {
            return redirect()->route('subscription.pricing')->with('warning', 'You have reached your free limit of 10 profile views per day. Upgrade to Premium for unlimited views!');
        }

        $this->quotaService->recordView($currentUser, $targetUser->id);

        $compatibilityScore = null;
        if ($currentUser->profile) {
            $compatibilityScore = $this->matchingEngine->computeCompatibility($currentUser->profile, $profile);
        }
        $compatibilityHighlights = $currentUser->profile
            ? $this->matchingEngine->getCompatibilityHighlights($currentUser->profile, $profile)
            : [];

        // Check photo visibility permission (blurred vs visible)
        $isPhotoVisible = $profile->isPhotoVisibleTo($currentUser);
        $hasGrantedPhotoAccess = $currentUser->profile?->photoAccessGrants()
            ->where('granted_to_user_id', $targetUser->id)
            ->where('status', 'active')
            ->exists() ?? false;

        // Check existing interest relationship
        $existingInterest = Interest::where(function ($q) use ($currentUser, $targetUser) {
            $q->where('sender_id', $currentUser->id)->where('recipient_id', $targetUser->id);
        })->orWhere(function ($q) use ($currentUser, $targetUser) {
            $q->where('sender_id', $targetUser->id)->where('recipient_id', $currentUser->id);
        })->first();

        // Check active linked Wali
        $activeWaliLink = $targetUser->waliLinksAsSeeker()->where('status', 'active')->first();

        // Check if shortlisted by current user
        $isBookmarked = $currentUser->hasBookmarked($targetUser->id);

        return view('discovery.show', compact('targetUser', 'profile', 'compatibilityScore', 'compatibilityHighlights', 'isPhotoVisible', 'hasGrantedPhotoAccess', 'existingInterest', 'activeWaliLink', 'isBookmarked'));
    }

    public function saveSearch(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => ['required', 'string', 'max:100'],
        ]);

        $filters = array_filter(
            $request->only([
                'age_min',
                'age_max',
                'country',
                'city',
                'marital_status',
                'sect',
                'prayer_frequency',
                'education_level',
                'wali_required',
            ]),
            fn ($value): bool => filled($value)
        );

        SavedSearch::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'filters' => $filters,
            'alerts_enabled' => true,
        ]);

        return back()->with('success', 'Search criteria saved! You will receive alerts when matching candidates join (FR-3.3).');
    }

    public function toggleSavedSearchAlerts(int $savedSearchId): RedirectResponse
    {
        $savedSearch = Auth::user()->savedSearches()->findOrFail($savedSearchId);
        $savedSearch->update(['alerts_enabled' => ! $savedSearch->alerts_enabled]);

        return back()->with('success', $savedSearch->alerts_enabled
            ? 'Alerts enabled for this saved search.'
            : 'Alerts paused for this saved search.');
    }

    public function destroySavedSearch(int $savedSearchId): RedirectResponse
    {
        Auth::user()->savedSearches()->findOrFail($savedSearchId)->delete();

        return back()->with('success', 'Saved search removed.');
    }
}

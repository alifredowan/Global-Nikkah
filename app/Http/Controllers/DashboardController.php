<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Services\MatchingEngineService;
use App\Services\QuotaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected MatchingEngineService $matchingEngine,
        protected QuotaService $quotaService
    ) {}

    public function index(): View|RedirectResponse
    {
        $user = Auth::user();

        if ($user->isSuperAdmin() || $user->isModerator()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isWali()) {
            return redirect()->route('wali.dashboard');
        }

        $profile = $user->profile ?? Profile::create(['user_id' => $user->id]);
        $profile->loadMissing(['primaryPhoto']);
        $profile->updateCompleteness();

        $pendingInterests = $user->receivedInterests()
            ->where('status', 'pending')
            ->count();
        $recentInterests = $user->receivedInterests()
            ->where('status', 'pending')
            ->with(['sender.profile.primaryPhoto'])
            ->latest()
            ->limit(3)
            ->get();
        $verificationRequest = $user->verificationRequests()->latest()->first();
        $activeWaliLink = $user->waliLinksAsSeeker()
            ->where('status', 'active')
            ->with('wali')
            ->first();
        $savedSearches = $user->savedSearches()->latest()->limit(3)->get();
        $dailyMatches = $this->matchingEngine->getDailyMatches($user, 4);
        $quotaStats = $this->quotaService->getUsageStats($user);
        $unreadNotifications = $user->unreadNotifications()->count();
        $conversationCount = $user->conversations()->count();
        $unreadMessages = $user->unreadMessagesCount();
        $onboardingSteps = [
            [
                'label' => 'Complete your biodata',
                'description' => 'Add the details families need to understand your values.',
                'complete' => $profile->completeness_percentage >= 70,
                'route' => 'profile.edit',
            ],
            [
                'label' => 'Add a primary photo',
                'description' => 'A modest photo helps your profile feel genuine and welcoming.',
                'complete' => $profile->primaryPhoto !== null,
                'route' => 'profile.show',
            ],
            [
                'label' => 'Verify your identity',
                'description' => 'Build trust with a reviewed identity badge.',
                'complete' => $user->is_verified || $verificationRequest?->status === 'pending',
                'route' => 'profile.show',
            ],
            [
                'label' => 'Connect your Wali',
                'description' => 'Invite a trusted guardian to support your journey.',
                'complete' => ! $profile->wali_required || $activeWaliLink !== null,
                'route' => 'wali.link',
            ],
            [
                'label' => 'Set partner preferences',
                'description' => 'Give matching more context about who you hope to meet.',
                'complete' => collect($profile->partner_preferences ?? [])->filter(fn ($value): bool => filled($value))->isNotEmpty(),
                'route' => 'profile.edit',
            ],
        ];
        $completedOnboardingSteps = collect($onboardingSteps)->where('complete', true)->count();

        $nextAction = match (true) {
            $profile->completeness_percentage < 70 => [
                'eyebrow' => 'Improve your visibility',
                'title' => 'Complete your matrimonial profile',
                'description' => 'A richer profile helps families understand your deen, values, and life goals.',
                'route' => 'profile.edit',
                'label' => 'Complete profile',
            ],
            ! $user->is_verified && (! $verificationRequest || $verificationRequest->status === 'rejected') => [
                'eyebrow' => 'Build trust early',
                'title' => 'Submit your identity verification',
                'description' => 'Verified profiles create more confidence for families and prospective matches.',
                'route' => 'profile.show',
                'label' => 'Open verification',
            ],
            $profile->wali_required && ! $activeWaliLink => [
                'eyebrow' => 'Family involvement',
                'title' => 'Connect your Wali',
                'description' => 'Invite a trusted guardian to support your conversations and approvals.',
                'route' => 'wali.link',
                'label' => 'Link a Wali',
            ],
            $pendingInterests > 0 => [
                'eyebrow' => 'Your next conversation',
                'title' => 'You have pending interests',
                'description' => 'Review the people who have expressed a sincere interest in your profile.',
                'route' => 'interests.index',
                'label' => 'Review interests',
            ],
            default => [
                'eyebrow' => 'Keep your search moving',
                'title' => 'Explore today’s compatible matches',
                'description' => 'Review deen-weighted recommendations selected for your preferences.',
                'route' => 'discovery.index',
                'label' => 'Find matches',
            ],
        };

        return view('dashboard', compact(
            'user',
            'profile',
            'pendingInterests',
            'recentInterests',
            'verificationRequest',
            'activeWaliLink',
            'savedSearches',
            'dailyMatches',
            'quotaStats',
            'unreadNotifications',
            'conversationCount',
            'unreadMessages',
            'onboardingSteps',
            'completedOnboardingSteps',
            'nextAction'
        ));
    }
}

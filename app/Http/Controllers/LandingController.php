<?php

namespace App\Http\Controllers;

use App\Models\Marriage;
use App\Models\Profile;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View
    {
        return view('landing', $this->landingData());
    }

    /**
     * @return array{stats: array{active_seekers: int, total_seekers: int, verified_seekers: int, verified_percentage: int, wali_accounts: int, successful_matches: int}, recentProfiles: Collection, plans: Collection}
     */
    private function landingData(): array
    {
        try {
            $activeSeekers = User::where('role', 'seeker')->where('is_active', true)->count();
            $totalSeekers = User::where('role', 'seeker')->count();
            $verifiedSeekers = User::where('role', 'seeker')->where('is_verified', true)->count();
            $verifiedBase = $activeSeekers > 0 ? $activeSeekers : $totalSeekers;
            $verifiedPercentage = $verifiedBase > 0
                ? (int) round(($verifiedSeekers / $verifiedBase) * 100)
                : 0;

            $totalWalis = User::where('role', 'wali')->count();
            $activeWalis = User::where('role', 'wali')->where('is_active', true)->count();
            $waliAccounts = $totalWalis > 0 ? $totalWalis : $activeWalis;

            $successfulMatches = Marriage::where('status', 'confirmed')->count();

            $stats = [
                'active_seekers' => $activeSeekers,
                'total_seekers' => $totalSeekers,
                'verified_seekers' => $verifiedSeekers,
                'verified_percentage' => $verifiedPercentage,
                'wali_accounts' => $waliAccounts,
                'successful_matches' => $successfulMatches,
            ];
        } catch (\Throwable) {
            $stats = [
                'active_seekers' => 0,
                'total_seekers' => 0,
                'verified_seekers' => 0,
                'verified_percentage' => 0,
                'wali_accounts' => 0,
                'successful_matches' => 0,
            ];
        }

        try {
            $recentProfiles = Profile::with(['user', 'primaryPhoto'])
                ->whereHas('user', fn ($q) => $q->where('is_active', true)->where('role', 'seeker'))
                ->latest()
                ->take(4)
                ->get();
        } catch (\Throwable) {
            $recentProfiles = collect();
        }

        $plans = SubscriptionPlan::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('monthly_price')
            ->get();

        if ($plans->isEmpty()) {
            $plans = collect([
                new SubscriptionPlan(Subscription::getPlanDetails('free')),
                new SubscriptionPlan(Subscription::getPlanDetails('premium')),
                new SubscriptionPlan(Subscription::getPlanDetails('premium_plus')),
            ]);
        }

        return compact('stats', 'recentProfiles', 'plans');
    }

    public function privacy(): View
    {
        return view('pages.privacy');
    }

    public function cookiePolicy(): View
    {
        return view('pages.cookie-policy');
    }

    public function terms(): View
    {
        return view('pages.terms');
    }

    public function about(): View
    {
        return view('pages.about');
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function howItWorks(): View
    {
        return view('pages.how-it-works');
    }
}

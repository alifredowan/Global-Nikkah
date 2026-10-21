<?php

namespace App\Http\Controllers;

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
     * @return array{stats: array{total_seekers: int, verified_profiles: int, successful_matches: int}, recentProfiles: \Illuminate\Database\Eloquent\Collection, plans: Collection}
     */
    private function landingData(): array
    {
        $stats = [
            'total_seekers' => User::where('role', 'seeker')->count(),
            'verified_profiles' => User::where('is_verified', true)->count(),
            'successful_matches' => 128,
        ];

        $recentProfiles = Profile::with(['user', 'primaryPhoto'])
            ->whereHas('user', fn ($q) => $q->where('is_active', true)->where('role', 'seeker'))
            ->latest()
            ->take(4)
            ->get();

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

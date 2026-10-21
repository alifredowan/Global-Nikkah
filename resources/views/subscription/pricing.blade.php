@extends('layouts.app')

@section('title', 'Single Marriage Pass • One-Time Payment Until Nikah - Global Nikkah')
@section('meta_description', 'Compare Global Nikkah membership plans and choose a one-time marriage pass with premium matrimonial privileges until confirmed Nikah.')
@section('canonical_url', route('subscription.pricing'))
@section('robots', 'index, follow')

@section('content')
<div class="py-12 bg-slate-50 dark:bg-slate-950 min-h-screen transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

        <!-- Header / Ethical Philosophy -->
        <div class="text-center max-w-3xl mx-auto space-y-3">
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-emerald-100 dark:bg-emerald-950/80 border border-emerald-300 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs font-black uppercase tracking-wider shadow-xs">
                <span>💍</span>
                <span>Membership Plans & Tiers • Single Marriage Pass</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">
                Single Marriage Pass: Pay Once Until Nikah
            </h1>
            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed max-w-2xl mx-auto">
                Conventional dating apps profit from keeping users single and paying monthly subscriptions indefinitely.
                <strong class="text-slate-900 dark:text-slate-200">Global Nikkah aligns directly with your success</strong>: pay a single one-time pass fee and enjoy all premium matrimonial privileges until your marriage is confirmed.
            </p>

            <!-- Three Core Pillars -->
            <div class="pt-2 flex flex-wrap items-center justify-center gap-3 text-[11px] font-bold text-slate-700 dark:text-slate-300">
                <span class="flex items-center gap-1.5 px-3 py-1 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs">
                    <span class="text-emerald-600">✓</span> No Monthly Renewals
                </span>
                <span class="flex items-center gap-1.5 px-3 py-1 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs">
                    <span class="text-emerald-600">✓</span> Valid Until Confirmed Marriage
                </span>
                <span class="flex items-center gap-1.5 px-3 py-1 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs">
                    <span class="text-emerald-600">✓</span> Shariah Wali Supervision & Privacy
                </span>
            </div>
        </div>

        @if(session('success'))
            <div class="max-w-2xl mx-auto p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs font-semibold flex items-center gap-2">
                <span>✓</span> {{ session('success') }}
            </div>
        @endif

        @if(session('info'))
            <div class="max-w-2xl mx-auto p-4 rounded-2xl bg-blue-50 dark:bg-blue-950/60 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200 text-xs font-semibold flex items-center gap-2">
                <span>ℹ️</span> {{ session('info') }}
            </div>
        @endif

        @if(session('warning'))
            <div class="max-w-2xl mx-auto p-4 rounded-2xl bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-200 text-xs font-semibold flex items-center gap-2">
                <span>⚠️</span> {{ session('warning') }}
            </div>
        @endif

        <!-- Pricing Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-{{ min(4, max(3, count($plans))) }} gap-8 items-stretch">
            @foreach($plans as $plan)
                @php
                    $isCurrent = ($currentPlan === $plan->slug);
                    $isFree = $plan->isFree();
                    $isPopular = (bool) $plan->is_popular;
                @endphp

                <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 border-2 {{ $isCurrent ? 'border-emerald-600 ring-4 ring-emerald-100 dark:ring-emerald-950/60' : ($isPopular ? 'border-emerald-500 shadow-xl' : 'border-slate-200 dark:border-slate-800 shadow-xs') }} relative flex flex-col justify-between transition">
                    @if($plan->badge_text)
                        <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-3.5 py-0.5 rounded-full text-[11px] font-extrabold {{ $isPopular ? 'bg-emerald-600 text-white shadow-xs' : 'bg-amber-500 text-white shadow-xs' }} uppercase tracking-wider">
                            {{ $plan->badge_text }}
                        </div>
                    @endif

                    <div>
                        <!-- Header badge -->
                        <div class="mb-2">
                            @if($isCurrent)
                                <span class="px-3 py-1 rounded-full text-[10px] font-extrabold bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 uppercase tracking-wider">Your Active Pass</span>
                            @elseif($isFree)
                                <span class="px-3 py-1 rounded-full text-[10px] font-extrabold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 uppercase tracking-wider">Free Forever</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-[10px] font-extrabold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 uppercase tracking-wider">One-Time Marriage Pass</span>
                            @endif
                        </div>

                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mt-2 font-heading">{{ $plan->name }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 min-h-[32px]">{{ $plan->description }}</p>

                        <!-- Price Section -->
                        <div class="my-5 pb-5 border-b border-slate-100 dark:border-slate-800">
                            @if($isFree)
                                <div class="flex items-baseline gap-1.5">
                                    <span class="text-4xl font-extrabold text-slate-900 dark:text-white font-heading">$0</span>
                                    <span class="text-xs text-slate-500 dark:text-slate-400 font-semibold">/ forever free</span>
                                </div>
                                <span class="text-[11px] text-slate-400 dark:text-slate-500 block mt-1">Always available for seekers on basic matching.</span>
                            @else
                                <div class="space-y-1">
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-4xl font-extrabold text-slate-900 dark:text-white font-heading">${{ number_format($plan->monthly_price, 2) }}</span>
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300">
                                            Pay 1-Time
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-xs font-bold text-emerald-700 dark:text-emerald-400">
                                        <span>🛡️</span>
                                        <span>Active Until Confirmed Nikah</span>
                                    </div>
                                    <p class="text-[10px] text-slate-400 dark:text-slate-500">
                                        No recurring monthly deductions. Automatically completes upon successful marriage.
                                    </p>
                                </div>
                            @endif
                        </div>

                        <!-- Feature list -->
                        <ul class="space-y-3 text-xs text-slate-700 dark:text-slate-300 pt-1">
                            @if(!empty($plan->features) && count($plan->features) > 0)
                                @foreach($plan->features as $featureItem)
                                    <li class="flex items-start gap-2">
                                        <span class="text-emerald-600 dark:text-emerald-400 font-bold mt-0.5">✓</span>
                                        <span>{{ $featureItem }}</span>
                                    </li>
                                @endforeach
                            @else
                                <li class="flex items-center gap-2">
                                    <span class="text-emerald-600 font-bold">✓</span>
                                    <span>{{ $plan->isUnlimitedViews() ? 'Unlimited daily profile views' : $plan->daily_profile_views . ' daily profile views' }}</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="text-emerald-600 font-bold">✓</span>
                                    <span>{{ $plan->isUnlimitedInterests() ? 'Unlimited interest requests' : $plan->daily_interests . ' daily interest requests' }}</span>
                                </li>
                                @if($plan->advanced_filters)
                                    <li class="flex items-center gap-2">
                                        <span class="text-emerald-600 font-bold">✓</span>
                                        <span>Advanced religious filters (sect, madhhab, diet)</span>
                                    </li>
                                @endif
                                @if($plan->profile_boost)
                                    <li class="flex items-center gap-2 font-bold text-amber-700 dark:text-amber-400">
                                        <span>⚡</span>
                                        <span>Top profile boost in search discovery</span>
                                    </li>
                                @endif
                                @if($plan->see_who_viewed)
                                    <li class="flex items-center gap-2">
                                        <span class="text-emerald-600 font-bold">✓</span>
                                        <span>See who viewed your profile</span>
                                    </li>
                                @endif
                                @if($plan->dedicated_advisor)
                                    <li class="flex items-center gap-2 font-bold text-purple-700 dark:text-purple-400">
                                        <span>★</span>
                                        <span>Dedicated marriage advisor contact</span>
                                    </li>
                                @endif
                            @endif
                        </ul>
                    </div>

                    <!-- CTA Action Button -->
                    <div class="mt-8">
                        @if($isCurrent)
                            <button disabled class="w-full py-3 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 text-xs font-bold cursor-not-allowed">
                                Current Active Pass
                            </button>
                        @elseif($isFree)
                            @if(Auth::guest())
                                <a href="{{ route('register') }}" class="block text-center w-full py-3 rounded-xl border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-bold transition">
                                    Get Started for Free
                                </a>
                            @else
                                <form action="{{ route('subscription.cancel') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full py-3 rounded-xl border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-bold transition cursor-pointer">
                                        Switch to Free Explorer
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('subscription.checkout', $plan->slug) }}" class="block text-center w-full py-3.5 rounded-xl {{ $isPopular ? 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-md shadow-emerald-900/20' : 'bg-slate-900 dark:bg-slate-800 hover:bg-black dark:hover:bg-slate-700 text-white' }} text-xs font-bold transition">
                                Get {{ $plan->name }} (One-Time)
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Ethical Single-Payment Security Guarantee -->
        <div class="max-w-4xl mx-auto mt-12 p-6 sm:p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl font-bold shrink-0">
                    🤝
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900 dark:text-white">Our Single Marriage Guarantee (১ বার সাবস্ক্রিপশন গ্যারান্টি)</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        When you purchase a pass, you will never be subjected to unexpected monthly charges. Your account maintains all tier privileges until you and your chosen partner register and confirm your successful Nikah milestone on Global Nikkah.
                    </p>
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100 dark:border-slate-800 flex flex-wrap items-center justify-between gap-4 text-xs font-bold text-slate-700 dark:text-slate-300">
                <div class="flex items-center gap-2">
                    <span class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-indigo-700 dark:text-indigo-400">
                        <span>💳</span> Stripe Checkout
                    </span>
                    <span class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-sky-700 dark:text-sky-400">
                        <span>🅿️</span> PayPal Express
                    </span>
                </div>
                <div class="text-[11px] text-slate-400 dark:text-slate-500">
                    🔒 Protected with 256-bit SSL encryption. Zero automatic card renewals.
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

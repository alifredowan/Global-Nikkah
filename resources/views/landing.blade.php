@extends('layouts.app')

@section('title', 'Global Nikkah - Blessed Halal Muslim Matrimonial Platform')

@section('content')
<div class="overflow-hidden">

    <!-- ================================================================= -->
    <!-- 1. HERO SECTION                                                  -->
    <!-- ================================================================= -->
    <section class="relative bg-gradient-to-b from-emerald-950 via-slate-950 to-emerald-950 text-white pt-12 pb-20 lg:pt-20 lg:pb-28">
        <!-- Subtle Halal Ambient Glow -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -left-40 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 -right-40 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">

                <!-- Left Column Text & CTAs -->
                <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-900/60 border border-emerald-700/50 text-xs font-semibold text-emerald-300 backdrop-blur-md">
                        <span class="text-amber-400">★</span>
                        <span>Pure Intentions, Halal Unions</span>
                    </div>

                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-[1.15] text-white font-heading">
                        Find Your Righteous Partner with <span class="text-amber-400">Dignity</span> &amp; <span class="text-emerald-400">Family Barakah</span>.
                    </h1>

                    <p class="text-base sm:text-lg text-emerald-100/80 leading-relaxed font-light max-w-2xl mx-auto lg:mx-0">
                        Global Nikkah is an ethical Islamic matchmaking platform built strictly for marriage (Nikah), not casual dating. Featuring verified identities, guardian (Wali) participation, default photo blurring, and deen-weighted compatibility.
                    </p>

                    <!-- CTA Buttons -->
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 pt-2">
                        <a href="{{ route('register') }}" class="px-8 py-4 rounded-xl font-bold text-sm sm:text-base text-emerald-950 bg-amber-400 hover:bg-amber-300 shadow-xl shadow-amber-950/30 transition transform hover:-translate-y-0.5">
                            Create Halal Profile &rarr;
                        </a>
                        <a href="{{ route('discovery.index') }}" class="px-8 py-4 rounded-xl font-semibold text-sm sm:text-base text-white bg-white/10 hover:bg-white/15 border border-white/20 backdrop-blur-md transition">
                            Browse Candidates
                        </a>
                    </div>

                    <!-- Clean Framed Stats Bar (Never overlaps or clips) -->
                    <div class="pt-6">
                        <div class="grid grid-cols-3 gap-4 p-4 sm:p-5 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm max-w-lg mx-auto lg:mx-0 text-center">
                            <div>
                                <div class="text-2xl sm:text-3xl font-extrabold text-amber-300 font-heading">{{ $stats['total_seekers'] }}+</div>
                                <div class="text-[11px] sm:text-xs text-emerald-200/70 font-medium mt-0.5">Active Seekers</div>
                            </div>
                            <div class="border-x border-white/10">
                                <div class="text-2xl sm:text-3xl font-extrabold text-white font-heading">{{ $stats['verified_profiles'] }}</div>
                                <div class="text-[11px] sm:text-xs text-emerald-200/70 font-medium mt-0.5">KYC Verified</div>
                            </div>
                            <div>
                                <div class="text-2xl sm:text-3xl font-extrabold text-emerald-400 font-heading">100%</div>
                                <div class="text-[11px] sm:text-xs text-emerald-200/70 font-medium mt-0.5">Halal Governed</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column Showcase Card: Interactive Halal Passport Preview -->
                <div class="lg:col-span-5 flex justify-center">
                    <div class="w-full max-w-md bg-slate-900/90 backdrop-blur-xl rounded-3xl p-6 sm:p-7 border border-emerald-500/30 shadow-2xl space-y-5 relative">
                        <!-- Top Header Pill -->
                        <div class="flex items-center justify-between pb-4 border-b border-white/10">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                <span class="text-xs uppercase font-extrabold tracking-wider text-emerald-300">Halal Matrimonial Passport</span>
                            </div>
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">
                                🛡️ Verified
                            </span>
                        </div>

                        <!-- Candidate Profile Preview Snippet -->
                        <div class="p-4 rounded-2xl bg-white/5 border border-white/10 space-y-3">
                            <div class="flex items-center gap-3.5">
                                <div class="relative w-14 h-14 rounded-2xl bg-emerald-950 border border-emerald-400/40 flex items-center justify-center text-xl shrink-0 overflow-hidden shadow-inner">
                                    <span class="font-bold text-amber-300">☪</span>
                                    <div class="absolute inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center">
                                        <span class="text-[10px] text-amber-300">🔒 Haya</span>
                                    </div>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between gap-1">
                                        <h4 class="font-bold text-sm text-white truncate">Zayd Al-Ansari</h4>
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300">98% Match</span>
                                    </div>
                                    <p class="text-xs text-amber-300/90 mt-0.5">Sunni • Hanafi • 29 yrs</p>
                                    <p class="text-[11px] text-slate-400 truncate">London, UK • Software Architect</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between text-[11px] text-slate-300 pt-2 border-t border-white/10">
                                <span class="flex items-center gap-1">🕌 5x Daily Prayer</span>
                                <span class="flex items-center gap-1 text-emerald-400 font-semibold">🛡️ Wali Chaperoned</span>
                            </div>
                        </div>

                        <!-- 3 Integrity Pillars Mini-List -->
                        <div class="space-y-2.5">
                            <div class="flex items-center gap-3 p-2.5 rounded-xl bg-white/5 border border-white/5 text-xs text-slate-300">
                                <span class="w-8 h-8 rounded-lg bg-emerald-950 text-emerald-400 flex items-center justify-center shrink-0">🛡️</span>
                                <div class="min-w-0">
                                    <p class="font-bold text-white text-xs">Guardian (Wali) Transparency</p>
                                    <p class="text-[11px] text-slate-400">Chaperoned interactions to protect family honor.</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-2.5 rounded-xl bg-white/5 border border-white/5 text-xs text-slate-300">
                                <span class="w-8 h-8 rounded-lg bg-amber-950 text-amber-400 flex items-center justify-center shrink-0">🔒</span>
                                <div class="min-w-0">
                                    <p class="font-bold text-white text-xs">Modesty &amp; Blurred Photos</p>
                                    <p class="text-[11px] text-slate-400">Photos unlock only upon reciprocal mutual interest.</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-2.5 rounded-xl bg-white/5 border border-white/5 text-xs text-slate-300">
                                <span class="w-8 h-8 rounded-lg bg-blue-950 text-blue-400 flex items-center justify-center shrink-0">🪪</span>
                                <div class="min-w-0">
                                    <p class="font-bold text-white text-xs">KYC Identity Verification</p>
                                    <p class="text-[11px] text-slate-400">Government ID review by human moderators.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Bottom Tagline -->
                        <div class="flex items-center justify-between text-xs pt-1 text-slate-400">
                            <span class="text-emerald-400 font-semibold flex items-center gap-1">
                                <span>✓</span> Zero Tolerance for Dating
                            </span>
                            <span class="text-amber-400 font-semibold">100% Strictly for Nikah</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ================================================================= -->
    <!-- 2. CORE PILLARS OF ISLAMIC MATRIMONY                             -->
    <!-- ================================================================= -->
    <section class="py-20 bg-slate-50 dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">Islamic Matrimonial Ethics</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight font-heading">
                    Distinctly Islamic by Design, Not an Afterthought
                </h2>
                <p class="text-sm sm:text-base text-slate-600 dark:text-slate-400 leading-relaxed">
                    Unlike casual dating apps, Global Nikkah strictly adheres to Islamic marriage jurisprudence, modesty standards, and family involvement.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Pillar 1 -->
                <div class="bg-white dark:bg-slate-800/90 rounded-2xl p-6 border border-slate-200/80 dark:border-slate-700/60 shadow-xs hover:shadow-md hover:border-emerald-500/50 transition">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 flex items-center justify-center text-2xl mb-4">
                        🛡️
                    </div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white mb-2 font-heading">Wali (Guardian) Model</h3>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                        Female seekers can link their father or mahram with granular permissions: view-only oversight, chat approvals, or active proxy assistance.
                    </p>
                </div>

                <!-- Pillar 2 -->
                <div class="bg-white dark:bg-slate-800/90 rounded-2xl p-6 border border-slate-200/80 dark:border-slate-700/60 shadow-xs hover:shadow-md hover:border-emerald-500/50 transition">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 flex items-center justify-center text-2xl mb-4">
                        🔒
                    </div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white mb-2 font-heading">Photo Privacy by Default</h3>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                        Photos are blurred by default across search and discovery. Full unblurred photos unlock only upon mutual consent and reciprocal approval.
                    </p>
                </div>

                <!-- Pillar 3 -->
                <div class="bg-white dark:bg-slate-800/90 rounded-2xl p-6 border border-slate-200/80 dark:border-slate-700/60 shadow-xs hover:shadow-md hover:border-emerald-500/50 transition">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-950 text-blue-800 dark:text-blue-300 flex items-center justify-center text-2xl mb-4">
                        ⚖️
                    </div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white mb-2 font-heading">Deen-Weighted Algorithm</h3>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                        Matches are ranked using a compatibility engine that weights prayer frequency, madhhab, and halal practice higher than superficial metrics.
                    </p>
                </div>

                <!-- Pillar 4 -->
                <div class="bg-white dark:bg-slate-800/90 rounded-2xl p-6 border border-slate-200/80 dark:border-slate-700/60 shadow-xs hover:shadow-md hover:border-emerald-500/50 transition">
                    <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-950 text-purple-800 dark:text-purple-300 flex items-center justify-center text-2xl mb-4">
                        💬
                    </div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white mb-2 font-heading">Chaperoned Halal Chat</h3>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                        No unsolicited messaging. Communication unlocks only after mutual interest. All chats are protected by anti-harassment scanners.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ================================================================= -->
    <!-- 3. HOW IT WORKS (3 SIMPLE STEPS TO NIKAH)                        -->
    <!-- ================================================================= -->
    <section class="py-16 bg-white dark:bg-slate-950 border-t border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12 space-y-2">
                <span class="text-xs font-bold uppercase tracking-wider text-amber-700 dark:text-amber-400">Streamlined Process</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white font-heading">
                    Three Steps to Your Blessed Union
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                <!-- Step 1 -->
                <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 space-y-3 relative text-center sm:text-left">
                    <span class="text-3xl font-black text-emerald-600/30 dark:text-emerald-400/20 font-mono">01</span>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white font-heading">Create Modest Profile</h3>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                        Define your religious practice, prayer frequency, madhhab, and family values. Photos remain respectfully blurred by default.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 space-y-3 relative text-center sm:text-left">
                    <span class="text-3xl font-black text-amber-600/30 dark:text-amber-400/20 font-mono">02</span>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white font-heading">Involve Your Wali</h3>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                        Add your guardian with custom permissions. Suitors know every communication is conducted with integrity and family approval.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 space-y-3 relative text-center sm:text-left">
                    <span class="text-3xl font-black text-blue-600/30 dark:text-blue-400/20 font-mono">03</span>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white font-heading">Righteous Match &amp; Nikah</h3>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                        Connect with reciprocal interest, exchange family introductions, and complete half your deen with Barakah and peace of mind.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ================================================================= -->
    <!-- 4. RECENT CANDIDATE PROFILES SHOWCASE (BUG FIX & CENTERING)      -->
    <!-- ================================================================= -->
    @if($recentProfiles->isNotEmpty())
        <section class="py-16 bg-slate-50 dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between mb-8 gap-4">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">Verified Community</span>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white font-heading">Recent Candidate Profiles</h2>
                    </div>
                    <a href="{{ route('discovery.index') }}" class="text-xs sm:text-sm font-bold text-emerald-700 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 flex items-center gap-1.5 transition">
                        View all candidates &rarr;
                    </a>
                </div>

                <!-- Responsive Grid: Centered gracefully without awkward gaps -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 justify-center">
                    @foreach($recentProfiles as $profile)
                        @php
                            $loc = collect([$profile->city, $profile->country])->filter()->implode(', ');
                        @endphp
                        <div class="bg-white dark:bg-slate-800/90 rounded-2xl border border-slate-200 dark:border-slate-700/60 overflow-hidden shadow-xs hover:shadow-lg transition group flex flex-col justify-between">
                            <!-- Photo / Avatar Area with Modesty Scrim -->
                            <div class="relative h-52 bg-slate-800 overflow-hidden">
                                @if($profile->primaryPhoto)
                                    <img src="{{ $profile->primaryPhoto->displayUrl() }}" alt="{{ $profile->user->name }}" class="w-full h-full object-cover photo-blur group-hover:scale-105 transition transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-emerald-950 text-white font-bold text-3xl">
                                        {{ substr($profile->user->name, 0, 1) }}
                                    </div>
                                @endif

                                <!-- Ambient Gradient Overlay -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/25 to-transparent"></div>

                                <!-- Top Badges: Modesty Notice & KYC -->
                                <div class="absolute top-3 left-3 right-3 flex items-center justify-between">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-black/60 text-emerald-300 border border-emerald-400/30 backdrop-blur-xs flex items-center gap-1">
                                        🔒 Modest Blur
                                    </span>
                                    @if($profile->user->is_verified)
                                        <span class="bg-blue-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1 shadow-xs">
                                            ✓ Verified
                                        </span>
                                    @endif
                                </div>

                                <!-- Bottom Info Overlay -->
                                <div class="absolute bottom-3 left-3 right-3 text-white space-y-0.5">
                                    <p class="text-[11px] font-semibold text-amber-300">{{ $profile->sect_madhhab ?? 'Practicing Muslim' }}</p>
                                    <h4 class="font-bold text-base truncate">{{ $profile->user->name }}</h4>
                                    <p class="text-xs text-slate-200">
                                        {{ $profile->user->age }} yrs{{ $loc ? ' • ' . $loc : '' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Card Details & Action -->
                            <div class="p-4 space-y-3">
                                <div class="flex items-center justify-between text-xs text-slate-600 dark:text-slate-400">
                                    <span>🕌 {{ str_replace('_', ' ', $profile->prayer_frequency ?? 'Practicing') }}</span>
                                    <span>🎓 {{ Str::limit($profile->profession ?? 'Professional', 16) }}</span>
                                </div>
                                <a href="{{ route('discovery.show', $profile->user_id) }}" class="block text-center w-full py-2.5 rounded-xl bg-slate-100 dark:bg-slate-700/80 hover:bg-emerald-600 dark:hover:bg-emerald-600 text-slate-800 dark:text-slate-100 hover:text-white dark:hover:text-white text-xs font-bold transition">
                                    View Halal Profile &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- ================================================================= -->
    <!-- 5. PRICING SECTION (CLEAN SINGLE MARRIAGE PASS - NO CLUTTER)       -->
    <!-- ================================================================= -->
    <section class="py-20 bg-white dark:bg-slate-950 border-t border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                <span class="text-xs font-bold uppercase tracking-wider text-amber-700 dark:text-amber-400">Fair &amp; Transparent Membership</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight font-heading">
                    Single Marriage Pass — Never Monthly Subscriptions
                </h2>
                <p class="text-sm sm:text-base text-slate-600 dark:text-slate-400 leading-relaxed">
                    Core halal matchmaking and wali chaperoning are always free. Upgrade once for boosted visibility and unlimited discovery until your wedding day.
                </p>
            </div>

            <!-- 3 Pricing Cards: Balanced Heights, Clean Padding, Aligned Buttons -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto items-stretch">
                @foreach($plans as $plan)
                    @php
                        $isFree = $plan->isFree();
                        $isPopular = (bool) $plan->is_popular;
                    @endphp
                    <div class="bg-slate-50 dark:bg-slate-900 rounded-3xl p-6 sm:p-8 {{ $isPopular ? 'border-2 border-emerald-600 dark:border-emerald-500 shadow-xl relative' : 'border border-slate-200 dark:border-slate-800 shadow-xs' }} flex flex-col justify-between h-full">

                        <div>
                            <!-- Clean Top Badge Pill -->
                            <div class="flex items-center justify-between mb-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $isFree ? 'bg-slate-200/80 dark:bg-slate-800 text-slate-700 dark:text-slate-300' : ($isPopular ? 'bg-emerald-100 dark:bg-emerald-950/80 text-emerald-800 dark:text-emerald-300' : 'bg-amber-100 dark:bg-amber-950/80 text-amber-800 dark:text-amber-300') }}">
                                    {{ $isFree ? 'Free Tier' : ($isPopular ? '★ Most Popular' : 'VIP Membership') }}
                                </span>
                                @if($plan->badge_text && !$isPopular)
                                    <span class="text-[10px] font-extrabold uppercase tracking-wider px-2 py-0.5 rounded-full bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                                        {{ $plan->badge_text }}
                                    </span>
                                @endif
                            </div>

                            <h3 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white font-heading">{{ $plan->name }}</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">{{ $plan->description }}</p>

                            <!-- Pricing Value -->
                            <div class="my-6">
                                @if($isFree)
                                    <div class="flex items-baseline gap-1.5">
                                        <span class="text-4xl font-extrabold text-slate-900 dark:text-white font-heading">$0</span>
                                        <span class="text-xs text-slate-500 dark:text-slate-400 font-semibold">/ forever free</span>
                                    </div>
                                    <span class="text-[11px] text-slate-400 dark:text-slate-500 block mt-1">Always available for seekers on basic matching.</span>
                                @else
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-4xl font-extrabold text-slate-900 dark:text-white font-heading">${{ number_format($plan->monthly_price, 2) }}</span>
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300">
                                            Pay 1-Time
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-xs font-bold text-emerald-700 dark:text-emerald-400 mt-1.5">
                                        <span>🛡️</span>
                                        <span>Active Until Confirmed Nikah</span>
                                    </div>
                                    <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">
                                        No recurring monthly charges. Valid until marriage.
                                    </p>
                                @endif
                            </div>

                            <!-- Features List (No Dev Codes) -->
                            <ul class="space-y-2.5 text-xs sm:text-sm text-slate-600 dark:text-slate-300 border-t border-slate-200/80 dark:border-slate-800 pt-5">
                                @if(!empty($plan->features) && count($plan->features) > 0)
                                    @foreach($plan->features as $featureItem)
                                        <li class="flex items-center gap-2">
                                            <span class="text-emerald-600 dark:text-emerald-400 font-bold">✓</span>
                                            <span>{{ preg_replace('/\s*\(FR-[^\)]+\)/', '', $featureItem) }}</span>
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
                                    <li class="flex items-center gap-2">
                                        <span class="text-emerald-600 font-bold">✓</span>
                                        <span>Full Wali guardian chaperone oversight</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="text-emerald-600 font-bold">✓</span>
                                        <span>Default blurred photo privacy &amp; Haya</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="text-emerald-600 font-bold">✓</span>
                                        <span>Advanced deen &amp; madhhab filters</span>
                                    </li>
                                @endif
                            </ul>
                        </div>

                        <!-- CTA Button -->
                        <div class="mt-8 pt-4">
                            @if($isFree)
                                <a href="{{ route('register') }}" class="block text-center w-full py-3.5 rounded-xl bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-800 dark:text-white text-xs font-bold transition">
                                    Get Started Free
                                </a>
                            @else
                                <a href="{{ route('subscription.checkout', $plan->slug) }}" class="block text-center w-full py-3.5 rounded-xl {{ $isPopular ? 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-md shadow-emerald-900/20' : 'bg-slate-900 dark:bg-slate-700 hover:bg-black dark:hover:bg-slate-600 text-white' }} text-xs font-bold transition">
                                    Choose {{ trim(Str::before($plan->name, '(')) ?: $plan->name }} &rarr;
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ================================================================= -->
    <!-- 6. FREQUENTLY ASKED QUESTIONS (FAQ ACCORDION)                    -->
    <!-- ================================================================= -->
    <section class="py-16 bg-slate-50 dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800"
             x-data="{ activeFaq: null }">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="text-center space-y-2">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">Common Inquiries</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white font-heading">
                    Frequently Asked Questions
                </h2>
            </div>

            <div class="space-y-3">
                <!-- FAQ 1 -->
                <div class="rounded-2xl bg-white dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-700/60 overflow-hidden shadow-xs">
                    <button type="button"
                            @click="activeFaq = (activeFaq === 1 ? null : 1)"
                            class="w-full p-5 text-left font-bold text-sm text-slate-900 dark:text-white flex items-center justify-between gap-4 cursor-pointer">
                        <span>How does the Single Marriage Pass work?</span>
                        <span class="text-emerald-600 dark:text-emerald-400 text-lg transition transform" :class="activeFaq === 1 ? 'rotate-180' : ''">&darr;</span>
                    </button>
                    <div x-show="activeFaq === 1" x-collapse class="px-5 pb-5 text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed border-t border-slate-100 dark:border-slate-700/60 pt-3">
                        You pay once for your chosen pass. We do not bill you monthly or automatically renew your card. Your pass remains 100% active and valid until you successfully find your partner and confirm your marriage on the platform.
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="rounded-2xl bg-white dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-700/60 overflow-hidden shadow-xs">
                    <button type="button"
                            @click="activeFaq = (activeFaq === 2 ? null : 2)"
                            class="w-full p-5 text-left font-bold text-sm text-slate-900 dark:text-white flex items-center justify-between gap-4 cursor-pointer">
                        <span>Why are photos blurred by default?</span>
                        <span class="text-emerald-600 dark:text-emerald-400 text-lg transition transform" :class="activeFaq === 2 ? 'rotate-180' : ''">&darr;</span>
                    </button>
                    <div x-show="activeFaq === 2" x-collapse class="px-5 pb-5 text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed border-t border-slate-100 dark:border-slate-700/60 pt-3">
                        In accordance with Islamic principles of modesty (Haya), photos are blurred so candidates can evaluate religiosity, character, and compatibility first. Photos unblur only when both candidates mutually accept an interest request.
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="rounded-2xl bg-white dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-700/60 overflow-hidden shadow-xs">
                    <button type="button"
                            @click="activeFaq = (activeFaq === 3 ? null : 3)"
                            class="w-full p-5 text-left font-bold text-sm text-slate-900 dark:text-white flex items-center justify-between gap-4 cursor-pointer">
                        <span>How does Wali (Guardian) supervision function?</span>
                        <span class="text-emerald-600 dark:text-emerald-400 text-lg transition transform" :class="activeFaq === 3 ? 'rotate-180' : ''">&darr;</span>
                    </button>
                    <div x-show="activeFaq === 3" x-collapse class="px-5 pb-5 text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed border-t border-slate-100 dark:border-slate-700/60 pt-3">
                        Female candidates can link their Wali via email. Walis receive dedicated access to review candidate bios, oversee conversations, and approve or decline courtship requests according to their chosen permission settings.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================================================================= -->
    <!-- 7. BOTTOM CALL TO ACTION BANNER                                  -->
    <!-- ================================================================= -->
    <section class="py-16 bg-emerald-950 text-white relative">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <h2 class="text-2xl sm:text-4xl font-extrabold font-heading tracking-tight">
                Begin Your Journey to a Blessed Nikah
            </h2>
            <p class="text-sm sm:text-base text-emerald-200/80 max-w-xl mx-auto leading-relaxed">
                Experience dignified matchmaking designed with pure intentions, family honor, and Islamic integrity.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-4 pt-2">
                <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-xl font-bold text-sm text-emerald-950 bg-amber-400 hover:bg-amber-300 shadow-xl transition transform hover:-translate-y-0.5">
                    Create Free Profile
                </a>
                <a href="{{ route('about') }}" class="px-8 py-3.5 rounded-xl font-semibold text-sm text-white bg-emerald-900/80 hover:bg-emerald-800 border border-emerald-700/60 transition">
                    Learn More About Us
                </a>
            </div>
        </div>
    </section>

</div>
@endsection

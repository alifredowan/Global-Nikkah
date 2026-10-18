@extends('layouts.app')

@section('title', 'About Us - Global Nikkah | Ethical Islamic Matrimonial Platform')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-200 py-10 sm:py-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">

        <!-- Header Hero -->
        <div class="text-center space-y-4">
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-emerald-100 dark:bg-emerald-950/80 border border-emerald-300 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs font-bold uppercase tracking-wider">
                <span>☪</span>
                <span>Our Sacred Mission</span>
            </div>
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight leading-[1.15]">
                Built for <span class="text-emerald-600 dark:text-emerald-400">Nikah</span>. Grounded in <span class="text-amber-500">Sunnah</span> &amp; Family Barakah.
            </h1>
            <p class="text-sm sm:text-base text-slate-600 dark:text-slate-400 max-w-2xl mx-auto leading-relaxed">
                Global Nikkah was founded to heal modern Muslim matrimonial search by removing dating app toxicity, endless subscription traps, and superficial swiping culture.
            </p>
        </div>

        <!-- Quranic Verse Callout Card -->
        <div class="relative overflow-hidden p-8 sm:p-12 rounded-3xl bg-emerald-950 text-white border border-emerald-800/80 shadow-2xl text-center space-y-4">
            <div class="text-amber-300 text-2xl font-serif">﷽</div>
            <blockquote class="text-base sm:text-xl font-light italic text-emerald-100/90 max-w-3xl mx-auto leading-relaxed">
                &ldquo;And among His signs is that He created for you spouses from among yourselves that you may find tranquility in them; and He placed between you love and mercy.&rdquo;
            </blockquote>
            <p class="text-xs font-bold text-amber-300 tracking-wider uppercase">— Surah Ar-Rum (30:21)</p>
        </div>

        <!-- The Problem vs Our Solution -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Modern Dating Problem -->
            <div class="p-8 rounded-3xl bg-white dark:bg-slate-900 border border-rose-200/60 dark:border-rose-950 shadow-sm space-y-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-50 dark:bg-rose-950 text-rose-700 dark:text-rose-400 text-xs font-bold">
                    <span>❌</span> The Dating App Trap
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white font-heading">
                    Perpetual Subscriptions &amp; Secret Chats
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                    Mainstream dating apps are incentivized to keep you single forever. They charge recurring monthly fees, encourage superficial swiping, exclude families, and normalize illicit private chatting without boundaries.
                </p>
                <ul class="space-y-2 text-xs text-rose-700 dark:text-rose-400/90 font-medium pt-2">
                    <li class="flex items-center gap-2"><span>✕</span> Recurring monthly fees whether you find a partner or not</li>
                    <li class="flex items-center gap-2"><span>✕</span> Unsolicited flirtation and ghosting culture</li>
                    <li class="flex items-center gap-2"><span>✕</span> Zero family or Wali involvement</li>
                </ul>
            </div>

            <!-- Global Nikkah Solution -->
            <div class="p-8 rounded-3xl bg-white dark:bg-slate-900 border border-emerald-300/60 dark:border-emerald-800 shadow-sm space-y-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400 text-xs font-bold">
                    <span>✓</span> The Global Nikkah Difference
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white font-heading">
                    Single Marriage Pass &amp; Shariah Integrity
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                    We designed every feature to help you succeed in completing half your deen. You pay once until your confirmed Nikah, photos remain modest by default, and guardians are honored as dignified chaperones.
                </p>
                <ul class="space-y-2 text-xs text-emerald-700 dark:text-emerald-400 font-medium pt-2">
                    <li class="flex items-center gap-2"><span>✓</span> Pay 1-Time until confirmed Nikah; no monthly renewal traps</li>
                    <li class="flex items-center gap-2"><span>✓</span> Default photo blurring &amp; reciprocal mutual unblurring</li>
                    <li class="flex items-center gap-2"><span>✓</span> Transparent Wali chaperoning and moderator protection</li>
                </ul>
            </div>
        </div>

        <!-- 4 Founding Pillars -->
        <div class="space-y-8">
            <div class="text-center max-w-xl mx-auto space-y-2">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">Our Core Pillars</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white font-heading">
                    How We Protect Your Halal Journey
                </h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-xs hover:border-emerald-500/50 transition space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-950/80 text-emerald-700 dark:text-emerald-400 flex items-center justify-center text-2xl">
                        🛡️
                    </div>
                    <h4 class="font-bold text-base text-slate-900 dark:text-white">Wali Chaperoning</h4>
                    <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                        Granular guardian permissions: view-only oversight, chat unlock approvals, or active proxy assistance.
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-xs hover:border-emerald-500/50 transition space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-950/80 text-amber-700 dark:text-amber-400 flex items-center justify-center text-2xl">
                        🔒
                    </div>
                    <h4 class="font-bold text-base text-slate-900 dark:text-white">Haya by Default</h4>
                    <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                        Modesty protected at all times. Candidates evaluate religiosity, family values, and character before photos unblur.
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-xs hover:border-emerald-500/50 transition space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-950/80 text-blue-700 dark:text-blue-400 flex items-center justify-center text-2xl">
                        ⚖️
                    </div>
                    <h4 class="font-bold text-base text-slate-900 dark:text-white">Deen Compatibility</h4>
                    <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                        Custom matching engine prioritizing prayer consistency, madhhab, Islamic lifestyle, and matrimonial timelines.
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-xs hover:border-emerald-500/50 transition space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-950/80 text-purple-700 dark:text-purple-400 flex items-center justify-center text-2xl">
                        🪪
                    </div>
                    <h4 class="font-bold text-base text-slate-900 dark:text-white">KYC Verified Profiles</h4>
                    <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                        Every serious candidate undergoes manual moderator verification of identity documents to eliminate bots and frauds.
                    </p>
                </div>
            </div>
        </div>

        <!-- Trust Stats Banner -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 border border-slate-200/80 dark:border-slate-800 shadow-sm">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center divide-y md:divide-y-0 md:divide-x divide-slate-100 dark:divide-slate-800">
                <div class="p-4">
                    <div class="text-3xl font-extrabold text-emerald-600 dark:text-emerald-400 font-heading">100%</div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">Shariah Governed</div>
                </div>
                <div class="p-4">
                    <div class="text-3xl font-extrabold text-amber-500 font-heading">Pay 1-Time</div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">Active Until Nikah</div>
                </div>
                <div class="p-4">
                    <div class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading">Verified</div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">Government ID Review</div>
                </div>
                <div class="p-4">
                    <div class="text-3xl font-extrabold text-blue-600 dark:text-blue-400 font-heading">Zero</div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">Dating or Casual Chat</div>
                </div>
            </div>
        </div>

        <!-- Bottom Action -->
        <div class="p-8 sm:p-12 rounded-3xl bg-emerald-950 text-white border border-emerald-800/80 text-center space-y-6 shadow-2xl">
            <div class="max-w-2xl mx-auto space-y-2">
                <h3 class="text-2xl sm:text-3xl font-extrabold font-heading">
                    Begin Your Halal Search Today
                </h3>
                <p class="text-xs sm:text-sm text-emerald-100/90 leading-relaxed">
                    Join thousands of practicing Muslims taking the righteous path toward marriage with peace of mind and family blessings.
                </p>
            </div>
            <div class="flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-xl bg-amber-400 hover:bg-amber-300 text-emerald-950 text-sm font-bold transition shadow-lg shadow-amber-950/20">
                    Create Halal Profile
                </a>
                <a href="{{ route('discovery.index') }}" class="px-8 py-3.5 rounded-xl bg-emerald-900/80 hover:bg-emerald-800 text-white text-sm font-semibold border border-emerald-700/60 transition">
                    Browse Candidates
                </a>
            </div>
        </div>

    </div>
</div>
@endsection

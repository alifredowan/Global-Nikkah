@extends('layouts.app')

@section('title', 'My Matrimonial Dashboard - Global Nikkah')

@section('content')
<div class="min-h-screen bg-slate-50/70 py-8 dark:bg-slate-950">
    <div class="mx-auto max-w-7xl space-y-7 px-4 sm:px-6 lg:px-8">

        <!-- 1. Atmospheric Hero Banner (Vivah Style) -->
        <section class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-950 via-emerald-900 to-slate-950 p-6 sm:p-8 text-white shadow-md border border-emerald-900/40">
            <div class="absolute inset-0 opacity-15 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
            <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-semibold uppercase tracking-wider mb-2">
                        <span>✨</span>
                        <span>Assalamu Alaikum, {{ $user->name }}</span>
                    </div>
                    <h1 class="font-heading text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                        Your path to a blessed union, in one place.
                    </h1>
                    <p class="text-xs sm:text-sm text-emerald-100/80 mt-1.5 leading-relaxed max-w-xl">
                        A serene, dignified overview of your matrimonial readiness, family oversight, and deen-compatible recommendations.
                    </p>
                </div>

                <!-- Readiness Pill & Primary Search CTA -->
                <div class="flex items-center gap-3 shrink-0 flex-wrap sm:flex-nowrap">
                    <div class="flex items-center gap-3 rounded-2xl border border-white/15 bg-white/10 px-4 py-3 backdrop-blur-md shadow-xs">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-400 text-sm font-black text-slate-950 shadow-xs">
                            {{ $profile->completeness_percentage }}%
                        </div>
                        <div>
                            <p class="text-xs font-bold text-white">Profile Readiness</p>
                            <p class="text-[11px] text-emerald-200/80">{{ $profile->completeness_percentage >= 70 ? 'High Discovery Ready' : 'Needs More Details' }}</p>
                        </div>
                    </div>
                    <a href="{{ route('discovery.index') }}" class="px-5 py-3 rounded-2xl bg-amber-400 hover:bg-amber-300 text-slate-950 text-xs font-extrabold shadow-sm transition flex items-center gap-1.5 shrink-0">
                        <span>🔍</span>
                        <span>Find Matches</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- 2. Clean Executive Metrics Grid -->
        <section class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('interests.index') }}" class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800/80 p-5 shadow-xs hover:border-emerald-500/50 smooth-card transition-all duration-300 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Pending Interests</span>
                    <span class="w-9 h-9 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center text-sm shadow-xs border border-amber-200/50 dark:border-amber-800/50">💌</span>
                </div>
                <div class="mt-4 flex items-baseline justify-between">
                    <span class="font-heading text-3xl font-extrabold text-slate-900 dark:text-white">{{ $pendingInterests }}</span>
                    <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">Review requests &rarr;</span>
                </div>
            </a>

            <a href="{{ route('chat.index') }}" class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800/80 p-5 shadow-xs hover:border-emerald-500/50 smooth-card transition-all duration-300 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Messages</span>
                    <span class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-sm shadow-xs border border-emerald-200/50 dark:border-emerald-800/50">💬</span>
                </div>
                <div class="mt-4 flex items-baseline justify-between">
                    <span class="font-heading text-3xl font-extrabold text-slate-900 dark:text-white">{{ $unreadMessages }}</span>
                    <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">{{ $conversationCount }} Active chats &rarr;</span>
                </div>
            </a>

            <a href="{{ route('notifications.index') }}" class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800/80 p-5 shadow-xs hover:border-emerald-500/50 smooth-card transition-all duration-300 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Updates</span>
                    <span class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-sm shadow-xs border border-blue-200/50 dark:border-blue-800/50">🔔</span>
                </div>
                <div class="mt-4 flex items-baseline justify-between">
                    <span class="font-heading text-3xl font-extrabold text-slate-900 dark:text-white">{{ $unreadNotifications }}</span>
                    <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">Activity &rarr;</span>
                </div>
            </a>

            <a href="{{ route('discovery.index') }}" class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800/80 p-5 shadow-xs hover:border-emerald-500/50 smooth-card transition-all duration-300 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Daily Views</span>
                    <span class="w-9 h-9 rounded-xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center text-sm shadow-xs border border-purple-200/50 dark:border-purple-800/50">👁️</span>
                </div>
                <div class="mt-4 flex items-baseline justify-between">
                    <span class="font-heading text-3xl font-extrabold text-slate-900 dark:text-white">{{ $quotaStats['views_limit'] === 'Unlimited' ? '∞' : $quotaStats['views_remaining'] }}</span>
                    <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">{{ $quotaStats['views_limit'] === 'Unlimited' ? 'Unlimited Access' : 'Views remaining' }} &rarr;</span>
                </div>
            </a>
        </section>

        <!-- 3. Featured Section: Today's Compatible Introductions -->
        <section class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800/80 p-6 sm:p-7 shadow-xs space-y-5">
            <div class="flex items-center justify-between">
                <div>
                    <div class="inline-flex items-center gap-1.5 text-[10px] font-extrabold uppercase tracking-widest text-emerald-600 dark:text-emerald-400">
                        <span>✨</span>
                        <span>Curated Introductions</span>
                    </div>
                    <h2 class="font-heading text-xl font-extrabold text-slate-900 dark:text-white mt-0.5">
                        Today’s Compatible Matches
                    </h2>
                </div>
                <a href="{{ route('discovery.index') }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 dark:text-emerald-400 flex items-center gap-1">
                    <span>View All Matches</span>
                    <span>&rarr;</span>
                </a>
            </div>

            @if($dailyMatches->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    @foreach($dailyMatches as $match)
                        <div class="rounded-2xl border border-slate-200/80 dark:border-slate-800/80 shadow-xs hover:border-emerald-500/50 smooth-card overflow-hidden flex flex-col justify-between bg-slate-50/50 dark:bg-slate-900 transition-all duration-300">
                            <div>
                                <!-- Photo Container with Vivah Zoom & Gradient -->
                                <div class="relative h-48 bg-slate-900 overflow-hidden group">
                                    @if($match->primaryPhoto)
                                        <img src="{{ $match->primaryPhoto->displayUrl() }}" alt="{{ $match->user->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500 ease-out photo-blur" loading="lazy" decoding="async">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-emerald-950 to-slate-900 text-amber-300 font-bold text-3xl group-hover:scale-105 transition duration-500 ease-out">
                                            {{ substr($match->user->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/25 to-transparent"></div>

                                    <!-- Top Match Badge -->
                                    <div class="absolute top-2.5 right-2.5">
                                        <span class="bg-emerald-950/90 backdrop-blur-xs text-emerald-200 px-2.5 py-0.5 rounded-full border border-emerald-400/40 text-[10px] font-extrabold shadow-xs">
                                            ✨ {{ $match->compatibility_score }}% Match
                                        </span>
                                    </div>

                                    <!-- Bottom Info Overlay -->
                                    <div class="absolute bottom-2.5 inset-x-3 text-white">
                                        <h3 class="font-heading font-bold text-sm truncate text-white drop-shadow-sm">{{ $match->user->name }}</h3>
                                        <p class="text-[11px] text-amber-200/95 font-medium truncate mt-0.5 drop-shadow-xs">
                                            {{ $match->user->age }} yrs • {{ $match->city ?: 'Location Flexible' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Mini Specs Grid -->
                                <div class="p-3">
                                    <div class="grid grid-cols-2 gap-1.5 text-[10px] bg-white dark:bg-slate-800/60 p-2 rounded-xl border border-slate-100 dark:border-slate-800">
                                        <div class="min-w-0">
                                            <span class="text-slate-400 block text-[9px] uppercase font-bold">Madhhab</span>
                                            <span class="font-semibold text-slate-800 dark:text-slate-200 truncate block">{{ $match->sect_madhhab ?: 'Muslim' }}</span>
                                        </div>
                                        <div class="min-w-0">
                                            <span class="text-slate-400 block text-[9px] uppercase font-bold">Wali</span>
                                            <span class="font-semibold text-emerald-600 dark:text-emerald-400 truncate block">{{ $match->wali_required ? 'Linked' : 'Self' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-3 pt-0">
                                <a href="{{ route('discovery.show', $match->user_id) }}" class="w-full text-center py-2 rounded-xl bg-slate-900 dark:bg-slate-800 hover:bg-emerald-700 dark:hover:bg-emerald-600 text-white text-xs font-bold transition shadow-xs flex items-center justify-center gap-1">
                                    <span>View Biodata</span>
                                    <span class="text-[10px]">&rarr;</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-slate-200 dark:border-slate-800 p-8 text-center bg-slate-50/50 dark:bg-slate-800/30">
                    <p class="text-xs font-semibold text-slate-600 dark:text-slate-300">Complete more of your profile preferences to unlock daily deen compatibility matches.</p>
                    <a href="{{ route('profile.edit') }}" class="mt-2.5 inline-flex items-center gap-1 text-xs font-bold text-emerald-600 hover:underline">
                        <span>Edit Preferences</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            @endif
        </section>

        <!-- 4. Two-Column Layout: Action & Readiness vs Trust & Governance -->
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Left Column: Priority Action & Recent Interests -->
            <div class="space-y-6">

                <!-- Recommended Next Action (Clean Card) -->
                <div class="rounded-3xl border border-amber-200/80 bg-gradient-to-br from-amber-50/60 to-white dark:border-amber-900/40 dark:from-amber-950/20 dark:to-slate-900 p-6 shadow-xs space-y-4">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <span class="text-[10px] font-extrabold uppercase tracking-widest text-amber-800 dark:text-amber-300 flex items-center gap-1.5">
                                <span>🎯</span> Recommended Next Priority
                            </span>
                            <h2 class="mt-1 font-heading text-lg font-extrabold text-slate-900 dark:text-white">
                                {{ $nextAction['title'] }}
                            </h2>
                            <p class="mt-1.5 text-xs leading-relaxed text-slate-600 dark:text-slate-300">
                                {{ $nextAction['description'] }}
                            </p>
                        </div>
                        <a href="{{ route($nextAction['route']) }}" class="shrink-0 rounded-xl bg-emerald-700 hover:bg-emerald-800 px-4 py-2.5 text-xs font-bold text-white shadow-xs transition flex items-center gap-1">
                            <span>{{ $nextAction['label'] }}</span>
                            <span>&rarr;</span>
                        </a>
                    </div>

                    <div>
                        <div class="flex items-center justify-between text-[11px] mb-1.5 font-semibold text-slate-600 dark:text-slate-400">
                            <span>Profile Completeness</span>
                            <span class="font-bold text-amber-700 dark:text-amber-400">{{ $profile->completeness_percentage }}%</span>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-slate-200 dark:bg-slate-700">
                            <div class="h-full rounded-full bg-gradient-to-r from-amber-500 to-emerald-500 transition-all duration-500" style="width: {{ $profile->completeness_percentage }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Recent Interest Requests -->
                <div class="rounded-3xl border border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-slate-900 p-6 shadow-xs space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="font-heading text-base font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                            <span>💌</span>
                            <span>Recent Introduction Requests</span>
                        </h2>
                        <a href="{{ route('interests.index') }}" class="text-xs font-bold text-emerald-700 dark:text-emerald-400 hover:underline">
                            View All &rarr;
                        </a>
                    </div>

                    <div class="space-y-2.5">
                        @forelse($recentInterests as $interest)
                            <a href="{{ route('discovery.show', $interest->sender_id) }}" class="flex items-center gap-3.5 rounded-2xl border border-slate-100 dark:border-slate-800 p-3 transition hover:border-emerald-300 hover:bg-slate-50 dark:hover:bg-slate-800/60 smooth-card">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-emerald-950 text-sm font-bold text-amber-300 border border-slate-200 dark:border-slate-700">
                                    {{ substr($interest->sender->name, 0, 1) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-xs font-bold text-slate-900 dark:text-white">{{ $interest->sender->name }}</p>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Received {{ $interest->created_at->diffForHumans() }}</p>
                                </div>
                                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 shrink-0">Review &rarr;</span>
                            </a>
                        @empty
                            <p class="rounded-2xl bg-slate-50/70 dark:bg-slate-800/40 p-5 text-center text-xs text-slate-500 dark:text-slate-400">
                                No pending introduction requests at this time.
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Column: Trust & Family + Setup Checklist -->
            <div class="space-y-6">

                <!-- Trust & Family Oversight (Vivah Governance Style) -->
                <div class="rounded-3xl border border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-slate-900 p-6 shadow-xs space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="font-heading text-base font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                            <span>🛡️</span>
                            <span>Trust & family</span>
                        </h2>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 border border-emerald-300/40">
                            Shariah Privacy
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <!-- Verification Pill -->
                        <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 space-y-1">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Identity KYC</span>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-800 dark:text-slate-200">
                                    {{ $user->is_verified ? 'Verified Seeker' : ($verificationRequest?->status === 'pending' ? 'Review in Progress' : 'Unverified') }}
                                </span>
                                <span class="w-2 h-2 rounded-full {{ $user->is_verified ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                            </div>
                        </div>

                        <!-- Guardian (Wali) Pill -->
                        <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 space-y-1">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Guardian (Wali)</span>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate">
                                    {{ $activeWaliLink ? 'Guardian Connected' : 'Not Linked' }}
                                </span>
                                <span class="w-2 h-2 rounded-full {{ $activeWaliLink ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ $activeWaliLink ? route('wali.dashboard') : route('wali.link') }}" class="block w-full py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700/80 text-center text-xs font-bold text-slate-700 dark:text-slate-200 transition">
                        {{ $activeWaliLink ? 'Open Family Oversight Console &rarr;' : '🛡️ Connect Your Guardian (Wali) &rarr;' }}
                    </a>
                </div>

                <!-- Profile Setup Progress Checklist -->
                <div class="rounded-3xl border border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-slate-900 p-6 shadow-xs space-y-4">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-600 dark:text-emerald-400">
                                Match Readiness
                            </p>
                            <h2 class="mt-1 font-heading text-lg font-extrabold text-slate-900 dark:text-white">
                                Profile setup
                            </h2>
                        </div>
                        <span class="rounded-full bg-emerald-100 dark:bg-emerald-950 px-2.5 py-1 text-[10px] font-extrabold text-emerald-800 dark:text-emerald-300 border border-emerald-300/40">
                            {{ $completedOnboardingSteps }}/{{ count($onboardingSteps) }} Completed
                        </span>
                    </div>

                    <div class="space-y-2">
                        @foreach($onboardingSteps as $step)
                            <a href="{{ route($step['route']) }}" class="flex items-center justify-between p-2.5 rounded-xl transition hover:bg-slate-50 dark:hover:bg-slate-800/60 group">
                                <div class="flex items-center gap-3 min-w-0">
                                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full text-[10px] font-black {{ $step['complete'] ? 'bg-emerald-500 text-white' : 'border-2 border-slate-300 dark:border-slate-600 text-transparent' }}">
                                        ✓
                                    </span>
                                    <span class="text-xs font-bold truncate {{ $step['complete'] ? 'text-slate-400 line-through' : 'text-slate-800 dark:text-slate-200' }}">
                                        {{ $step['label'] }}
                                    </span>
                                </div>
                                @if(!$step['complete'])
                                    <span class="text-xs font-bold text-emerald-600 group-hover:translate-x-0.5 transition">&rarr;</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Saved Searches Quick Workspace -->
                @if($savedSearches->isNotEmpty())
                    <div class="rounded-3xl border border-emerald-100 dark:border-emerald-900/40 bg-emerald-50/50 dark:bg-emerald-950/20 p-5 space-y-3">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-900 dark:text-emerald-300 flex items-center gap-1.5">
                                <span>⌕</span> Saved Match Filters
                            </h3>
                            <a href="{{ route('discovery.index') }}" class="text-[11px] font-bold text-emerald-700 dark:text-emerald-400 hover:underline">
                                Search All &rarr;
                            </a>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($savedSearches as $savedSearch)
                                <a href="{{ route('discovery.index', collect($savedSearch->filters ?? [])->filter(fn ($value) => filled($value))->all()) }}" class="px-3 py-1.5 rounded-xl bg-white dark:bg-slate-800 border border-emerald-200/80 dark:border-emerald-800/60 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:border-emerald-500 shadow-xs transition">
                                    {{ $savedSearch->title }} &rarr;
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </section>

    </div>
</div>
@endsection

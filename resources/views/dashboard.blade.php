@extends('layouts.app')

@section('title', 'My Matrimonial Dashboard - Global Nikkah')

@section('content')
<div class="min-h-screen bg-slate-50 py-8 dark:bg-slate-950">
    <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
        <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-950 via-emerald-900 to-slate-950 p-6 text-white shadow-xl sm:p-8">
            <div class="absolute -right-16 -top-24 h-64 w-64 rounded-full bg-emerald-400/10 blur-3xl"></div>
            <div class="absolute -bottom-32 left-1/3 h-72 w-72 rounded-full bg-amber-400/10 blur-3xl"></div>
            <div class="relative flex flex-col gap-7 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-2xl">
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-emerald-300">Assalamu Alaikum, {{ $user->name }}</p>
                    <h1 class="mt-3 font-heading text-3xl font-extrabold tracking-tight sm:text-4xl">Your path to a blessed union, in one place.</h1>
                    <p class="mt-3 max-w-xl text-sm leading-relaxed text-emerald-100/80">A calm overview of your profile readiness, family activity, and today’s deen-weighted introductions.</p>
                </div>
                <div class="flex shrink-0 items-center gap-3 rounded-2xl border border-white/10 bg-white/10 p-3 backdrop-blur-sm">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-400 text-lg font-black text-emerald-950">{{ $profile->completeness_percentage }}%</div>
                    <div>
                        <p class="text-xs font-bold text-white">Profile readiness</p>
                        <p class="mt-0.5 text-[11px] text-emerald-200/75">{{ $profile->completeness_percentage >= 80 ? 'Strong family-facing profile' : 'A few details will improve your reach' }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid grid-cols-2 gap-3 sm:grid-cols-4">
            <a href="{{ route('interests.index') }}" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-xs transition hover:-translate-y-0.5 hover:border-emerald-300 hover:shadow-md dark:border-slate-800 dark:bg-slate-900 dark:hover:border-emerald-800">
                <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">Pending interests</p>
                <p class="mt-2 font-heading text-2xl font-extrabold text-slate-900 dark:text-white">{{ $pendingInterests }}</p>
                <p class="mt-1 text-[10px] font-bold text-emerald-700 dark:text-emerald-400">Review requests →</p>
            </a>
            <a href="{{ route('chat.index') }}" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-xs transition hover:-translate-y-0.5 hover:border-emerald-300 hover:shadow-md dark:border-slate-800 dark:bg-slate-900 dark:hover:border-emerald-800">
                <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">Unread messages</p>
                <p class="mt-2 font-heading text-2xl font-extrabold text-slate-900 dark:text-white">{{ $unreadMessages }}</p>
                <p class="mt-1 text-[10px] font-bold text-emerald-700 dark:text-emerald-400">{{ $conversationCount }} conversations →</p>
            </a>
            <a href="{{ route('notifications.index') }}" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-xs transition hover:-translate-y-0.5 hover:border-emerald-300 hover:shadow-md dark:border-slate-800 dark:bg-slate-900 dark:hover:border-emerald-800">
                <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">Unread updates</p>
                <p class="mt-2 font-heading text-2xl font-extrabold text-slate-900 dark:text-white">{{ $unreadNotifications }}</p>
                <p class="mt-1 text-[10px] font-bold text-emerald-700 dark:text-emerald-400">View notifications →</p>
            </a>
            <a href="{{ route('discovery.index') }}" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-xs transition hover:-translate-y-0.5 hover:border-emerald-300 hover:shadow-md dark:border-slate-800 dark:bg-slate-900 dark:hover:border-emerald-800">
                <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">Daily views</p>
                <p class="mt-2 font-heading text-2xl font-extrabold text-slate-900 dark:text-white">{{ $quotaStats['views_limit'] === 'Unlimited' ? '∞' : $quotaStats['views_remaining'] }}</p>
                <p class="mt-1 text-[10px] font-bold text-emerald-700 dark:text-emerald-400">{{ $quotaStats['views_limit'] === 'Unlimited' ? 'Unlimited access' : 'Views remaining' }}</p>
            </a>
        </section>

        <section class="grid gap-6 lg:grid-cols-[1.25fr_0.75fr]">
            <div class="space-y-6">
                <div class="rounded-3xl border border-amber-200/80 bg-gradient-to-br from-amber-50 to-white p-6 shadow-xs dark:border-amber-900/50 dark:from-amber-950/30 dark:to-slate-900">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-amber-700 dark:text-amber-300">Recommended next step</p>
                            <h2 class="mt-1 font-heading text-xl font-extrabold text-slate-900 dark:text-white">{{ $nextAction['title'] }}</h2>
                            <p class="mt-2 max-w-xl text-xs leading-relaxed text-slate-600 dark:text-slate-300">{{ $nextAction['description'] }}</p>
                        </div>
                        <a href="{{ route($nextAction['route']) }}" class="shrink-0 rounded-xl bg-emerald-700 px-4 py-3 text-center text-xs font-bold text-white shadow-md shadow-emerald-900/15 transition hover:bg-emerald-800">{{ $nextAction['label'] }} →</a>
                    </div>
                    <div class="mt-5 h-2 overflow-hidden rounded-full bg-amber-100 dark:bg-amber-950/80"><div class="h-full rounded-full bg-amber-500 transition-all" style="width: {{ $profile->completeness_percentage }}%"></div></div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xs dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-emerald-700 dark:text-emerald-300">Selected for you</p>
                            <h2 class="mt-1 font-heading text-xl font-extrabold text-slate-900 dark:text-white">Today’s compatible matches</h2>
                        </div>
                        <a href="{{ route('discovery.index') }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 dark:text-emerald-400">View all →</a>
                    </div>
                    @if($dailyMatches->isNotEmpty())
                        <div class="mt-5 grid gap-3 sm:grid-cols-2">
                            @foreach($dailyMatches as $match)
                                <a href="{{ route('discovery.show', $match->user_id) }}" class="group flex items-center gap-3 rounded-2xl border border-slate-100 p-3 transition hover:border-emerald-200 hover:bg-emerald-50/50 dark:border-slate-800 dark:hover:border-emerald-900 dark:hover:bg-emerald-950/30">
                                    <div class="relative h-12 w-12 shrink-0 overflow-hidden rounded-xl bg-emerald-950">
                                        @if($match->primaryPhoto)
                                            <img src="{{ $match->primaryPhoto->displayUrl() }}" alt="{{ $match->user->name }}" class="photo-blur h-full w-full object-cover">
                                        @else
                                            <span class="flex h-full items-center justify-center font-bold text-amber-300">{{ substr($match->user->name, 0, 1) }}</span>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center justify-between gap-2"><p class="truncate text-xs font-bold text-slate-900 group-hover:text-emerald-700 dark:text-white dark:group-hover:text-emerald-300">{{ $match->user->name }}</p><span class="shrink-0 rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-extrabold text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">{{ $match->compatibility_score }}%</span></div>
                                        <p class="mt-1 truncate text-[11px] text-slate-500 dark:text-slate-400">{{ $match->user->age }} yrs · {{ $match->city ?: 'Location private' }}</p>
                                        <p class="mt-1 truncate text-[10px] font-semibold text-emerald-700 dark:text-emerald-400">{{ $match->sect_madhhab ?: 'Muslim' }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="mt-5 rounded-2xl border border-dashed border-slate-200 p-6 text-center dark:border-slate-700"><p class="text-xs font-semibold text-slate-600 dark:text-slate-300">Complete more of your profile to unlock stronger recommendations.</p></div>
                    @endif
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xs dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between"><h2 class="font-heading text-lg font-extrabold text-slate-900 dark:text-white">Trust & family</h2><span class="text-lg">🛡️</span></div>
                    <div class="mt-5 space-y-3">
                        <div class="flex items-center justify-between rounded-xl bg-slate-50 p-3 dark:bg-slate-800/60"><span class="text-xs font-semibold text-slate-600 dark:text-slate-300">Identity verification</span><span class="rounded-full px-2 py-1 text-[10px] font-bold {{ $user->is_verified ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300' }}">{{ $user->is_verified ? 'Verified' : ($verificationRequest?->status === 'pending' ? 'In review' : 'Action needed') }}</span></div>
                        <div class="flex items-center justify-between rounded-xl bg-slate-50 p-3 dark:bg-slate-800/60"><span class="text-xs font-semibold text-slate-600 dark:text-slate-300">Guardian connection</span><span class="rounded-full px-2 py-1 text-[10px] font-bold {{ $activeWaliLink ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-slate-200 text-slate-600 dark:bg-slate-700 dark:text-slate-300' }}">{{ $activeWaliLink ? 'Connected' : 'Not linked' }}</span></div>
                    </div>
                    <a href="{{ $activeWaliLink ? route('wali.dashboard') : route('wali.link') }}" class="mt-4 block rounded-xl border border-slate-200 px-4 py-2.5 text-center text-xs font-bold text-slate-700 transition hover:border-emerald-300 hover:text-emerald-700 dark:border-slate-700 dark:text-slate-200 dark:hover:border-emerald-700 dark:hover:text-emerald-300">{{ $activeWaliLink ? 'Open family dashboard' : 'Connect your Wali' }}</a>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xs dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-start justify-between gap-4">
                        <div><p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-emerald-700 dark:text-emerald-300">Getting match-ready</p><h2 class="mt-1 font-heading text-lg font-extrabold text-slate-900 dark:text-white">Profile setup</h2></div>
                        <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-[10px] font-extrabold text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">{{ $completedOnboardingSteps }}/{{ count($onboardingSteps) }}</span>
                    </div>
                    <div class="mt-4 h-2 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800"><div class="h-full rounded-full bg-emerald-500" style="width: {{ ($completedOnboardingSteps / max(1, count($onboardingSteps))) * 100 }}%"></div></div>
                    <div class="mt-4 space-y-3">
                        @foreach($onboardingSteps as $step)
                            <a href="{{ route($step['route']) }}" class="flex items-start gap-3 rounded-xl p-1.5 transition hover:bg-slate-50 dark:hover:bg-slate-800">
                                <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full text-[10px] font-black {{ $step['complete'] ? 'bg-emerald-500 text-white' : 'border-2 border-slate-300 text-transparent dark:border-slate-600' }}">✓</span>
                                <span class="min-w-0 flex-1"><span class="block text-xs font-bold {{ $step['complete'] ? 'text-slate-500 line-through dark:text-slate-400' : 'text-slate-900 dark:text-white' }}">{{ $step['label'] }}</span><span class="mt-0.5 block text-[10px] leading-relaxed text-slate-500 dark:text-slate-400">{{ $step['complete'] ? 'Completed' : $step['description'] }}</span></span>
                                @if(!$step['complete'])<span class="text-xs font-bold text-emerald-600">→</span>@endif
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xs dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between"><h2 class="font-heading text-lg font-extrabold text-slate-900 dark:text-white">Recent interests</h2><a href="{{ route('interests.index') }}" class="text-xs font-bold text-emerald-700 dark:text-emerald-400">All →</a></div>
                    <div class="mt-4 space-y-3">
                        @forelse($recentInterests as $interest)
                            <a href="{{ route('discovery.show', $interest->sender_id) }}" class="flex items-center gap-3 rounded-xl p-2 transition hover:bg-slate-50 dark:hover:bg-slate-800">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-emerald-950 text-sm font-bold text-amber-300">{{ substr($interest->sender->name, 0, 1) }}</div>
                                <div class="min-w-0 flex-1"><p class="truncate text-xs font-bold text-slate-900 dark:text-white">{{ $interest->sender->name }}</p><p class="text-[10px] text-slate-500 dark:text-slate-400">Sent {{ $interest->created_at->diffForHumans() }}</p></div>
                                <span class="text-xs text-emerald-600">→</span>
                            </a>
                        @empty
                            <p class="rounded-xl bg-slate-50 p-4 text-xs leading-relaxed text-slate-500 dark:bg-slate-800/60 dark:text-slate-400">No pending interests yet. Your next thoughtful introduction may be waiting in discovery.</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-3xl border border-emerald-100 bg-emerald-50/70 p-6 dark:border-emerald-900/50 dark:bg-emerald-950/30">
                    <div class="flex items-center justify-between"><h2 class="font-heading text-lg font-extrabold text-slate-900 dark:text-white">Saved searches</h2><a href="{{ route('discovery.index') }}" class="text-xs font-bold text-emerald-700 dark:text-emerald-400">Manage →</a></div>
                    <div class="mt-4 space-y-2">
                        @forelse($savedSearches as $savedSearch)
                            <a href="{{ route('discovery.index', collect($savedSearch->filters ?? [])->filter(fn ($value) => filled($value))->all()) }}" class="flex items-center justify-between rounded-xl bg-white/70 px-3 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-white dark:bg-slate-900/60 dark:text-slate-200 dark:hover:bg-slate-900"><span class="truncate">{{ $savedSearch->title }}</span><span class="ml-2 shrink-0 text-emerald-600">→</span></a>
                        @empty
                            <p class="text-xs leading-relaxed text-slate-600 dark:text-slate-300">Save a search in discovery to keep your family’s preferences ready.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

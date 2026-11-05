@extends('layouts.app')

@section('title', 'My Matrimonial Dashboard - Global Nikkah')

@section('content')
<div class="min-h-screen bg-[var(--bg-canvas)] py-8">
    <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

        <!-- 1. Dignified Hero Ledger Banner (Curved Floating Style) -->
        <section class="card-ledger pattern-header rounded-2xl sm:rounded-3xl p-6 sm:p-8 bg-[var(--bg-surface)] border-[var(--border-default)] shadow-xs">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="max-w-2xl space-y-2">
                    <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-[var(--radius-sm)] bg-[var(--accent-subtle)] text-[var(--accent-text)] text-xs font-semibold">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <span>Assalamu Alaikum, {{ $user->name }}</span>
                    </div>
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-[var(--text-primary)]">
                        Your path to a blessed union, in one place.
                    </h1>
                    <p class="text-xs sm:text-sm text-[var(--text-secondary)] leading-relaxed max-w-xl">
                        A serene, dignified overview of your matrimonial readiness, family oversight, and deen-compatible recommendations.
                    </p>
                </div>

                <!-- Readiness Pill & Primary Search CTA -->
                <div class="flex items-center gap-3 shrink-0 flex-wrap sm:flex-nowrap">
                    <div class="flex items-center gap-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] px-4 py-3 shadow-xs">
                        <div class="flex h-10 w-10 items-center justify-center rounded-[var(--radius-sm)] bg-[var(--accent-subtle)] border border-[var(--accent-border)] text-sm font-bold text-[var(--accent-text)]">
                            {{ $profile->completeness_percentage }}%
                        </div>
                        <div>
                            <p class="text-xs font-bold text-[var(--text-primary)]">Profile Readiness</p>
                            <p class="text-[11px] text-[var(--text-muted)]">{{ $profile->completeness_percentage >= 70 ? 'High Discovery Ready' : 'Needs More Details' }}</p>
                        </div>
                    </div>
                    <a href="{{ route('discovery.index') }}" class="btn-primary text-xs font-bold px-5">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        <span>Find Matches</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- 2. Clean Executive Metrics Grid -->
        <section class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('interests.index') }}" class="card-ledger p-4 bg-[var(--bg-surface)] flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-[var(--text-muted)] uppercase tracking-wider">Pending Interests</span>
                    <span class="w-8 h-8 rounded-[var(--radius-sm)] bg-[var(--secondary-subtle)] text-[var(--secondary-text)] flex items-center justify-center text-sm border border-[var(--secondary-border)]">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                    </span>
                </div>
                <div class="mt-3 flex items-baseline justify-between">
                    <span class="text-2xl font-bold text-[var(--text-primary)]">{{ $pendingInterests }}</span>
                    <span class="text-xs font-semibold text-[var(--accent-text)]">Review requests &rarr;</span>
                </div>
            </a>

            <a href="{{ route('chat.index') }}" class="card-ledger p-4 bg-[var(--bg-surface)] flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-[var(--text-muted)] uppercase tracking-wider">Messages</span>
                    <span class="w-8 h-8 rounded-[var(--radius-sm)] bg-[var(--accent-subtle)] text-[var(--accent-text)] flex items-center justify-center text-sm border border-[var(--accent-border)]">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    </span>
                </div>
                <div class="mt-3 flex items-baseline justify-between">
                    <span class="text-2xl font-bold text-[var(--text-primary)]">{{ $unreadMessages }}</span>
                    <span class="text-xs font-semibold text-[var(--accent-text)]">{{ $conversationCount }} Active chats &rarr;</span>
                </div>
            </a>

            <a href="{{ route('notifications.index') }}" class="card-ledger p-4 bg-[var(--bg-surface)] flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-[var(--text-muted)] uppercase tracking-wider">Updates</span>
                    <span class="w-8 h-8 rounded-[var(--radius-sm)] bg-[var(--bg-subtle)] text-[var(--text-primary)] flex items-center justify-center text-sm border border-[var(--border-default)]">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                    </span>
                </div>
                <div class="mt-3 flex items-baseline justify-between">
                    <span class="text-2xl font-bold text-[var(--text-primary)]">{{ $unreadNotifications }}</span>
                    <span class="text-xs font-semibold text-[var(--accent-text)]">Activity &rarr;</span>
                </div>
            </a>

            <a href="{{ route('discovery.index') }}" class="card-ledger p-4 bg-[var(--bg-surface)] flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-[var(--text-muted)] uppercase tracking-wider">Daily Views</span>
                    <span class="w-8 h-8 rounded-[var(--radius-sm)] bg-[var(--bg-subtle)] text-[var(--text-primary)] flex items-center justify-center text-sm border border-[var(--border-default)]">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                    </span>
                </div>
                <div class="mt-3 flex items-baseline justify-between">
                    <span class="text-2xl font-bold text-[var(--text-primary)]">{{ $quotaStats['views_limit'] === 'Unlimited' ? '∞' : $quotaStats['views_remaining'] }}</span>
                    <span class="text-xs font-semibold text-[var(--accent-text)]">{{ $quotaStats['views_limit'] === 'Unlimited' ? 'Unlimited Access' : 'Views remaining' }} &rarr;</span>
                </div>
            </a>
        </section>

        <!-- 3. Featured Section: Today's Compatible Introductions -->
        <section class="card-ledger p-5 sm:p-6 bg-[var(--bg-surface)] space-y-4">
            <div class="flex items-center justify-between border-b border-[var(--border-subtle)] pb-3">
                <div>
                    <h2 class="text-sm font-bold text-[var(--text-primary)] uppercase tracking-wider">
                        Today’s Compatible Matches
                    </h2>
                    <p class="text-xs text-[var(--text-muted)] mt-0.5">Algorithmic suggestions aligned with your deen and life priorities.</p>
                </div>
                <a href="{{ route('discovery.index') }}" class="text-xs font-semibold text-[var(--accent-text)] hover:underline flex items-center gap-1">
                    <span>View All Matches</span>
                    <span>&rarr;</span>
                </a>
            </div>

            @if($dailyMatches->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($dailyMatches as $match)
                        <article class="card-ledger flex flex-col justify-between overflow-hidden">
                            <div>
                                <div class="relative h-44 bg-[var(--neutral-900)] overflow-hidden">
                                    @if($match->primaryPhoto)
                                        <img src="{{ $match->primaryPhoto->displayUrl() }}" alt="{{ $match->user->name }}" class="w-full h-full object-cover photo-blur" loading="lazy">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-[var(--neutral-800)] text-white font-bold text-2xl">
                                            {{ substr($match->user->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

                                    <div class="absolute top-2 right-2">
                                        <span class="px-2 py-0.5 rounded-[var(--radius-sm)] text-[10px] font-bold bg-black/70 text-white border border-white/10">
                                            {{ $match->compatibility_score }}% Match
                                        </span>
                                    </div>

                                    <div class="absolute bottom-2.5 inset-x-3 text-white">
                                        <h3 class="text-sm font-bold truncate">{{ $match->user->name }}</h3>
                                        <p class="text-[11px] text-[var(--neutral-300)] truncate mt-0.5">
                                            {{ $match->user->age }} yrs &bull; {{ $match->city ?: 'District flexible' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="p-3 border-t border-[var(--border-subtle)] text-[11px] grid grid-cols-2 gap-2">
                                    <div>
                                        <span class="text-[var(--text-muted)] block text-[10px] uppercase font-semibold">Madhhab</span>
                                        <span class="font-medium text-[var(--text-primary)] truncate block">{{ $match->sect_madhhab ?: 'Muslim' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-[var(--text-muted)] block text-[10px] uppercase font-semibold">Wali</span>
                                        <span class="font-medium text-[var(--text-primary)] truncate block">{{ $match->wali_required ? 'Linked' : 'Self' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="p-3 pt-0">
                                <a href="{{ route('discovery.show', $match->user_id) }}" class="btn-secondary w-full text-xs font-semibold py-1.5">
                                    <span>View Biodata</span>
                                    <span class="text-[10px]">&rarr;</span>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="card-ledger p-8 text-center bg-[var(--bg-subtle)]">
                    <p class="text-xs font-semibold text-[var(--text-secondary)]">Complete more of your profile preferences to unlock daily deen compatibility matches.</p>
                    <a href="{{ route('profile.edit') }}" class="mt-2 inline-flex items-center gap-1 text-xs font-semibold text-[var(--accent-text)] hover:underline">
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

                <!-- Recommended Next Action (Clean Ledger) -->
                <div class="card-ledger p-5 bg-[var(--bg-surface)] space-y-3">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-[var(--accent-text)]">
                                Recommended Next Priority
                            </span>
                            <h2 class="mt-0.5 text-base font-bold text-[var(--text-primary)]">
                                {{ $nextAction['title'] }}
                            </h2>
                            <p class="mt-1 text-xs leading-relaxed text-[var(--text-secondary)]">
                                {{ $nextAction['description'] }}
                            </p>
                        </div>
                        <a href="{{ route($nextAction['route']) }}" class="btn-primary text-xs font-bold py-2 shrink-0">
                            <span>{{ $nextAction['label'] }}</span>
                            <span>&rarr;</span>
                        </a>
                    </div>

                    <div class="border-t border-[var(--border-subtle)] pt-3">
                        <div class="flex items-center justify-between text-[11px] mb-1 font-semibold text-[var(--text-secondary)]">
                            <span>Profile Completeness</span>
                            <span class="font-bold text-[var(--accent-text)]">{{ $profile->completeness_percentage }}%</span>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-[var(--bg-muted)]">
                            <div class="h-full rounded-full bg-[var(--accent-primary)] transition-all duration-300" style="width: {{ $profile->completeness_percentage }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Recent Interest Requests -->
                <div class="card-ledger p-5 bg-[var(--bg-surface)] space-y-3">
                    <div class="flex items-center justify-between border-b border-[var(--border-subtle)] pb-2.5">
                        <h2 class="text-xs font-bold text-[var(--text-primary)] uppercase tracking-wider">
                            Recent Introduction Requests
                        </h2>
                        <a href="{{ route('interests.index') }}" class="text-xs font-semibold text-[var(--accent-text)] hover:underline">
                            View All &rarr;
                        </a>
                    </div>

                    <div class="space-y-2">
                        @forelse($recentInterests as $interest)
                            <a href="{{ route('discovery.show', $interest->sender_id) }}" class="flex items-center gap-3 p-2.5 rounded-[var(--radius-sm)] border border-[var(--border-default)] hover:bg-[var(--bg-subtle)] transition">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-[var(--radius-sm)] bg-[var(--neutral-900)] text-xs font-bold text-white">
                                    {{ substr($interest->sender->name, 0, 1) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-xs font-bold text-[var(--text-primary)]">{{ $interest->sender->name }}</p>
                                    <p class="text-[11px] text-[var(--text-muted)]">Received {{ $interest->created_at->diffForHumans() }}</p>
                                </div>
                                <span class="text-xs font-semibold text-[var(--accent-text)] shrink-0">Review &rarr;</span>
                            </a>
                        @empty
                            <p class="card-ledger p-5 text-center text-xs text-[var(--text-muted)] bg-[var(--bg-subtle)]">
                                No pending introduction requests at this time.
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Column: Trust & Family + Setup Checklist -->
            <div class="space-y-6">

                <!-- Trust & Family Oversight -->
                <div class="card-ledger p-5 bg-[var(--bg-surface)] space-y-3">
                    <div class="flex items-center justify-between border-b border-[var(--border-subtle)] pb-2.5">
                        <h2 class="text-xs font-bold text-[var(--text-primary)] uppercase tracking-wider">
                            Trust &amp; family
                        </h2>
                        <span class="badge-status text-[var(--accent-text)] bg-[var(--accent-subtle)] border border-[var(--accent-border)]">
                            Shariah Privacy
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="p-3 rounded-[var(--radius-sm)] bg-[var(--bg-subtle)] border border-[var(--border-subtle)] space-y-1">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-[var(--text-muted)]">Identity KYC</span>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-[var(--text-primary)]">
                                    {{ $user->is_verified ? 'Verified Seeker' : ($verificationRequest?->status === 'pending' ? 'Review in Progress' : 'Unverified') }}
                                </span>
                                <span class="w-2 h-2 rounded-full {{ $user->is_verified ? 'bg-[var(--color-success)]' : 'bg-[var(--color-warning)]' }}"></span>
                            </div>
                        </div>

                        <div class="p-3 rounded-[var(--radius-sm)] bg-[var(--bg-subtle)] border border-[var(--border-subtle)] space-y-1">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-[var(--text-muted)]">Guardian (Wali)</span>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-[var(--text-primary)] truncate">
                                    {{ $activeWaliLink ? 'Guardian Connected' : 'Not Linked' }}
                                </span>
                                <span class="w-2 h-2 rounded-full {{ $activeWaliLink ? 'bg-[var(--color-success)]' : 'bg-[var(--text-muted)]' }}"></span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ $activeWaliLink ? route('wali.dashboard') : route('wali.link') }}" class="btn-secondary w-full text-xs font-semibold py-2">
                        {{ $activeWaliLink ? 'Open Family Oversight Console' : 'Connect Your Guardian (Wali)' }} &rarr;
                    </a>
                </div>

                <!-- Profile Setup Progress Checklist -->
                <div class="card-ledger p-5 bg-[var(--bg-surface)] space-y-3">
                    <div class="flex items-start justify-between gap-4 border-b border-[var(--border-subtle)] pb-2.5">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-[var(--accent-text)]">
                                Match Readiness
                            </p>
                            <h2 class="text-base font-bold text-[var(--text-primary)]">
                                Profile setup
                            </h2>
                        </div>
                        <span class="badge-status text-[var(--accent-text)] bg-[var(--accent-subtle)] border border-[var(--accent-border)]">
                            {{ $completedOnboardingSteps }}/{{ count($onboardingSteps) }} Completed
                        </span>
                    </div>

                    <div class="space-y-1.5">
                        @foreach($onboardingSteps as $step)
                            <a href="{{ route($step['route']) }}" class="flex items-center justify-between p-2 rounded-[var(--radius-sm)] transition hover:bg-[var(--bg-subtle)] group">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <span class="flex h-4 w-4 shrink-0 items-center justify-center rounded-full text-[10px] font-bold {{ $step['complete'] ? 'bg-[var(--color-success)] text-white' : 'border border-[var(--border-strong)] text-transparent' }}">
                                        ✓
                                    </span>
                                    <span class="text-xs font-medium truncate {{ $step['complete'] ? 'text-[var(--text-muted)] line-through' : 'text-[var(--text-primary)]' }}">
                                        {{ $step['label'] }}
                                    </span>
                                </div>
                                @if(!$step['complete'])
                                    <span class="text-xs font-semibold text-[var(--accent-text)]">&rarr;</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Saved Searches Quick Workspace -->
                @if($savedSearches->isNotEmpty())
                    <div class="card-ledger p-4 bg-[var(--bg-subtle)] space-y-2.5">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-[var(--text-primary)]">
                                Saved Match Filters
                            </h3>
                            <a href="{{ route('discovery.index') }}" class="text-[11px] font-semibold text-[var(--accent-text)] hover:underline">
                                Search All &rarr;
                            </a>
                        </div>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($savedSearches as $savedSearch)
                                <a href="{{ route('discovery.index', collect($savedSearch->filters ?? [])->filter(fn ($value) => filled($value))->all()) }}" class="btn-secondary text-xs h-7 py-0 px-2.5">
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

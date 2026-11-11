@extends('layouts.app')

@section('title', 'My Matrimonial Dashboard — Global Nikkah')

@section('content')
<div class="min-h-screen bg-[var(--bg-canvas)] py-8">
    <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">

        <!-- 1. Dignified Hero Ledger Banner -->
        <section class="card-ledger pattern-header p-6 sm:p-8 bg-[var(--bg-surface)]">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="max-w-2xl space-y-2">
                    <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-[var(--radius-sm)] bg-[var(--accent-subtle)] text-[var(--accent-text)] text-xs font-semibold">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <span>Assalamu Alaikum, {{ $user->name }}</span>
                    </div>
                    <h1 class="font-serif text-2xl sm:text-3xl font-semibold text-[var(--text-primary)] tracking-tight">
                        Your matrimonial journey, organized with clarity and dignity.
                    </h1>
                    <p class="text-xs sm:text-sm text-[var(--text-secondary)] leading-relaxed max-w-xl">
                        A serene overview of your marriage search, family chaperone involvement, and mutual introductions.
                    </p>
                </div>

                <!-- Readiness Pill & Primary Search CTA -->
                <div class="flex items-center gap-3 shrink-0 flex-wrap sm:flex-nowrap">
                    <div class="flex items-center gap-3 rounded-[var(--radius-md)] border border-[var(--border-default)] bg-[var(--bg-canvas)] px-4 py-3 shadow-2xs">
                        <div class="flex h-11 w-11 items-center justify-center rounded-[var(--radius-sm)] bg-[var(--accent-subtle)] border border-[var(--accent-border)] font-serif text-sm font-bold text-[var(--accent-text)]">
                            {{ $profile->completeness_percentage }}%
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-[var(--text-primary)]">Profile Readiness</p>
                            <p class="text-[11px] text-[var(--text-muted)]">{{ $profile->completeness_percentage >= 70 ? 'Ready for introductions' : 'Add remaining details' }}</p>
                        </div>
                    </div>
                    <a href="{{ route('discovery.index') }}" class="btn-primary h-11 px-5 text-xs font-semibold inline-flex items-center gap-2">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        <span>Discover Candidates</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- 2. Clean Executive Metrics Grid -->
        <section class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('interests.index') }}" class="card-ledger p-5 bg-[var(--bg-surface)] flex flex-col justify-between group hover:border-[var(--brand-primary)] transition">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-semibold text-[var(--text-muted)] uppercase tracking-wider">Pending Introductions</span>
                    <span class="w-8 h-8 rounded-[var(--radius-sm)] bg-[var(--accent-subtle)] text-[var(--accent-text)] flex items-center justify-center text-xs border border-[var(--accent-border)]">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                    </span>
                </div>
                <div class="mt-4 flex items-baseline justify-between">
                    <span class="font-serif text-3xl font-semibold text-[var(--text-primary)]">{{ $pendingInterests }}</span>
                    <span class="text-xs font-semibold text-[var(--accent-text)] group-hover:text-[var(--brand-primary)] transition">Review &rarr;</span>
                </div>
            </a>

            <a href="{{ route('chat.index') }}" class="card-ledger p-5 bg-[var(--bg-surface)] flex flex-col justify-between group hover:border-[var(--brand-primary)] transition">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-semibold text-[var(--text-muted)] uppercase tracking-wider">Messages</span>
                    <span class="w-8 h-8 rounded-[var(--radius-sm)] bg-[var(--accent-subtle)] text-[var(--accent-text)] flex items-center justify-center text-xs border border-[var(--accent-border)]">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    </span>
                </div>
                <div class="mt-4 flex items-baseline justify-between">
                    <span class="font-serif text-3xl font-semibold text-[var(--text-primary)]">{{ $unreadMessages }}</span>
                    <span class="text-xs font-semibold text-[var(--accent-text)] group-hover:text-[var(--brand-primary)] transition">{{ $conversationCount }} Active &rarr;</span>
                </div>
            </a>

            <a href="{{ route('notifications.index') }}" class="card-ledger p-5 bg-[var(--bg-surface)] flex flex-col justify-between group hover:border-[var(--brand-primary)] transition">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-semibold text-[var(--text-muted)] uppercase tracking-wider">Activity Updates</span>
                    <span class="w-8 h-8 rounded-[var(--radius-sm)] bg-[var(--bg-canvas)] text-[var(--text-secondary)] flex items-center justify-center text-xs border border-[var(--border-default)]">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    </span>
                </div>
                <div class="mt-4 flex items-baseline justify-between">
                    <span class="font-serif text-3xl font-semibold text-[var(--text-primary)]">{{ $unreadNotifications }}</span>
                    <span class="text-xs font-semibold text-[var(--accent-text)] group-hover:text-[var(--brand-primary)] transition">Inbox &rarr;</span>
                </div>
            </a>

            <a href="{{ route('discovery.index') }}" class="card-ledger p-5 bg-[var(--bg-surface)] flex flex-col justify-between group hover:border-[var(--brand-primary)] transition">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-semibold text-[var(--text-muted)] uppercase tracking-wider">Daily Profile Views</span>
                    <span class="w-8 h-8 rounded-[var(--radius-sm)] bg-[var(--bg-canvas)] text-[var(--text-secondary)] flex items-center justify-center text-xs border border-[var(--border-default)]">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                    </span>
                </div>
                <div class="mt-4 flex items-baseline justify-between">
                    <span class="font-serif text-3xl font-semibold text-[var(--text-primary)]">{{ $quotaStats['views_limit'] === 'Unlimited' ? '∞' : $quotaStats['views_remaining'] }}</span>
                    <span class="text-xs font-semibold text-[var(--accent-text)] group-hover:text-[var(--brand-primary)] transition">{{ $quotaStats['views_limit'] === 'Unlimited' ? 'Unlimited Pass' : 'Remaining' }} &rarr;</span>
                </div>
            </a>
        </section>

        <!-- 3. Featured Section: Compatible Introductions -->
        <section class="card-ledger p-6 sm:p-8 bg-[var(--bg-surface)] space-y-6">
            <div class="flex items-center justify-between border-b border-[var(--border-subtle)] pb-4">
                <div>
                    <h2 class="font-serif text-xl font-semibold text-[var(--text-primary)]">
                        Today&rsquo;s Compatible Introductions
                    </h2>
                    <p class="text-xs text-[var(--text-muted)] mt-0.5">Suggestions aligned with your religious practice and family criteria.</p>
                </div>
                <a href="{{ route('discovery.index') }}" class="text-xs font-semibold text-[var(--accent-text)] hover:text-[var(--brand-primary)] flex items-center gap-1 transition">
                    <span>Explore All</span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </div>

            @if($dailyMatches->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    @foreach($dailyMatches as $match)
                        <article class="card-ledger p-4 bg-[var(--bg-canvas)] flex flex-col justify-between group hover:border-[var(--brand-primary)] transition duration-200">
                            <div>
                                <div class="relative w-full h-48 mihrab-arch bg-[var(--bg-subtle)] overflow-hidden border border-[var(--border-default)] shadow-2xs">
                                    @if($match->primaryPhoto)
                                        <img src="{{ $match->primaryPhoto->displayUrl() }}" alt="{{ $match->user->name }}" class="w-full h-full object-cover {{ $match->photo_privacy === 'blurred' ? 'photo-blur' : '' }}" loading="lazy">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-[var(--neutral-200)] dark:bg-[var(--neutral-800)] text-[var(--text-muted)] font-serif font-bold text-2xl">
                                            {{ substr($match->user->name, 0, 1) }}
                                        </div>
                                    @endif

                                    <div class="absolute top-2.5 right-2.5">
                                        <span class="badge-status text-[10px] bg-[var(--bg-surface)]/90 backdrop-blur-xs border border-[var(--border-subtle)] font-medium text-[var(--text-secondary)]">
                                            {{ $match->compatibility_score }}% Match
                                        </span>
                                    </div>
                                </div>

                                <div class="mt-3 space-y-1">
                                    <h3 class="font-serif text-base font-semibold text-[var(--text-primary)] group-hover:text-[var(--brand-primary)] transition truncate">
                                        {{ $match->user->name }}
                                    </h3>
                                    <p class="text-xs text-[var(--text-muted)] truncate">
                                        {{ $match->user->age }} yrs &bull; {{ $match->city ?: 'District flexible' }}
                                    </p>
                                </div>

                                <div class="mt-3 pt-3 border-t border-[var(--border-subtle)] text-[11px] grid grid-cols-2 gap-2 text-[var(--text-secondary)]">
                                    <div>
                                        <span class="text-[var(--text-muted)] block text-[10px] uppercase font-semibold">Madhhab</span>
                                        <span class="font-medium truncate block">{{ $match->sect_madhhab ?: 'Practicing' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-[var(--text-muted)] block text-[10px] uppercase font-semibold">Wali Status</span>
                                        <span class="font-medium truncate block">{{ $match->wali_required ? 'Required' : 'Family Notified' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 pt-2">
                                <a href="{{ route('discovery.show', $match->user_id) }}" class="btn-secondary w-full h-9 text-xs font-semibold flex items-center justify-center gap-1">
                                    <span>View Biodata</span>
                                    <span aria-hidden="true">&rarr;</span>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="p-8 text-center bg-[var(--bg-canvas)] rounded-[var(--radius-md)] border border-[var(--border-subtle)]">
                    <p class="font-serif text-base text-[var(--text-secondary)]">Expand your profile preferences</p>
                    <p class="text-xs text-[var(--text-muted)] mt-1 max-w-md mx-auto">
                        Add more details to your partner expectations to unlock daily compatibility introductions.
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('profile.edit') }}" class="btn-secondary h-9 px-4 text-xs font-semibold inline-flex items-center gap-1">
                            <span>Edit Preferences</span>
                            <span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>
                </div>
            @endif
        </section>

        <!-- 4. Two-Column Layout: Priority Action vs Trust & Governance -->
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <!-- Left Column: Priority Action & Recent Interests -->
            <div class="space-y-6">

                <!-- Recommended Next Action -->
                <div class="card-ledger p-6 bg-[var(--bg-surface)] space-y-4">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-1">
                            <span class="text-[10px] font-semibold uppercase tracking-wider text-[var(--accent-text)]">
                                Recommended Next Step
                            </span>
                            <h2 class="font-serif text-lg font-semibold text-[var(--text-primary)]">
                                {{ $nextAction['title'] }}
                            </h2>
                            <p class="text-xs leading-relaxed text-[var(--text-secondary)]">
                                {{ $nextAction['description'] }}
                            </p>
                        </div>
                        <a href="{{ route($nextAction['route']) }}" class="btn-primary h-10 px-4 text-xs font-semibold shrink-0 inline-flex items-center gap-1">
                            <span>{{ $nextAction['label'] }}</span>
                            <span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>

                    <div class="border-t border-[var(--border-subtle)] pt-3">
                        <div class="flex items-center justify-between text-xs mb-1.5 font-medium text-[var(--text-secondary)]">
                            <span>Profile Readiness</span>
                            <span class="font-semibold text-[var(--accent-text)]">{{ $profile->completeness_percentage }}%</span>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-[var(--bg-canvas)] border border-[var(--border-subtle)]">
                            <div class="h-full rounded-full bg-[var(--brand-primary)] transition-all duration-300" style="width: {{ $profile->completeness_percentage }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Recent Interest Requests -->
                <div class="card-ledger p-6 bg-[var(--bg-surface)] space-y-4">
                    <div class="flex items-center justify-between border-b border-[var(--border-subtle)] pb-3">
                        <h2 class="font-serif text-base font-semibold text-[var(--text-primary)]">
                            Recent Introduction Requests
                        </h2>
                        <a href="{{ route('interests.index') }}" class="text-xs font-semibold text-[var(--accent-text)] hover:text-[var(--brand-primary)] transition">
                            View All &rarr;
                        </a>
                    </div>

                    <div class="space-y-2.5">
                        @forelse($recentInterests as $interest)
                            <a href="{{ route('discovery.show', $interest->sender_id) }}" class="flex items-center gap-3.5 p-3 rounded-[var(--radius-md)] bg-[var(--bg-canvas)] border border-[var(--border-default)] hover:border-[var(--brand-primary)] transition group">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-[var(--radius-sm)] bg-[var(--accent-subtle)] text-xs font-serif font-bold text-[var(--accent-text)] border border-[var(--accent-border)]">
                                    {{ substr($interest->sender->name, 0, 1) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-xs font-semibold text-[var(--text-primary)] group-hover:text-[var(--brand-primary)] transition">{{ $interest->sender->name }}</p>
                                    <p class="text-[11px] text-[var(--text-muted)]">Received {{ $interest->created_at->diffForHumans() }}</p>
                                </div>
                                <span class="text-xs font-semibold text-[var(--accent-text)] group-hover:text-[var(--brand-primary)] shrink-0 transition">Review &rarr;</span>
                            </a>
                        @empty
                            <p class="p-6 text-center text-xs text-[var(--text-muted)] bg-[var(--bg-canvas)] rounded-[var(--radius-md)] border border-[var(--border-subtle)]">
                                No pending introduction requests at this time.
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Column: Trust & Family + Setup Checklist -->
            <div class="space-y-6">

                <!-- Trust & Family Oversight -->
                <div class="card-ledger p-6 bg-[var(--bg-surface)] space-y-4">
                    <div class="flex items-center justify-between border-b border-[var(--border-subtle)] pb-3">
                        <h2 class="font-serif text-base font-semibold text-[var(--text-primary)]">
                            Trust &amp; Family Oversight
                        </h2>
                        <span class="badge-status text-[var(--accent-text)] bg-[var(--accent-subtle)] border border-[var(--accent-border)] text-[10px]">
                            Shariah Privacy
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div class="p-3.5 rounded-[var(--radius-md)] bg-[var(--bg-canvas)] border border-[var(--border-default)] space-y-1">
                            <span class="text-[10px] font-semibold uppercase tracking-wider text-[var(--text-muted)]">Identity Verification</span>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold text-[var(--text-primary)]">
                                    {{ $user->is_verified ? 'Verified Seeker' : ($verificationRequest?->status === 'pending' ? 'Review in Progress' : 'Unverified') }}
                                </span>
                                <span class="w-2 h-2 rounded-full {{ $user->is_verified ? 'bg-[var(--color-success)]' : 'bg-[var(--color-warning)]' }}"></span>
                            </div>
                        </div>

                        <div class="p-3.5 rounded-[var(--radius-md)] bg-[var(--bg-canvas)] border border-[var(--border-default)] space-y-1">
                            <span class="text-[10px] font-semibold uppercase tracking-wider text-[var(--text-muted)]">Guardian (Wali)</span>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold text-[var(--text-primary)] truncate">
                                    {{ $activeWaliLink ? 'Guardian Connected' : 'Not Linked' }}
                                </span>
                                <span class="w-2 h-2 rounded-full {{ $activeWaliLink ? 'bg-[var(--color-success)]' : 'bg-[var(--text-muted)]' }}"></span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ $activeWaliLink ? route('wali.dashboard') : route('wali.link') }}" class="btn-secondary w-full h-10 text-xs font-semibold flex items-center justify-center gap-1">
                        <span>{{ $activeWaliLink ? 'Open Family Oversight Console' : 'Connect Your Guardian (Wali)' }}</span>
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>

                <!-- Profile Setup Progress Checklist -->
                <div class="card-ledger p-6 bg-[var(--bg-surface)] space-y-4">
                    <div class="flex items-start justify-between gap-4 border-b border-[var(--border-subtle)] pb-3">
                        <div>
                            <span class="text-[10px] font-semibold uppercase tracking-wider text-[var(--accent-text)]">
                                Match Readiness
                            </span>
                            <h2 class="font-serif text-base font-semibold text-[var(--text-primary)] mt-0.5">
                                Onboarding Checklist
                            </h2>
                        </div>
                        <span class="badge-status text-[var(--accent-text)] bg-[var(--accent-subtle)] border border-[var(--accent-border)] text-[10px]">
                            {{ $completedOnboardingSteps }}/{{ count($onboardingSteps) }} Done
                        </span>
                    </div>

                    <div class="space-y-2">
                        @foreach($onboardingSteps as $step)
                            <a href="{{ route($step['route']) }}" class="flex items-center justify-between p-2.5 rounded-[var(--radius-md)] transition hover:bg-[var(--bg-canvas)] border border-transparent hover:border-[var(--border-default)] group">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <span class="flex h-4 w-4 shrink-0 items-center justify-center rounded-full text-[10px] font-bold {{ $step['complete'] ? 'bg-[var(--color-success)] text-white' : 'border border-[var(--border-default)] text-transparent' }}">
                                        ✓
                                    </span>
                                    <span class="text-xs font-medium truncate {{ $step['complete'] ? 'text-[var(--text-muted)] line-through' : 'text-[var(--text-primary)]' }}">
                                        {{ $step['label'] }}
                                    </span>
                                </div>
                                @if(!$step['complete'])
                                    <span class="text-xs font-semibold text-[var(--accent-text)] group-hover:text-[var(--brand-primary)]">&rarr;</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Saved Searches Quick Workspace -->
                @if($savedSearches->isNotEmpty())
                    <div class="card-ledger p-5 bg-[var(--bg-surface)] space-y-3">
                        <div class="flex items-center justify-between">
                            <h3 class="font-serif text-sm font-semibold text-[var(--text-primary)]">
                                Saved Match Searches
                            </h3>
                            <a href="{{ route('discovery.index') }}" class="text-[11px] font-semibold text-[var(--accent-text)] hover:underline">
                                Search All &rarr;
                            </a>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($savedSearches as $savedSearch)
                                <a href="{{ route('discovery.index', collect($savedSearch->filters ?? [])->filter(fn ($value) => filled($value))->all()) }}" class="btn-secondary text-xs h-8 py-0 px-3 flex items-center gap-1">
                                    <span>{{ $savedSearch->title }}</span>
                                    <span aria-hidden="true">&rarr;</span>
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

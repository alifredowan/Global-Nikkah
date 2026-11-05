@extends('layouts.app')

@section('title', 'Global Nikkah - Verified Muslim Matrimonial Directory')
@section('meta_description', 'A dignified, verified matrimonial directory for practicing Muslims and their families. Complete identity checks, Wali oversight, and photo privacy.')
@section('canonical_url', route('landing'))
@section('robots', 'index, follow')

@section('content')
<div class="space-y-12 sm:space-y-16 pb-16">

    <!-- ================================================================= -->
    <!-- 1. HERO SECTION: CURVED FLOATING HERO WITH FIRST-SCREEN SEARCH    -->
    <!-- ================================================================= -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-2 sm:pt-4">
        <section class="card-ledger pattern-header rounded-2xl sm:rounded-3xl p-6 sm:p-10 lg:p-12 bg-[var(--bg-surface)] border border-[var(--border-default)] shadow-xs">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">

                <!-- Left Column: Plain, Left-Aligned Headline & Sincere Copy -->
                <div class="lg:col-span-7 space-y-4">
                    <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-[var(--radius-sm)] bg-[var(--accent-subtle)] border border-[var(--accent-border)] text-[var(--accent-text)] text-xs font-semibold">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                        <span>Verified Matrimonial Directory</span>
                    </div>

                    <h1 class="text-2xl sm:text-4xl lg:text-5xl font-bold tracking-tight text-[var(--text-primary)] leading-[1.2]">
                        A dignified matrimonial directory for practicing Muslims and their families.
                    </h1>

                    <p class="text-sm sm:text-base text-[var(--text-secondary)] leading-relaxed max-w-2xl">
                        Global Nikkah is built for marriage, not casual dating. Candidates are registered with verified identities, optional guardian (Wali) supervision, and default photo privacy until mutual interest is established.
                    </p>

                    <!-- Trust Checklist (No Emojis, Quiet Monoline SVGs) -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2 text-xs text-[var(--text-secondary)]">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[var(--accent-primary)] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
                            <span>National ID / KYC verification</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[var(--accent-primary)] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
                            <span>Wali chaperone oversight</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[var(--accent-primary)] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
                            <span>Photo modesty protection</span>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Immediate Search Form (In First Screen) -->
                <div class="lg:col-span-5">
                    <div class="card-ledger p-5 sm:p-6 bg-[var(--bg-surface)]">
                        <div class="border-b border-[var(--border-subtle)] pb-3 mb-4">
                            <h2 class="text-sm font-bold text-[var(--text-primary)] uppercase tracking-wider">
                                Search Directory
                            </h2>
                            <p class="text-xs text-[var(--text-muted)] mt-0.5">Filter registered candidates by key criteria.</p>
                        </div>

                        <form action="{{ route('discovery.index') }}" method="GET" class="space-y-3.5">
                            <div>
                                <label for="looking_for" class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">Looking for</label>
                                <select id="looking_for" name="gender" class="w-full h-10 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs focus:ring-2 focus:ring-[var(--accent-primary)] outline-none">
                                    <option value="">Any candidate</option>
                                    <option value="female" {{ request('gender') === 'female' ? 'selected' : '' }}>Practicing Bride (Female)</option>
                                    <option value="male" {{ request('gender') === 'male' ? 'selected' : '' }}>Practicing Groom (Male)</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-2.5">
                                <div>
                                    <label for="age_min" class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">Age from</label>
                                    <input type="number" id="age_min" name="age_min" min="18" max="75" placeholder="21"
                                           class="w-full h-10 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs focus:ring-2 focus:ring-[var(--accent-primary)] outline-none">
                                </div>
                                <div>
                                    <label for="age_max" class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">Age to</label>
                                    <input type="number" id="age_max" name="age_max" min="18" max="75" placeholder="35"
                                           class="w-full h-10 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs focus:ring-2 focus:ring-[var(--accent-primary)] outline-none">
                                </div>
                            </div>

                            <div>
                                <label for="district" class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">District / City</label>
                                <input type="text" id="district" name="country" placeholder="e.g. Dhaka, Chittagong, London, Toronto"
                                       class="w-full h-10 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs focus:ring-2 focus:ring-[var(--accent-primary)] outline-none">
                            </div>

                            <div>
                                <label for="marital_status" class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">Marital status</label>
                                <select id="marital_status" name="marital_status" class="w-full h-10 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs focus:ring-2 focus:ring-[var(--accent-primary)] outline-none">
                                    <option value="">Any marital status</option>
                                    <option value="never_married">Never married</option>
                                    <option value="divorced">Divorced</option>
                                    <option value="widowed">Widowed</option>
                                </select>
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="btn-primary w-full text-xs font-bold">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                                    <span>Search Candidates</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </section>
    </div>

    <!-- ================================================================= -->
    <!-- 2. TRUST & ETHICAL GOVERNANCE (VERIFICATION & PRIVACY)            -->
    <!-- ================================================================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="border-b border-[var(--border-default)] pb-3 mb-6 flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-1">
            <div>
                <h2 class="text-base font-bold text-[var(--text-primary)] uppercase tracking-wider">
                    Trust &amp; Modesty Standards
                </h2>
                <p class="text-xs text-[var(--text-muted)] mt-0.5">How Global Nikkah maintains a serious environment for marriage.</p>
            </div>
            <a href="{{ route('how-it-works') }}" class="text-xs font-semibold text-[var(--accent-text)] hover:underline flex items-center gap-1">
                <span>Read detailed Shariah process</span>
                <span>&rarr;</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="card-ledger p-5 space-y-2.5">
                <div class="w-9 h-9 rounded-[var(--radius-sm)] bg-[var(--accent-subtle)] border border-[var(--accent-border)] text-[var(--accent-text)] flex items-center justify-center">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        <path d="m9 12 2 2 4-4"/>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-[var(--text-primary)]">Manual Identity Verification</h3>
                <p class="text-xs text-[var(--text-secondary)] leading-relaxed">
                    National ID or passport records are manually verified by trained moderators. Verified badges confirm the candidate’s legal identity and age before family introduction.
                </p>
            </div>

            <div class="card-ledger p-5 space-y-2.5">
                <div class="w-9 h-9 rounded-[var(--radius-sm)] bg-[var(--accent-subtle)] border border-[var(--accent-border)] text-[var(--accent-text)] flex items-center justify-center">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-[var(--text-primary)]">Guardian (Wali) Chaperone</h3>
                <p class="text-xs text-[var(--text-secondary)] leading-relaxed">
                    Sisters can attach their father, brother, or guardian with dedicated portal access. Guardians oversee courtship requests, review profiles, and join conversations.
                </p>
            </div>

            <div class="card-ledger p-5 space-y-2.5">
                <div class="w-9 h-9 rounded-[var(--radius-sm)] bg-[var(--accent-subtle)] border border-[var(--accent-border)] text-[var(--accent-text)] flex items-center justify-center">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                        <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-[var(--text-primary)]">Modesty (Haya) Protection</h3>
                <p class="text-xs text-[var(--text-secondary)] leading-relaxed">
                    Photos are blurred by default so evaluations center on faith, values, and life vision. Photos are only unblurred when both candidates mutually accept an inquiry.
                </p>
            </div>
        </div>
    </section>

    <!-- ================================================================= -->
    <!-- 3. RECENT CANDIDATE PROFILES (REAL DATABASE DATA)                 -->
    <!-- ================================================================= -->
    @if($recentProfiles->isNotEmpty())
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="border-b border-[var(--border-default)] pb-3 mb-6 flex items-baseline justify-between">
                <div>
                    <h2 class="text-base font-bold text-[var(--text-primary)] uppercase tracking-wider">
                        Recent Verified Candidates
                    </h2>
                    <p class="text-xs text-[var(--text-muted)] mt-0.5">Registered individuals currently seeking righteous marriage.</p>
                </div>
                <a href="{{ route('discovery.index') }}" class="text-xs font-semibold text-[var(--accent-text)] hover:underline flex items-center gap-1">
                    <span>Browse all candidates</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                @foreach($recentProfiles as $profile)
                    <article class="card-ledger flex flex-col justify-between overflow-hidden">
                        <div>
                            <div class="relative h-44 bg-[var(--neutral-900)] overflow-hidden">
                                @if($profile->primaryPhoto)
                                    <img src="{{ $profile->primaryPhoto->displayUrl() }}"
                                         alt="{{ $profile->user->name }}"
                                         class="w-full h-full object-cover photo-blur"
                                         loading="lazy" decoding="async">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-[var(--neutral-800)] text-[var(--neutral-300)] font-semibold text-2xl">
                                        {{ substr($profile->user->name, 0, 1) }}
                                    </div>
                                @endif

                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

                                <div class="absolute top-2.5 left-2.5 flex items-center gap-1.5">
                                    <span class="px-2 py-0.5 rounded-[var(--radius-sm)] text-[10px] font-medium bg-black/70 text-white/90 border border-white/10 flex items-center gap-1">
                                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                        <span>Modesty Shield</span>
                                    </span>
                                    @if($profile->user->is_verified)
                                        <span class="badge-verified">
                                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                                            <span>Verified</span>
                                        </span>
                                    @endif
                                </div>

                                <div class="absolute bottom-2.5 inset-x-3 text-white">
                                    <h3 class="text-sm font-bold truncate">{{ $profile->user->name }}</h3>
                                    <p class="text-[11px] text-[var(--neutral-300)] truncate">
                                        {{ $profile->user->age }} yrs • {{ $profile->city ?: 'District flexible' }}, {{ $profile->country ?: '' }}
                                    </p>
                                </div>
                            </div>

                            <div class="p-3.5 space-y-2 border-t border-[var(--border-subtle)]">
                                <div class="grid grid-cols-2 gap-2 text-[11px]">
                                    <div>
                                        <span class="text-[var(--text-muted)] block text-[10px] uppercase font-semibold">Madhhab</span>
                                        <span class="text-[var(--text-primary)] font-medium truncate block">{{ $profile->sect_madhhab ?: 'Practicing Muslim' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-[var(--text-muted)] block text-[10px] uppercase font-semibold">Prayer</span>
                                        <span class="text-[var(--text-primary)] font-medium truncate block">{{ str_replace('_', ' ', $profile->prayer_frequency ?: '5x Daily') }}</span>
                                    </div>
                                    <div>
                                        <span class="text-[var(--text-muted)] block text-[10px] uppercase font-semibold">Education</span>
                                        <span class="text-[var(--text-primary)] font-medium truncate block">{{ $profile->education_level ?: 'Graduate' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-[var(--text-muted)] block text-[10px] uppercase font-semibold">Guardian</span>
                                        <span class="text-[var(--text-primary)] font-medium truncate block">{{ $profile->wali_required ? 'Wali Linked' : 'Self' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-3.5 pt-0">
                            <a href="{{ route('discovery.show', $profile->user_id) }}" class="btn-secondary w-full text-xs font-semibold py-2">
                                <span>View Biodata</span>
                                <span class="text-[10px]">&rarr;</span>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    <!-- ================================================================= -->
    <!-- 4. ETHICAL PRICING: SINGLE NIKAH PASS (NO MONTHLY GOUGE)          -->
    <!-- ================================================================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="card-ledger p-6 sm:p-8 bg-[var(--bg-surface)] space-y-6">
            <div class="border-b border-[var(--border-subtle)] pb-4 flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-1">
                <div>
                    <h2 class="text-base font-bold text-[var(--text-primary)] uppercase tracking-wider">
                        Ethical Matrimonial Pricing
                    </h2>
                    <p class="text-xs text-[var(--text-muted)] mt-0.5">No subscription traps or recurring billings. Pay 1-Time active until confirmed Nikah.</p>
                </div>
                <a href="{{ route('subscription.pricing') }}" class="text-xs font-semibold text-[var(--accent-text)] hover:underline flex items-center gap-1">
                    <span>View all plan comparisons</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                @foreach($plans as $plan)
                    <div class="card-ledger p-5 bg-[var(--bg-surface)] flex flex-col justify-between">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-bold text-[var(--text-primary)]">{{ $plan->name }}</h3>
                                @if($plan->monthly_price > 0)
                                    <span class="badge-status text-[var(--accent-text)] bg-[var(--accent-subtle)] border border-[var(--accent-border)] font-semibold text-[10px]">
                                        Pay 1-Time
                                    </span>
                                @endif
                            </div>

                            <div>
                                @if($plan->monthly_price == 0)
                                    <p class="text-2xl font-bold text-[var(--text-primary)]">Free</p>
                                    <p class="text-xs text-[var(--text-muted)] mt-0.5">Always available for seekers and guardians.</p>
                                @else
                                    <p class="text-2xl font-bold text-[var(--text-primary)]">${{ number_format($plan->monthly_price, 2) }}</p>
                                    <p class="text-xs text-[var(--accent-text)] font-semibold mt-0.5 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                                        <span>Active Until Confirmed Nikah</span>
                                    </p>
                                @endif
                            </div>

                            <p class="text-xs text-[var(--text-secondary)] leading-relaxed">
                                {{ $plan->description ?: 'Designed for respectful family introductions and verified matchmaking.' }}
                            </p>
                        </div>

                        <div class="pt-4 border-t border-[var(--border-subtle)] mt-4">
                            <a href="{{ route('register') }}" class="{{ $plan->monthly_price > 0 ? 'btn-primary' : 'btn-secondary' }} w-full text-xs font-semibold py-2">
                                <span>Get Started</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ================================================================= -->
    <!-- 5. STATS LEDGER                                                   -->
    <!-- ================================================================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="card-ledger p-6 sm:p-8 bg-[var(--bg-surface)]">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-center divide-y sm:divide-y-0 sm:divide-x divide-[var(--border-subtle)]">
                <div class="pt-4 sm:pt-0">
                    <p class="text-2xl sm:text-3xl font-bold text-[var(--accent-text)]">{{ number_format($stats['total_seekers']) }}</p>
                    <p class="text-xs text-[var(--text-muted)] mt-1 font-semibold uppercase tracking-wider">Registered Seekers</p>
                </div>
                <div class="pt-4 sm:pt-0">
                    <p class="text-2xl sm:text-3xl font-bold text-[var(--accent-text)]">{{ number_format($stats['verified_profiles']) }}</p>
                    <p class="text-xs text-[var(--text-muted)] mt-1 font-semibold uppercase tracking-wider">Verified Identity Records</p>
                </div>
                <div class="pt-4 sm:pt-0">
                    <p class="text-2xl sm:text-3xl font-bold text-[var(--accent-text)]">{{ number_format($stats['successful_matches']) }}</p>
                    <p class="text-xs text-[var(--text-muted)] mt-1 font-semibold uppercase tracking-wider">Families Introduced</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ================================================================= -->
    <!-- 6. CALL TO ACTION: START VERIFICATION                             -->
    <!-- ================================================================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="card-ledger p-8 sm:p-10 bg-[var(--bg-subtle)] border-[var(--border-default)] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
            <div class="space-y-1.5 max-w-2xl">
                <h2 class="text-lg sm:text-xl font-bold text-[var(--text-primary)]">
                    Begin with pure intentions and family involvement.
                </h2>
                <p class="text-xs sm:text-sm text-[var(--text-secondary)] leading-relaxed">
                    Registration takes less than five minutes. Your profile is kept confidential until you choose to connect.
                </p>
            </div>
            <div class="flex items-center gap-3 shrink-0 flex-wrap">
                <a href="{{ route('register') }}" class="btn-primary text-xs font-bold px-5">
                    <span>Create Free Profile</span>
                    <span>&rarr;</span>
                </a>
                <a href="{{ route('about') }}" class="btn-secondary text-xs font-semibold px-4">
                    <span>About Global Nikkah</span>
                </a>
            </div>
        </div>
    </section>

</div>
@endsection

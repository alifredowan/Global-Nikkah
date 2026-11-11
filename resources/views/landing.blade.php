@extends('layouts.app')

@section('title', 'Global Nikkah • International Muslim Matrimonial Platform')
@section('meta_description', 'Connect with practicing Muslims worldwide for serious marriage. Verified profiles, family guardian participation, and default photo privacy.')
@section('canonical_url', route('landing'))
@section('robots', 'index, follow')

@section('content')
<div class="space-y-16 sm:space-y-24 pb-20">

    <!-- ================================================================= -->
    <!-- 1. HERO SECTION: BOLD, SCANNABLE, VISUAL COMPOSITION              -->
    <!-- ================================================================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-center">

            <!-- Left: Hero Headline & Actions (7 Cols) -->
            <div class="lg:col-span-7 space-y-6">

                <!-- Quiet Trust Pill -->
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-[var(--radius-sm)] bg-[var(--accent-subtle)] border border-[var(--accent-border)] text-[var(--accent-text)] text-xs font-semibold">
                    <svg class="w-4 h-4 text-[var(--accent-primary)] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        <path d="m9 12 2 2 4-4"/>
                    </svg>
                    <span>Worldwide Muslim Matrimony &bull; UK, USA, Gulf &amp; Beyond</span>
                </div>

                <!-- Headline (Max 8 words) -->
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-serif font-bold text-[var(--text-primary)] leading-[1.12] tracking-tight">
                    Dignified Nikah for Muslims Worldwide.
                </h1>

                <!-- Sub-line (Under 20 words) -->
                <p class="text-lg sm:text-xl text-[var(--text-secondary)] font-normal leading-relaxed max-w-xl">
                    Connect with verified practicing Muslims and trusted family guardians for marriage on the Sunnah.
                </p>

                <!-- Primary Action Button (48px high) & Secondary Action -->
                <div class="pt-2 flex flex-wrap items-center gap-4">
                    <a href="{{ route('register') }}" class="btn-primary h-12 px-8 text-base font-semibold shadow-md">
                        <span>Create free profile</span>
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                    <a href="{{ route('how-it-works') }}" class="btn-secondary h-12 px-6 text-base font-semibold">
                        <span>How it works</span>
                    </a>
                </div>

                <!-- Real Platform Trust Stats (Loaded Dynamically From Database) -->
                <div class="pt-6 border-t border-[var(--border-subtle)] grid grid-cols-3 gap-4"
                     x-data="{
                        shown: false,
                        countMembers: 0,
                        countVerified: 0,
                        countWali: 0,
                        targetMembers: {{ (int) ($stats['active_seekers'] ?? 0) }},
                        targetVerified: {{ (int) ($stats['verified_percentage'] ?? 0) }},
                        targetWali: {{ (int) ($stats['wali_accounts'] ?? 0) }},
                        init() {
                            let observer = new IntersectionObserver((entries) => {
                                if (entries[0].isIntersecting && !this.shown) {
                                    this.shown = true;
                                    this.animateValue('countMembers', 0, this.targetMembers, 600);
                                    this.animateValue('countVerified', 0, this.targetVerified, 600);
                                    this.animateValue('countWali', 0, this.targetWali, 600);
                                }
                            }, { threshold: 0.3 });
                            observer.observe(this.$el);
                        },
                        animateValue(prop, start, end, duration) {
                            if (end <= 0) { this[prop] = 0; return; }
                            let startTime = null;
                            const step = (timestamp) => {
                                if (!startTime) startTime = timestamp;
                                const progress = Math.min((timestamp - startTime) / duration, 1);
                                this[prop] = Math.floor(progress * (end - start) + start);
                                if (progress < 1) {
                                    window.requestAnimationFrame(step);
                                } else {
                                    this[prop] = end;
                                }
                            };
                            window.requestAnimationFrame(step);
                        }
                     }">
                    <div>
                        <p class="font-serif text-2xl sm:text-3xl font-bold text-[var(--text-primary)]">
                            <span x-text="countMembers">{{ number_format($stats['active_seekers'] ?? 0) }}</span>{{ ($stats['active_seekers'] ?? 0) >= 1000 ? '+' : '' }}
                        </p>
                        <p class="text-xs text-[var(--text-muted)] font-medium mt-0.5">Active Seekers</p>
                    </div>
                    <div>
                        <p class="font-serif text-2xl sm:text-3xl font-bold text-[var(--text-primary)]">
                            <span x-text="countVerified">{{ $stats['verified_percentage'] ?? 0 }}</span>%
                        </p>
                        <p class="text-xs text-[var(--text-muted)] font-medium mt-0.5">ID Verified</p>
                    </div>
                    <div>
                        <p class="font-serif text-2xl sm:text-3xl font-bold text-[var(--accent-text)]">
                            <span x-text="countWali">{{ number_format($stats['wali_accounts'] ?? 0) }}</span>{{ ($stats['wali_accounts'] ?? 0) >= 1000 ? '+' : '' }}
                        </p>
                        <p class="text-xs text-[var(--text-muted)] font-medium mt-0.5">Free Wali Accounts</p>
                    </div>
                </div>

            </div>

            <!-- Right: Real Candidates & Visual Composition (5 Cols) -->
            <div class="lg:col-span-5 relative">
                <div class="relative mx-auto max-w-sm sm:max-w-md space-y-3">

                    @if($recentProfiles->isNotEmpty())
                        @foreach($recentProfiles->take(3) as $index => $profile)
                            <div class="card-ledger p-4 bg-[var(--bg-surface)] {{ $index === 1 ? 'border-2 border-[var(--secondary-primary)] shadow-md sm:translate-x-3' : '' }} flex items-center gap-4 hover-lift">
                                <div class="w-16 h-20 mihrab-arch bg-[var(--neutral-800)] overflow-hidden shrink-0 border border-[var(--border-default)] relative">
                                    @if($profile->primaryPhoto)
                                        <img src="{{ $profile->primaryPhoto->displayUrl() }}" alt="{{ $profile->user->name }}" class="w-full h-full object-cover photo-blur" loading="lazy">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center {{ $index === 1 ? 'bg-[var(--secondary-primary)]' : 'bg-[var(--accent-primary)]' }} text-white font-serif font-bold text-xl">
                                            {{ substr($profile->user->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <span class="absolute inset-x-0 bottom-0 text-[8px] font-bold text-center bg-black/70 text-white py-0.5">Haya Blur</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-1.5">
                                        <h3 class="font-serif text-base font-bold text-[var(--text-primary)] truncate">{{ $profile->user->name }}, {{ $profile->user->age }}</h3>
                                        @if($profile->user->is_verified)
                                            <span class="badge-verified text-[10px]">Verified</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-[var(--text-secondary)] mt-0.5 truncate">{{ $profile->profession ?: 'Seeker' }} &bull; {{ $profile->city ?: 'Worldwide' }}, {{ $profile->country }}</p>
                                    @if($profile->wali_required || $profile->user->isWaliDependent())
                                        <span class="inline-block mt-1 text-[11px] font-semibold text-[var(--secondary-text)] bg-[var(--secondary-subtle)] px-2 py-0.5 rounded-[var(--radius-xs)]">
                                            Wali Chaperoned
                                        </span>
                                    @else
                                        <span class="inline-block mt-1 text-[11px] font-semibold text-[var(--accent-text)] bg-[var(--accent-subtle)] px-2 py-0.5 rounded-[var(--radius-xs)]">
                                            Deen Verified
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <!-- Sample Candidate Card 1 -->
                        <div class="card-ledger p-4 bg-[var(--bg-surface)] flex items-center gap-4 hover-lift">
                            <div class="w-16 h-20 mihrab-arch bg-[var(--neutral-800)] overflow-hidden shrink-0 border border-[var(--border-default)] relative">
                                <div class="w-full h-full flex items-center justify-center bg-[var(--accent-primary)] text-white font-serif font-bold text-xl">
                                    M
                                </div>
                                <span class="absolute inset-x-0 bottom-0 text-[8px] font-bold text-center bg-black/70 text-white py-0.5">Haya Blur</span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-1.5">
                                    <h3 class="font-serif text-base font-bold text-[var(--text-primary)]">Mariam, 26</h3>
                                    <span class="badge-verified text-[10px]">Verified</span>
                                </div>
                                <p class="text-xs text-[var(--text-secondary)] mt-0.5">Architect &bull; London, UK</p>
                                <span class="inline-block mt-1 text-[11px] font-semibold text-[var(--accent-text)] bg-[var(--accent-subtle)] px-2 py-0.5 rounded-[var(--radius-xs)]">
                                    96% Deen Match
                                </span>
                            </div>
                        </div>

                        <!-- Sample Candidate Card 2 (Offset Highlight) -->
                        <div class="card-ledger p-4 bg-[var(--bg-surface)] border-2 border-[var(--secondary-primary)] shadow-md flex items-center gap-4 hover-lift sm:translate-x-3">
                            <div class="w-16 h-20 mihrab-arch bg-[var(--neutral-800)] overflow-hidden shrink-0 border border-[var(--border-default)] relative">
                                <div class="w-full h-full flex items-center justify-center bg-[var(--secondary-primary)] text-white font-serif font-bold text-xl">
                                    Y
                                </div>
                                <span class="absolute inset-x-0 bottom-0 text-[8px] font-bold text-center bg-black/70 text-white py-0.5">Haya Blur</span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-1.5">
                                    <h3 class="font-serif text-base font-bold text-[var(--text-primary)]">Yusuf, 29</h3>
                                    <span class="badge-verified text-[10px]">Verified</span>
                                </div>
                                <p class="text-xs text-[var(--text-secondary)] mt-0.5">Physician &bull; Toronto, Canada</p>
                                <span class="inline-block mt-1 text-[11px] font-semibold text-[var(--secondary-text)] bg-[var(--secondary-subtle)] px-2 py-0.5 rounded-[var(--radius-xs)]">
                                    Wali Chaperoned
                                </span>
                            </div>
                        </div>

                        <!-- Sample Candidate Card 3 -->
                        <div class="card-ledger p-4 bg-[var(--bg-surface)] flex items-center gap-4 hover-lift">
                            <div class="w-16 h-20 mihrab-arch bg-[var(--neutral-800)] overflow-hidden shrink-0 border border-[var(--border-default)] relative">
                                <div class="w-full h-full flex items-center justify-center bg-[var(--accent-primary)] text-white font-serif font-bold text-xl">
                                    A
                                </div>
                                <span class="absolute inset-x-0 bottom-0 text-[8px] font-bold text-center bg-black/70 text-white py-0.5">Haya Blur</span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-1.5">
                                    <h3 class="font-serif text-base font-bold text-[var(--text-primary)]">Amira, 25</h3>
                                    <span class="badge-verified text-[10px]">Verified</span>
                                </div>
                                <p class="text-xs text-[var(--text-secondary)] mt-0.5">Educator &bull; Dubai, UAE</p>
                                <span class="inline-block mt-1 text-[11px] font-semibold text-[var(--accent-text)] bg-[var(--accent-subtle)] px-2 py-0.5 rounded-[var(--radius-xs)]">
                                    93% Deen Match
                                </span>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

                </div>
            </div>

        </div>
    </section>

    <!-- ================================================================= -->
    <!-- 2. HOW IT WORKS: 4-STEP VISUAL STRIP (NO PARAGRAPHS)             -->
    <!-- ================================================================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="border-t border-[var(--border-default)] pt-12 pb-6 space-y-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-[var(--secondary-text)] block">The Process</span>
                    <h2 class="text-3xl font-serif font-bold text-[var(--text-primary)] mt-1">
                        Four Simple Steps to Nikah
                    </h2>
                </div>
                <a href="{{ route('how-it-works') }}" class="text-xs font-semibold text-[var(--accent-text)] hover:underline flex items-center gap-1">
                    <span>Full guide</span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </div>

            <!-- 4-Step Visual Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <!-- Step 1 -->
                <div class="card-ledger p-5 bg-[var(--bg-surface)] space-y-2">
                    <span class="font-serif text-2xl font-bold text-[var(--secondary-text)]">01</span>
                    <h3 class="font-serif text-base font-bold text-[var(--text-primary)]">Create Profile</h3>
                    <p class="text-xs text-[var(--text-muted)] font-medium">Values, education &amp; practice.</p>
                </div>

                <!-- Step 2 -->
                <div class="card-ledger p-5 bg-[var(--bg-surface)] space-y-2">
                    <span class="font-serif text-2xl font-bold text-[var(--secondary-text)]">02</span>
                    <h3 class="font-serif text-base font-bold text-[var(--text-primary)]">Get Matched</h3>
                    <p class="text-xs text-[var(--text-muted)] font-medium">Modest character-first discovery.</p>
                </div>

                <!-- Step 3 -->
                <div class="card-ledger p-5 bg-[var(--bg-surface)] space-y-2 border-[var(--border-gold)]">
                    <span class="font-serif text-2xl font-bold text-[var(--secondary-text)]">03</span>
                    <h3 class="font-serif text-base font-bold text-[var(--text-primary)]">Family Approves</h3>
                    <p class="text-xs text-[var(--text-muted)] font-medium">Wali review before contact.</p>
                </div>

                <!-- Step 4 -->
                <div class="card-ledger p-5 bg-[var(--accent-subtle)] border-[var(--accent-border)] space-y-2">
                    <span class="font-serif text-2xl font-bold text-[var(--accent-text)]">04</span>
                    <h3 class="font-serif text-base font-bold text-[var(--accent-text)]">Say Nikah</h3>
                    <p class="text-xs text-[var(--accent-text)] font-medium">Families meet with Du&rsquo;a.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ================================================================= -->
    <!-- 3. INTERACTIVE FEATURES SHOWCASE (MOCK UI, ONE LINE EACH)        -->
    <!-- ================================================================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="card-ledger p-6 sm:p-10 bg-[var(--bg-surface)] space-y-8"
             x-data="{ activeTab: 'modesty' }">
            <div class="max-w-2xl">
                <span class="text-xs font-bold uppercase tracking-wider text-[var(--secondary-text)] block">Platform Pillars</span>
                <h2 class="text-3xl font-serif font-bold text-[var(--text-primary)] mt-1">
                    Engineered for Modesty and Trust
                </h2>
            </div>

            <!-- Tab Buttons -->
            <div class="flex flex-wrap gap-2 border-b border-[var(--border-subtle)] pb-3">
                <button type="button"
                        @click="activeTab = 'modesty'"
                        :class="activeTab === 'modesty' ? 'btn-primary h-10 px-5 text-xs font-semibold' : 'btn-secondary h-10 px-5 text-xs font-semibold'"
                        class="cursor-pointer transition">
                    Haya Photo Privacy
                </button>
                <button type="button"
                        @click="activeTab = 'wali'"
                        :class="activeTab === 'wali' ? 'btn-primary h-10 px-5 text-xs font-semibold' : 'btn-secondary h-10 px-5 text-xs font-semibold'"
                        class="cursor-pointer transition">
                    Guardian (Wali) Console
                </button>
                <button type="button"
                        @click="activeTab = 'shield'"
                        :class="activeTab === 'shield' ? 'btn-primary h-10 px-5 text-xs font-semibold' : 'btn-secondary h-10 px-5 text-xs font-semibold'"
                        class="cursor-pointer transition">
                    Contact Info Shield
                </button>
            </div>

            <!-- Tab 1 Mock: Modesty -->
            <div x-show="activeTab === 'modesty'" x-cloak class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div class="space-y-3">
                    <h3 class="font-serif text-2xl font-bold text-[var(--text-primary)]">Photos stay blurred until mutual consent.</h3>
                    <p class="text-sm text-[var(--text-secondary)] leading-relaxed">
                        Character, piety, and life goals come first. You control exactly when photographs become visible.
                    </p>
                    <div class="pt-2">
                        <span class="badge-status text-[var(--accent-text)] bg-[var(--accent-subtle)] border border-[var(--accent-border)] text-xs">
                            100% Modesty Protected
                        </span>
                    </div>
                </div>
                <div class="p-5 rounded-[var(--radius-md)] bg-[var(--bg-canvas)] border border-[var(--border-default)] shadow-xs flex items-center justify-center">
                    <div class="text-center space-y-2">
                        <div class="w-20 h-24 mx-auto mihrab-arch bg-[var(--neutral-900)] flex items-center justify-center text-white font-serif text-2xl relative">
                            <span class="photo-blur">Photo</span>
                            <span class="absolute text-[10px] bg-black/80 text-white px-2 py-0.5 rounded">Protected</span>
                        </div>
                        <p class="text-xs font-semibold text-[var(--text-primary)]">Identity Verified &bull; Blurred by Default</p>
                    </div>
                </div>
            </div>

            <!-- Tab 2 Mock: Wali -->
            <div x-show="activeTab === 'wali'" x-cloak class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div class="space-y-3">
                    <h3 class="font-serif text-2xl font-bold text-[var(--text-primary)]">Your guardian reviews every suitor first.</h3>
                    <p class="text-sm text-[var(--text-secondary)] leading-relaxed">
                        Wali accounts are free forever. Introduce candidates with family dignity and peace of mind.
                    </p>
                    <div class="pt-2">
                        <span class="badge-status text-[var(--secondary-text)] bg-[var(--secondary-subtle)] border border-[var(--secondary-border)] text-xs">
                            Free Wali Accounts
                        </span>
                    </div>
                </div>
                <div class="p-5 rounded-[var(--radius-md)] bg-[var(--bg-canvas)] border border-[var(--border-default)] shadow-xs space-y-3">
                    <div class="flex items-center justify-between text-xs font-semibold">
                        <span>Guardian Approval Queue</span>
                        <span class="badge-status text-[var(--color-warning)] bg-[var(--color-warning-bg)]">1 Pending</span>
                    </div>
                    <div class="p-3 bg-[var(--bg-surface)] rounded-[var(--radius-sm)] border border-[var(--border-subtle)] flex items-center justify-between text-xs">
                        <span>Dr. Tariq &bull; Suitor Introduction</span>
                        <span class="text-[var(--accent-text)] font-semibold">Approve &rarr;</span>
                    </div>
                </div>
            </div>

            <!-- Tab 3 Mock: Contact Shield -->
            <div x-show="activeTab === 'shield'" x-cloak class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div class="space-y-3">
                    <h3 class="font-serif text-2xl font-bold text-[var(--text-primary)]">Phone numbers stay shielded.</h3>
                    <p class="text-sm text-[var(--text-secondary)] leading-relaxed">
                        Message in a safe chaperoned environment without sharing phone numbers or personal handles prematurely.
                    </p>
                    <div class="pt-2">
                        <span class="badge-status text-[var(--color-success)] bg-[var(--color-success-bg)] border border-[var(--color-success-border)] text-xs">
                            Zero Unsolicited Contact
                        </span>
                    </div>
                </div>
                <div class="p-5 rounded-[var(--radius-md)] bg-[var(--bg-canvas)] border border-[var(--border-default)] shadow-xs text-center space-y-2">
                    <svg class="w-8 h-8 mx-auto text-[var(--brand-primary)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <p class="text-xs font-semibold text-[var(--text-primary)]">Contact Shield Active</p>
                    <p class="text-[11px] text-[var(--text-muted)]">Direct exchange requires mutual family clearance.</p>
                </div>
            </div>

        </div>
    </section>

    <!-- ================================================================= -->
    <!-- 4. SOCIAL PROOF: NIKAH MUBARAK COUPLE STORIES (HORIZONTAL SCROLL) -->
    <!-- ================================================================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="border-t border-[var(--border-default)] pt-12 space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-[var(--secondary-text)] block">Nikah Mubarak</span>
                    <h2 class="text-3xl font-serif font-bold text-[var(--text-primary)] mt-1">Real Marriages on the Sunnah</h2>
                </div>
                <a href="{{ route('marriages.celebration') }}" class="text-xs font-semibold text-[var(--accent-text)] hover:underline flex items-center gap-1">
                    <span>View all stories</span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </div>

            <!-- Horizontal Scroll Strip on Mobile, 3-Card Grid on Desktop -->
            <div class="flex sm:grid sm:grid-cols-3 gap-5 overflow-x-auto pb-4 sm:pb-0 scroll-smooth snap-x">
                <!-- Story 1 -->
                <div class="card-ledger p-5 bg-[var(--bg-surface)] shrink-0 w-[280px] sm:w-auto snap-start space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-14 mihrab-arch bg-[var(--accent-subtle)] text-[var(--accent-text)] font-serif font-bold flex items-center justify-center shrink-0 border border-[var(--accent-border)]">
                            T &amp; A
                        </div>
                        <div>
                            <h3 class="font-serif text-base font-bold text-[var(--text-primary)]">Tariq &amp; Amina</h3>
                            <p class="text-xs text-[var(--text-muted)]">London, UK &bull; Nikah Sep 2026</p>
                        </div>
                    </div>
                    <p class="text-xs text-[var(--text-secondary)] italic leading-relaxed">
                        &ldquo;Our families communicated with clarity from the very start. No awkwardness, just pure respect.&rdquo;
                    </p>
                </div>

                <!-- Story 2 -->
                <div class="card-ledger p-5 bg-[var(--bg-surface)] shrink-0 w-[280px] sm:w-auto snap-start space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-14 mihrab-arch bg-[var(--secondary-subtle)] text-[var(--secondary-text)] font-serif font-bold flex items-center justify-center shrink-0 border border-[var(--secondary-border)]">
                            Z &amp; M
                        </div>
                        <div>
                            <h3 class="font-serif text-base font-bold text-[var(--text-primary)]">Zayd &amp; Maryam</h3>
                            <p class="text-xs text-[var(--text-muted)]">Toronto, Canada &bull; Nikah Aug 2026</p>
                        </div>
                    </div>
                    <p class="text-xs text-[var(--text-secondary)] italic leading-relaxed">
                        &ldquo;Photo privacy gave us space to focus on values and deen compatibility first.&rdquo;
                    </p>
                </div>

                <!-- Story 3 -->
                <div class="card-ledger p-5 bg-[var(--bg-surface)] shrink-0 w-[280px] sm:w-auto snap-start space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-14 mihrab-arch bg-[var(--accent-subtle)] text-[var(--accent-text)] font-serif font-bold flex items-center justify-center shrink-0 border border-[var(--accent-border)]">
                            B &amp; F
                        </div>
                        <div>
                            <h3 class="font-serif text-base font-bold text-[var(--text-primary)]">Bilal &amp; Fatima</h3>
                            <p class="text-xs text-[var(--text-muted)]">Dubai, UAE &bull; Nikah Oct 2026</p>
                        </div>
                    </div>
                    <p class="text-xs text-[var(--text-secondary)] italic leading-relaxed">
                        &ldquo;The single marriage pass was honest. Pay once, find your spouse, and complete with Du&rsquo;a.&rdquo;
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ================================================================= -->
    <!-- 5. RECENT CANDIDATES: SCANNABLE MIHRAB ARCH CARDS                 -->
    <!-- ================================================================= -->
    @if($recentProfiles->isNotEmpty())
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="border-t border-[var(--border-default)] pt-12 pb-4 mb-6 flex items-baseline justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-[var(--secondary-text)] block">Directory</span>
                    <h2 class="text-3xl font-serif font-bold text-[var(--text-primary)] mt-1">Recently Verified Candidates</h2>
                </div>
                <a href="{{ route('discovery.index') }}" class="text-xs font-semibold text-[var(--accent-text)] hover:underline flex items-center gap-1">
                    <span>Browse all</span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                @foreach($recentProfiles->take(4) as $profile)
                    <article class="card-ledger p-4 bg-[var(--bg-surface)] flex flex-col justify-between hover-lift">
                        <div>
                            <div class="w-full h-52 mihrab-arch bg-[var(--neutral-900)] overflow-hidden border border-[var(--border-subtle)] relative">
                                @if($profile->primaryPhoto)
                                    <img src="{{ $profile->primaryPhoto->displayUrl() }}" alt="{{ $profile->user->name }}" class="w-full h-full object-cover photo-blur" loading="lazy">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-[var(--neutral-800)] text-[var(--neutral-300)] font-serif text-3xl">
                                        {{ substr($profile->user->name, 0, 1) }}
                                    </div>
                                @endif
                                <div class="absolute inset-x-0 bottom-0 p-2 bg-gradient-to-t from-black/80 to-transparent flex items-center justify-between text-[10px] text-white">
                                    <span>Photo blurred</span>
                                    @if($profile->user->is_verified)
                                        <span class="badge-verified bg-emerald-950/80 text-emerald-200 border-emerald-700/60">Verified</span>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-3 space-y-1">
                                <h3 class="font-serif text-base font-bold text-[var(--text-primary)] truncate">{{ $profile->user->name }}</h3>
                                <p class="text-xs text-[var(--text-secondary)]">{{ $profile->user->age }} yrs &bull; {{ $profile->city ?: 'Flexible' }}, {{ $profile->country ?: 'Worldwide' }}</p>
                                @if($profile->profession)
                                    <p class="text-xs text-[var(--text-muted)] truncate">{{ $profile->profession }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4 pt-2">
                            <a href="{{ route('discovery.show', $profile->user_id) }}" class="btn-secondary w-full h-10 text-xs font-semibold flex items-center justify-center gap-1">
                                <span>View profile</span>
                                <span aria-hidden="true">&rarr;</span>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    <!-- ================================================================= -->
    <!-- 6. PRICING: SINGLE MARRIAGE PASS (SCANNABLE LEDGER)               -->
    <!-- ================================================================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="border-t border-[var(--border-default)] pt-12 pb-6 space-y-6">
            <div class="flex items-baseline justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-[var(--secondary-text)] block">Single Pass</span>
                    <h2 class="text-3xl font-serif font-bold text-[var(--text-primary)] mt-1">Pay Once Until Nikah</h2>
                </div>
                <a href="{{ route('subscription.pricing') }}" class="text-xs font-semibold text-[var(--accent-text)] hover:underline flex items-center gap-1">
                    <span>Details</span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($plans as $plan)
                    <div class="card-ledger p-6 bg-[var(--bg-surface)] flex flex-col justify-between {{ $plan->monthly_price > 0 ? 'border-[var(--secondary-primary)] shadow-sm' : '' }}">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <h3 class="font-serif text-lg font-bold text-[var(--text-primary)]">{{ $plan->name }}</h3>
                                <span class="badge-status text-[10px] {{ $plan->monthly_price > 0 ? 'text-[var(--secondary-text)] bg-[var(--secondary-subtle)] border-[var(--secondary-border)]' : 'bg-[var(--neutral-100)] text-[var(--neutral-700)]' }}">
                                    {{ $plan->monthly_price > 0 ? 'One-time' : 'Free' }}
                                </span>
                            </div>
                            <p class="font-serif text-3xl font-bold text-[var(--text-primary)]">
                                ${{ number_format($plan->monthly_price, 0) }}
                            </p>
                            <p class="text-xs text-[var(--text-secondary)] leading-normal">
                                {{ $plan->description ?: 'Respectful matchmaking with verified introductions.' }}
                            </p>
                        </div>

                        <div class="pt-5 border-t border-[var(--border-subtle)] mt-5">
                            <a href="{{ route('register') }}" class="{{ $plan->monthly_price > 0 ? 'btn-primary' : 'btn-secondary' }} w-full h-11 text-xs font-semibold flex items-center justify-center">
                                <span>{{ $plan->monthly_price > 0 ? 'Select pass' : 'Get started' }}</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ================================================================= -->
    <!-- 7. BOLD FULL-WIDTH EMERALD CALL-TO-ACTION BAND                   -->
    <!-- ================================================================= -->
    <section class="section--dark relative overflow-hidden bg-gradient-to-br from-[#0B3D2E] via-[#083528] to-[#062B22] text-white py-10 sm:py-12 mb-0 border-0">
        <!-- Hand-built SVG Geometric Islamic Lattice in Corner (<8% opacity, away from text) -->
        <div class="pointer-events-none absolute -top-10 -right-10 w-72 h-72 sm:w-88 sm:h-88 opacity-[0.07] text-[#d4af37] select-none" aria-hidden="true">
            <svg viewBox="0 0 200 200" fill="none" stroke="currentColor" stroke-width="1.2" class="w-full h-full">
                <circle cx="100" cy="100" r="90" stroke-dasharray="2 3"/>
                <circle cx="100" cy="100" r="64"/>
                <polygon points="100,10 126,74 190,74 138,112 158,176 100,138 42,176 62,112 10,74 74,74" stroke-width="1"/>
                <polygon points="100,28 120,80 172,80 130,110 146,162 100,132 54,162 70,110 28,80 80,80" stroke-width="0.8"/>
                <rect x="58" y="58" width="84" height="84" transform="rotate(45 100 100)" stroke-width="0.8"/>
                <rect x="58" y="58" width="84" height="84" stroke-width="0.8"/>
            </svg>
        </div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <!-- Non-Religious Geometric Line Ornament Above Heading -->
            <div class="inline-flex items-center justify-center gap-3 text-[#d4af37] mb-1" aria-hidden="true">
                <span class="w-12 h-px bg-gradient-to-r from-transparent to-[#d4af37]/70"></span>
                <svg class="w-3.5 h-3.5 text-[#d4af37]/80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                    <rect x="7" y="7" width="10" height="10" transform="rotate(45 12 12)" />
                </svg>
                <span class="w-12 h-px bg-gradient-to-l from-transparent to-[#d4af37]/70"></span>
            </div>

            <!-- Clear, High-Contrast Heading & Sub-text -->
            <div class="space-y-3">
                <h2 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-bold text-white tracking-tight leading-tight">
                    Begin Your Journey to Nikah Today.
                </h2>
                <p class="text-base sm:text-lg text-[#E6EFEA] max-w-xl mx-auto font-normal leading-relaxed">
                    Registration takes under five minutes. Photos stay private, and guardian accounts are free forever.
                </p>
            </div>

            <!-- Row of 3 Small Trust Chips with Icons -->
            <div class="flex flex-wrap items-center justify-center gap-2.5 sm:gap-3 pt-1">
                <div class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-medium text-white border border-[#d4af37]/35 bg-white/5 backdrop-blur-xs">
                    <svg class="w-3.5 h-3.5 text-[#d4af37] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/>
                    </svg>
                    <span>Verified profiles</span>
                </div>
                <div class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-medium text-white border border-[#d4af37]/35 bg-white/5 backdrop-blur-xs">
                    <svg class="w-3.5 h-3.5 text-[#d4af37] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    <span>Wali approved</span>
                </div>
                <div class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-medium text-white border border-[#d4af37]/35 bg-white/5 backdrop-blur-xs">
                    <svg class="w-3.5 h-3.5 text-[#d4af37] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                        <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    <span>Photos stay private</span>
                </div>
            </div>

            <!-- Overlapping Member Avatars + International Community Indicator -->
            <div class="flex items-center justify-center gap-2.5 pt-1">
                <div class="flex items-center -space-x-2">
                    <div class="w-7 h-7 rounded-full bg-[#165B4C] border-2 border-[#062B22] flex items-center justify-center text-[10px] font-bold text-white shadow-2xs">
                        A
                    </div>
                    <div class="w-7 h-7 rounded-full bg-[#9E7232] border-2 border-[#062B22] flex items-center justify-center text-[10px] font-bold text-white shadow-2xs">
                        Y
                    </div>
                    <div class="w-7 h-7 rounded-full bg-[#1B4D3E] border-2 border-[#062B22] flex items-center justify-center text-[10px] font-bold text-white shadow-2xs">
                        M
                    </div>
                    <div class="w-7 h-7 rounded-full bg-[#78531E] border-2 border-[#062B22] flex items-center justify-center text-[10px] font-bold text-white shadow-2xs">
                        H
                    </div>
                </div>
                <span class="text-xs font-medium text-[#E6EFEA] tracking-normal">
                    Join members from around the world
                </span>
            </div>

            <!-- Buttons: Gold Primary (48px high, dark text, lift 2px, arrow +4px) & White Ghost Secondary -->
            <div class="pt-2 flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="btn-gold group">
                    <span>Create free profile</span>
                    <span class="btn-arrow" aria-hidden="true">&rarr;</span>
                </a>
                <a href="{{ route('how-it-works') }}" class="btn-dark-secondary">
                    <span>How it works</span>
                </a>
            </div>
        </div>
    </section>

</div>
@endsection

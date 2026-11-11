@extends('layouts.app')

@section('title', 'Cookie Policy — Global Nikkah')
@section('meta_description', 'Learn how Global Nikkah uses essential cookies and respects your choice regarding analytics and optional tracking.')
@section('canonical_url', route('cookie-policy'))
@section('robots', 'index, follow')

@section('content')
<div class="min-h-screen bg-[var(--bg-canvas)] text-[var(--text-primary)] py-10 sm:py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

        <!-- Header -->
        <div class="space-y-3">
            <nav class="flex items-center gap-2 text-xs text-[var(--text-muted)]" aria-label="Breadcrumb">
                <a href="{{ route('landing') }}" class="hover:text-[var(--text-primary)] transition">Home</a>
                <span>/</span>
                <span class="text-[var(--text-primary)] font-medium">Cookie Policy</span>
            </nav>

            <div class="card-ledger pattern-header p-6 sm:p-10 bg-[var(--bg-surface)]">
                <div class="space-y-2">
                    <span class="badge-status text-[var(--accent-text)] bg-[var(--accent-subtle)] border border-[var(--accent-border)] text-xs font-semibold">
                        Privacy &bull; Data Governance
                    </span>
                    <h1 class="font-serif text-3xl sm:text-4xl font-semibold text-[var(--text-primary)] tracking-tight">
                        Cookie Policy
                    </h1>
                    <p class="text-sm text-[var(--text-secondary)] max-w-2xl leading-relaxed">
                        We believe in complete transparency. We use a few essential cookies to keep you signed in securely, and we never use invasive tracking. You decide what cookies we can use.
                    </p>
                    <div class="pt-2 text-xs text-[var(--text-muted)]">
                        <span>Last updated: November 2026</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Policy Content -->
        <div class="card-ledger p-6 sm:p-10 bg-[var(--bg-surface)] space-y-8 text-sm leading-relaxed text-[var(--text-secondary)]">

            <!-- What are cookies -->
            <section class="space-y-3">
                <h2 class="font-serif text-xl font-semibold text-[var(--text-primary)]">
                    1. What Are Cookies?
                </h2>
                <p>
                    Cookies are small text files stored on your device when you browse websites. They help remember your preferences, keep you authenticated across pages, and ensure our security protections work properly.
                </p>
            </section>

            <hr class="border-[var(--border-subtle)]">

            <!-- Categories of cookies -->
            <section class="space-y-4">
                <h2 class="font-serif text-xl font-semibold text-[var(--text-primary)]">
                    2. Cookies We Use
                </h2>

                <div class="space-y-4">
                    <!-- Essential -->
                    <div class="p-4 rounded-[var(--radius-md)] bg-[var(--bg-canvas)] border border-[var(--border-default)] space-y-1.5">
                        <div class="flex items-center justify-between">
                            <h3 class="font-semibold text-sm text-[var(--text-primary)]">Essential Cookies (Always Active)</h3>
                            <span class="badge-status text-[var(--color-success)] bg-[var(--color-success-bg)] border border-[var(--color-success-border)] text-[10px]">
                                Strictly Required
                            </span>
                        </div>
                        <p class="text-xs text-[var(--text-secondary)] leading-relaxed">
                            These cookies are necessary for the website to function. They handle secure account authentication, CSRF form protection, theme selection (light or dark mode), and remember your cookie preferences. The site cannot function without them.
                        </p>
                        <p class="text-[11px] text-[var(--text-muted)] font-mono">
                            Examples: XSRF-TOKEN, global_nikkah_session, global_nikkah_cookie_consent
                        </p>
                    </div>

                    <!-- Analytics -->
                    <div class="p-4 rounded-[var(--radius-md)] bg-[var(--bg-canvas)] border border-[var(--border-default)] space-y-1.5">
                        <div class="flex items-center justify-between">
                            <h3 class="font-semibold text-sm text-[var(--text-primary)]">Analytics Cookies (Optional)</h3>
                            <span class="badge-status text-[var(--accent-text)] bg-[var(--accent-subtle)] border border-[var(--accent-border)] text-[10px]">
                                Off by Default
                            </span>
                        </div>
                        <p class="text-xs text-[var(--text-secondary)] leading-relaxed">
                            These help us understand how visitors interact with the site (e.g. which pages are visited most often and if visitors experience error messages). All collected data is aggregated and anonymized. They are never enabled without your consent.
                        </p>
                    </div>

                    <!-- Marketing -->
                    <div class="p-4 rounded-[var(--radius-md)] bg-[var(--bg-canvas)] border border-[var(--border-default)] space-y-1.5">
                        <div class="flex items-center justify-between">
                            <h3 class="font-semibold text-sm text-[var(--text-primary)]">Marketing &amp; Referral Cookies (Optional)</h3>
                            <span class="badge-status text-[var(--text-muted)] bg-[var(--bg-subtle)] border border-[var(--border-subtle)] text-[10px]">
                                Off by Default
                            </span>
                        </div>
                        <p class="text-xs text-[var(--text-secondary)] leading-relaxed">
                            These measure the performance of our community outreach campaigns and referrals. We do not display third-party advertisements or sell your browsing history to advertising brokers.
                        </p>
                    </div>
                </div>
            </section>

            <hr class="border-[var(--border-subtle)]">

            <!-- Changing preferences -->
            <section class="space-y-3">
                <h2 class="font-serif text-xl font-semibold text-[var(--text-primary)]">
                    3. Managing Your Choices
                </h2>
                <p>
                    Your consent choice is stored for 12 months. You can change your choices at any time by clicking the button below or using the &ldquo;Cookie settings&rdquo; link in the footer of every page.
                </p>
                <div class="pt-2">
                    <button type="button" onclick="if(window.NikahConsent){ window.NikahConsent.openSettings(); }" class="btn-primary h-11 px-5 text-xs font-semibold inline-flex items-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                        <span>Change Cookie Preferences</span>
                    </button>
                </div>
            </section>

            <hr class="border-[var(--border-subtle)]">

            <!-- Questions -->
            <section class="space-y-2">
                <h2 class="font-serif text-xl font-semibold text-[var(--text-primary)]">
                    4. Questions &amp; Contact
                </h2>
                <p>
                    If you have questions regarding our cookie practices, please read our <a href="{{ route('privacy') }}" class="text-[var(--brand-primary)] underline">Privacy Policy</a> or reach out through our <a href="{{ route('contact') }}" class="text-[var(--brand-primary)] underline">Contact page</a>.
                </p>
            </section>

        </div>

    </div>
</div>
@endsection


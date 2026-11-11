@extends('layouts.app')

@section('title', 'Membership & Marriage Pass — Global Nikkah')
@section('meta_description', 'Honorable, one-time membership pricing. Pay once and keep full matrimonial privileges until your marriage is confirmed.')
@section('canonical_url', route('subscription.pricing'))
@section('robots', 'index, follow')

@section('content')
<div class="py-12 bg-[var(--bg-canvas)] min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

        <!-- Editorial Header Section -->
        <div class="card-ledger pattern-header p-8 sm:p-12 bg-[var(--bg-surface)]">
            <div class="max-w-3xl space-y-4">
                <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-[var(--radius-sm)] bg-[var(--accent-subtle)] text-[var(--accent-text)] text-xs font-semibold">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><circle cx="12" cy="12" r="8"/><circle cx="12" cy="12" r="3"/></svg>
                    <span>Membership Plans & Tiers &bull; Single Marriage Pass</span>
                </div>
                <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-semibold text-[var(--text-primary)] tracking-tight leading-tight">
                    Single Marriage Pass: Active Until Nikah
                </h1>
                <p class="text-sm sm:text-base text-[var(--text-secondary)] leading-relaxed">
                    Pay once for verified introductions and guardian oversight. Your pass concludes naturally on your wedding day with zero recurring fees.
                </p>

                <!-- Core Pillars -->
                <div class="pt-4 grid grid-cols-1 sm:grid-cols-3 gap-4 border-t border-[var(--border-subtle)] text-xs text-[var(--text-secondary)]">
                    <div class="flex items-start gap-2.5">
                        <svg class="w-4 h-4 text-[var(--brand-primary)] shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                        <div>
                            <strong class="font-semibold text-[var(--text-primary)] block">Zero Recurring Billing</strong>
                            <span>Never an unexpected monthly renewal.</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <svg class="w-4 h-4 text-[var(--brand-primary)] shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                        <div>
                            <strong class="font-semibold text-[var(--text-primary)] block">Valid Until Marriage</strong>
                            <span>Active through every serious introduction.</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <svg class="w-4 h-4 text-[var(--brand-primary)] shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                        <div>
                            <strong class="font-semibold text-[var(--text-primary)] block">Guardian Included Free</strong>
                            <span>Wali accounts never cost a single penny.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-[var(--radius-md)] bg-[var(--color-success-bg)] border border-[var(--color-success-border)] text-xs text-[var(--color-success)] flex items-center gap-2.5">
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('info'))
            <div class="p-4 rounded-[var(--radius-md)] bg-[var(--bg-subtle)] border border-[var(--border-default)] text-xs text-[var(--text-secondary)] flex items-center gap-2.5">
                <svg class="w-4 h-4 text-[var(--accent-text)] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        @if(session('warning'))
            <div class="p-4 rounded-[var(--radius-md)] bg-[var(--accent-subtle)] border border-[var(--accent-border)] text-xs text-[var(--accent-text)] flex items-center gap-2.5">
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        <!-- Pricing Ledger Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-{{ min(4, max(3, count($plans))) }} gap-8 items-stretch">
            @foreach($plans as $plan)
                @php
                    $isCurrent = ($currentPlan === $plan->slug);
                    $isFree = $plan->isFree();
                    $isPopular = (bool) $plan->is_popular;
                @endphp

                <div class="card-ledger p-7 sm:p-8 bg-[var(--bg-surface)] flex flex-col justify-between relative transition duration-200 {{ $isPopular ? 'border-t-4 border-t-[var(--accent-gold)] shadow-md' : '' }} {{ $isCurrent ? 'ring-2 ring-[var(--brand-primary)]' : '' }}">
                    @if($plan->badge_text)
                        <div class="absolute -top-3 left-6 px-3 py-0.5 rounded-[var(--radius-xs)] text-[10px] font-semibold uppercase tracking-wider {{ $isPopular ? 'bg-[var(--accent-gold)] text-white' : 'bg-[var(--brand-primary)] text-white' }}">
                            {{ $plan->badge_text }}
                        </div>
                    @endif

                    <div>
                        <!-- Tier Subtitle / Status -->
                        <div class="mb-3">
                            @if($isCurrent)
                                <span class="badge-status text-[var(--color-success)] bg-[var(--color-success-bg)] border border-[var(--color-success-border)] text-[10px]">
                                    Current Active Pass
                                </span>
                            @elseif($isFree)
                                <span class="badge-status text-[var(--text-muted)] bg-[var(--bg-canvas)] border border-[var(--border-subtle)] text-[10px]">
                                    Free Explorer Tier
                                </span>
                            @else
                                <span class="badge-status text-[var(--accent-text)] bg-[var(--accent-subtle)] border border-[var(--accent-border)] text-[10px]">
                                    Single Marriage Pass
                                </span>
                            @endif
                        </div>

                        <h3 class="font-serif text-2xl font-semibold text-[var(--text-primary)]">
                            {{ $plan->name }}
                        </h3>
                        <p class="text-xs text-[var(--text-secondary)] mt-1.5 leading-relaxed min-h-[36px]">
                            {{ $plan->description }}
                        </p>

                        <!-- Price Section -->
                        <div class="my-6 pb-6 border-b border-[var(--border-subtle)]">
                            @if($isFree)
                                <div class="flex items-baseline gap-1.5">
                                    <span class="font-serif text-4xl font-semibold text-[var(--text-primary)]">$0</span>
                                    <span class="text-xs text-[var(--text-muted)]">/ always free</span>
                                </div>
                                <p class="text-[11px] text-[var(--text-muted)] mt-1.5">
                                    Build profile, browse verified candidates, receive introduction requests.
                                </p>
                            @else
                                <div class="space-y-1.5">
                                    <div class="flex items-baseline gap-2">
                                        <span class="font-serif text-4xl font-semibold text-[var(--text-primary)]">
                                            ${{ number_format($plan->monthly_price, 2) }}
                                        </span>
                                        <span class="badge-gold text-[10px]">
                                            One-Time Payment
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-xs font-medium text-[var(--brand-primary)]">
                                        <svg class="w-3.5 h-3.5 text-[var(--accent-gold)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
                                        <span>Active Until Confirmed Nikah</span>
                                    </div>
                                    <p class="text-[11px] text-[var(--text-muted)]">
                                        No recurring fees. Automatically completes when your marriage is finalized.
                                    </p>
                                </div>
                            @endif
                        </div>

                        <!-- Feature List -->
                        <ul class="space-y-3 text-xs text-[var(--text-secondary)]">
                            @if(!empty($plan->features) && count($plan->features) > 0)
                                @foreach($plan->features as $featureItem)
                                    <li class="flex items-start gap-2.5">
                                        <svg class="w-4 h-4 text-[var(--brand-primary)] shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                        <span class="leading-relaxed">{{ $featureItem }}</span>
                                    </li>
                                @endforeach
                            @else
                                <li class="flex items-start gap-2.5">
                                    <svg class="w-4 h-4 text-[var(--brand-primary)] shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                    <span>{{ $plan->isUnlimitedViews() ? 'Unlimited daily profile views' : $plan->daily_profile_views . ' daily profile views' }}</span>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <svg class="w-4 h-4 text-[var(--brand-primary)] shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                    <span>{{ $plan->isUnlimitedInterests() ? 'Unlimited introduction requests' : $plan->daily_interests . ' daily introduction requests' }}</span>
                                </li>
                                @if($plan->advanced_filters)
                                    <li class="flex items-start gap-2.5">
                                        <svg class="w-4 h-4 text-[var(--brand-primary)] shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                        <span>Advanced religious criteria (Sect, Madhhab, Halal lifestyle)</span>
                                    </li>
                                @endif
                                @if($plan->profile_boost)
                                    <li class="flex items-start gap-2.5 text-[var(--accent-text)] font-medium">
                                        <svg class="w-4 h-4 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                        <span>Priority discovery highlight for matched seekers</span>
                                    </li>
                                @endif
                                @if($plan->see_who_viewed)
                                    <li class="flex items-start gap-2.5">
                                        <svg class="w-4 h-4 text-[var(--brand-primary)] shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                        <span>Profile visitor insights</span>
                                    </li>
                                @endif
                                @if($plan->dedicated_advisor)
                                    <li class="flex items-start gap-2.5 text-[var(--accent-text)] font-medium">
                                        <svg class="w-4 h-4 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                        <span>Personal matrimonial advisor consultation</span>
                                    </li>
                                @endif
                            @endif
                        </ul>
                    </div>

                    <!-- CTA Action Button -->
                    <div class="mt-8 pt-4 border-t border-[var(--border-subtle)]">
                        @if($isCurrent)
                            <button disabled class="w-full h-11 rounded-[var(--radius-md)] bg-[var(--bg-canvas)] border border-[var(--border-default)] text-xs font-semibold text-[var(--text-muted)] cursor-not-allowed">
                                Current Active Pass
                            </button>
                        @elseif($isFree)
                            @if(Auth::guest())
                                <a href="{{ route('register') }}" class="btn-secondary w-full h-11 text-xs font-semibold text-center flex items-center justify-center">
                                    Register Free
                                </a>
                            @else
                                <form action="{{ route('subscription.cancel') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-secondary w-full h-11 text-xs font-semibold cursor-pointer">
                                        Switch to Free Tier
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('subscription.checkout', $plan->slug) }}" class="{{ $isPopular ? 'btn-primary' : 'btn-secondary' }} w-full h-12 text-sm font-bold text-center flex items-center justify-center gap-2">
                                <span>Get {{ $plan->name }}</span>
                                <span aria-hidden="true">&rarr;</span>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Ethical Single-Payment Security Guarantee -->
        <div class="card-ledger p-8 bg-[var(--bg-surface)] border-l-2 border-l-[var(--accent-gold)] space-y-4">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-[var(--radius-sm)] bg-[var(--accent-subtle)] text-[var(--accent-text)] flex items-center justify-center shrink-0 border border-[var(--accent-border)]">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                </div>
                <div class="space-y-1">
                    <h3 class="font-serif text-lg font-semibold text-[var(--text-primary)]">
                        Our Single Marriage Guarantee (One-Time Pass Guarantee)
                    </h3>
                    <p class="text-xs text-[var(--text-secondary)] leading-relaxed max-w-3xl">
                        When you acquire a pass, you will never receive an unannounced monthly charge or subscription penalty. Your privileges stay open as long as you are actively seeking, and complete automatically when you and your partner confirm your blessed Nikah.
                    </p>
                </div>
            </div>

            <div class="pt-4 border-t border-[var(--border-subtle)] flex flex-wrap items-center justify-between gap-4 text-xs text-[var(--text-muted)]">
                <div class="flex items-center gap-4">
                    <span class="inline-flex items-center gap-1.5 font-medium text-[var(--text-secondary)]">
                        <svg class="w-4 h-4 text-[var(--accent-text)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                        <span>Stripe &amp; Cards</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5 font-medium text-[var(--text-secondary)]">
                        <svg class="w-4 h-4 text-[var(--accent-text)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><rect x="2" y="2" width="20" height="20" rx="4"/><path d="m8 10 3 3 5-5"/></svg>
                        <span>PayPal Express</span>
                    </span>
                </div>
                <div class="text-[11px] flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <span>256-bit encrypted checkout &bull; Zero automatic renewals</span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

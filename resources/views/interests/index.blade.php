@extends('layouts.app')

@section('title', 'Interests & Inquiries • Global Nikkah')

@section('content')
<div class="py-8 sm:py-12 bg-[var(--bg-canvas)] min-h-screen" x-data="{ tab: 'received' }">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Header Card -->
        <div class="card-ledger pattern-header p-6 sm:p-7 bg-[var(--bg-surface)] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-[var(--secondary-text)] block">Matrimonial Introductions</span>
                <h1 class="text-2xl sm:text-3xl font-serif font-bold text-[var(--text-primary)] mt-0.5">Interests &amp; Inquiries</h1>
                <p class="text-xs text-[var(--text-secondary)] mt-1">Formal inquiries conducted with respect, transparency, and family oversight.</p>
            </div>

            <!-- Tab Switcher (44px touch targets) -->
            <div class="inline-flex rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-subtle)] p-0.5 text-xs font-semibold" role="tablist">
                <button type="button" @click="tab = 'received'" role="tab"
                        :class="tab === 'received' ? 'bg-[var(--bg-surface)] text-[var(--accent-text)] shadow-xs' : 'text-[var(--text-muted)] hover:text-[var(--text-primary)]'"
                        class="px-4 py-2.5 rounded-[var(--radius-xs)] transition cursor-pointer">
                    Received ({{ $received->count() }})
                </button>
                <button type="button" @click="tab = 'sent'" role="tab"
                        :class="tab === 'sent' ? 'bg-[var(--bg-surface)] text-[var(--accent-text)] shadow-xs' : 'text-[var(--text-muted)] hover:text-[var(--text-primary)]'"
                        class="px-4 py-2.5 rounded-[var(--radius-xs)] transition cursor-pointer">
                    Sent ({{ $sent->count() }})
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-[var(--radius-sm)] bg-[var(--color-success-bg)] border border-[var(--color-success-border)] text-xs text-[var(--color-success)] flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- ========================================================= -->
        <!-- RECEIVED TAB                                              -->
        <!-- ========================================================= -->
        <div x-show="tab === 'received'" class="space-y-4">
            @forelse($received as $interest)
                <article class="card-ledger p-5 bg-[var(--bg-surface)] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 hover-lift">
                    <div class="flex items-center gap-4 min-w-0">
                        <!-- Mihrab Arch Thumbnail -->
                        <a href="{{ route('discovery.show', $interest->sender_id) }}"
                           class="mihrab-arch w-16 h-20 bg-[var(--neutral-900)] overflow-hidden shrink-0 block border border-[var(--border-subtle)]"
                           title="Review Candidate Profile">
                            @if($interest->sender->profile?->primaryPhoto)
                                <img src="{{ $interest->sender->profile->primaryPhoto->displayUrl() }}" alt="{{ $interest->sender->name }}"
                                     class="w-full h-full object-cover photo-blur" loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-[var(--neutral-800)] text-white font-serif text-xl">
                                    {{ substr($interest->sender->name, 0, 1) }}
                                </div>
                            @endif
                        </a>

                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <a href="{{ route('discovery.show', $interest->sender_id) }}" class="font-serif font-bold text-base text-[var(--text-primary)] hover:underline">
                                    {{ $interest->sender->name }}
                                </a>
                                @if($interest->sender->is_verified)
                                    <span class="badge-verified">Verified</span>
                                @endif
                                @if($interest->sender->isPro())
                                    <span class="badge-gold">One-Time Pass</span>
                                @endif
                            </div>

                            <p class="text-xs text-[var(--text-secondary)] mt-0.5">
                                {{ $interest->sender->age }} yrs &bull; {{ $interest->sender->profile?->city ?? 'District flexible' }}, {{ $interest->sender->profile?->country ?? '' }} &bull;
                                <span>{{ $interest->sender->profile?->sect_madhhab ?? 'Practicing' }}</span>
                            </p>

                            @if($interest->message_note)
                                <p class="text-xs text-[var(--text-primary)] bg-[var(--bg-subtle)] p-2.5 rounded-[var(--radius-sm)] mt-2 italic border border-[var(--border-subtle)] leading-relaxed">
                                    &ldquo;{{ $interest->message_note }}&rdquo;
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Right Column: Actions & Status -->
                    <div class="flex items-center gap-2 w-full sm:w-auto justify-end flex-wrap pt-2 sm:pt-0 border-t sm:border-t-0 border-[var(--border-subtle)]">
                        <a href="{{ route('discovery.show', $interest->sender_id) }}" class="btn-secondary text-xs h-10 py-0">
                            <span>Review Biodata</span>
                        </a>

                        @if($interest->status === 'pending')
                            <form action="{{ route('interests.respond', $interest->id) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="action" value="accept">
                                <button type="submit" class="btn-primary text-xs font-bold h-11 px-4">
                                    <span>Accept Interest</span>
                                </button>
                            </form>
                            <form action="{{ route('interests.respond', $interest->id) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="action" value="decline">
                                <button type="submit" class="btn-secondary text-xs font-semibold h-11 px-3">
                                    <span>Decline</span>
                                </button>
                            </form>
                        @elseif($interest->status === 'accepted')
                            <span class="badge-status text-[var(--color-success)] bg-[var(--color-success-bg)] border border-[var(--color-success-border)]">
                                Mutual Consent
                            </span>
                            <a href="{{ $interest->conversation ? route('chat.show', $interest->conversation->id) : route('chat.user', $interest->sender_id) }}" class="btn-subtle text-xs h-10 py-0">
                                <span>Open Conversation &rarr;</span>
                            </a>
                        @elseif($interest->status === 'declined')
                            <span class="badge-status text-[var(--color-danger)] bg-[var(--color-danger-bg)] border border-[var(--color-danger-border)]">
                                Declined respectfully
                            </span>
                        @elseif($interest->status === 'closed_married')
                            <span class="badge-status text-[var(--accent-text)] bg-[var(--accent-subtle)] border border-[var(--accent-border)]">
                                Closed (Nikah Completed)
                            </span>
                        @else
                            <span class="badge-status text-[var(--text-muted)] bg-[var(--bg-subtle)] border border-[var(--border-default)] capitalize">
                                {{ $interest->status }}
                            </span>
                        @endif
                    </div>
                </article>
            @empty
                <div class="card-ledger p-12 text-center bg-[var(--bg-surface)] space-y-2">
                    <p class="text-sm font-serif text-[var(--text-primary)]">No interest requests received yet.</p>
                    <p class="text-xs text-[var(--text-muted)] max-w-sm mx-auto">
                        Your profile is visible to compatible candidates. Keeping your prayer habits and family preferences detailed helps families reach out.
                    </p>
                </div>
            @endforelse
        </div>

        <!-- ========================================================= -->
        <!-- SENT TAB                                                  -->
        <!-- ========================================================= -->
        <div x-show="tab === 'sent'" x-cloak class="space-y-4">
            @forelse($sent as $interest)
                <article class="card-ledger p-5 bg-[var(--bg-surface)] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 hover-lift">
                    <div class="flex items-center gap-4 min-w-0">
                        <a href="{{ route('discovery.show', $interest->recipient_id) }}"
                           class="mihrab-arch w-16 h-20 bg-[var(--neutral-900)] overflow-hidden shrink-0 block border border-[var(--border-subtle)]"
                           title="Review Candidate Profile">
                            @if($interest->recipient->profile?->primaryPhoto)
                                <img src="{{ $interest->recipient->profile->primaryPhoto->displayUrl() }}" alt="{{ $interest->recipient->name }}"
                                     class="w-full h-full object-cover photo-blur" loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-[var(--neutral-800)] text-white font-serif text-xl">
                                    {{ substr($interest->recipient->name, 0, 1) }}
                                </div>
                            @endif
                        </a>

                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <a href="{{ route('discovery.show', $interest->recipient_id) }}" class="font-serif font-bold text-base text-[var(--text-primary)] hover:underline">
                                    {{ $interest->recipient->name }}
                                </a>
                                @if($interest->recipient->is_verified)
                                    <span class="badge-verified">Verified</span>
                                @endif
                            </div>
                            <p class="text-xs text-[var(--text-secondary)] mt-0.5">
                                {{ $interest->recipient->age }} yrs &bull; {{ $interest->recipient->profile?->city ?? 'District flexible' }}, {{ $interest->recipient->profile?->country ?? '' }}
                            </p>
                            <span class="text-[11px] text-[var(--text-muted)] block mt-1">Dispatched on {{ $interest->created_at->format('d M Y') }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 flex-wrap w-full sm:w-auto justify-end pt-2 sm:pt-0 border-t sm:border-t-0 border-[var(--border-subtle)]">
                        <a href="{{ route('discovery.show', $interest->recipient_id) }}" class="btn-secondary text-xs h-10 py-0">
                            <span>Review Biodata</span>
                        </a>

                        @if($interest->status === 'accepted')
                            @if($interest->wali_approval_status === 'pending')
                                <span class="badge-status text-[var(--color-info)] bg-[var(--color-info-bg)] border border-[var(--color-info-border)]">
                                    Awaiting Guardian Approval
                                </span>
                            @else
                                <span class="badge-status text-[var(--color-success)] bg-[var(--color-success-bg)] border border-[var(--color-success-border)]">
                                    Mutual Consent
                                </span>
                                <a href="{{ $interest->conversation ? route('chat.show', $interest->conversation->id) : route('chat.user', $interest->recipient_id) }}" class="btn-subtle text-xs h-10 py-0">
                                    <span>Open Conversation</span>
                                </a>
                            @endif
                        @elseif($interest->status === 'pending')
                            <span class="badge-status text-[var(--secondary-text)] bg-[var(--secondary-subtle)] border border-[var(--secondary-border)]">
                                Pending Candidate Response
                            </span>
                        @elseif($interest->status === 'declined')
                            <span class="badge-status text-[var(--color-danger)] bg-[var(--color-danger-bg)] border border-[var(--color-danger-border)]">
                                Declined respectfully
                            </span>
                        @elseif($interest->status === 'closed_married')
                            <span class="badge-status text-[var(--accent-text)] bg-[var(--accent-subtle)] border border-[var(--accent-border)]">
                                Closed (Nikah Completed)
                            </span>
                        @else
                            <span class="badge-status text-[var(--text-muted)] bg-[var(--bg-subtle)] border border-[var(--border-default)] capitalize">
                                {{ $interest->status }}
                            </span>
                        @endif
                    </div>
                </article>
            @empty
                <div class="card-ledger p-12 text-center bg-[var(--bg-surface)] space-y-3">
                    <p class="text-sm font-serif text-[var(--text-primary)]">You have not sent any interest inquiries yet.</p>
                    <p class="text-xs text-[var(--text-muted)] max-w-sm mx-auto">Browse verified candidates and reach out with sincere intentions.</p>
                    <div class="pt-2">
                        <a href="{{ route('discovery.index') }}" class="btn-primary text-xs font-semibold px-5 py-2.5">
                            Browse Candidate Directory
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

    </div>
</div>
@endsection

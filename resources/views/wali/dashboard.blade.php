@extends('layouts.app')

@section('title', 'Guardian (Wali) Console - Global Nikkah')

@section('content')
<div class="py-8 bg-[var(--bg-canvas)] min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Header Ledger Card (Curved Floating Style) -->
        <div class="card-ledger pattern-header rounded-2xl sm:rounded-3xl p-6 sm:p-8 bg-[var(--bg-surface)] border-[var(--border-default)] shadow-xs">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="space-y-1.5">
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-[var(--radius-sm)] bg-[var(--accent-subtle)] text-[var(--accent-text)] text-xs font-semibold">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        <span>Guardian (Wali) Chaperone Console</span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-bold text-[var(--text-primary)]">
                        Family Oversight &amp; Matrimonial Approvals
                    </h1>
                    <p class="text-xs text-[var(--text-secondary)] max-w-xl leading-relaxed">
                        Shariah-aligned family oversight empowering trusted guardians to review candidate introductions, grant approvals, and chaperone serious inquiries.
                    </p>
                </div>
                <span class="badge-status text-[var(--color-success)] bg-[var(--color-success-bg)] border border-[var(--color-success-border)] self-start md:self-center shrink-0">
                    Oversight Active
                </span>
            </div>
        </div>

        <!-- Pending Interest Approvals (FR-4.3) -->
        <div class="space-y-3">
            <div class="flex items-center justify-between border-b border-[var(--border-subtle)] pb-2.5">
                <h2 class="text-sm font-bold text-[var(--text-primary)] uppercase tracking-wider flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[var(--color-warning)]"></span>
                    <span>Pending Interest Approvals Requiring Review</span>
                </h2>
                <span class="text-xs text-[var(--text-muted)]">{{ $pendingInterests->count() }} awaiting action</span>
            </div>

            @forelse($pendingInterests as $pi)
                <article class="card-ledger p-5 bg-[var(--bg-surface)] flex flex-col md:flex-row items-start md:items-center justify-between gap-5">
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="w-14 h-14 rounded-[var(--radius-sm)] bg-[var(--neutral-900)] overflow-hidden shrink-0 border border-[var(--border-default)]">
                            @if($pi->sender->profile?->primaryPhoto)
                                <img src="{{ $pi->sender->profile->primaryPhoto->displayUrl() }}" alt="{{ $pi->sender->name }}" class="w-full h-full object-cover" loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-[var(--neutral-800)] text-white font-bold text-xl">
                                    {{ substr($pi->sender->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <span class="text-[10px] font-bold text-[var(--accent-text)] uppercase tracking-wider block">Introduction Request for: {{ $pi->recipient->name }}</span>
                            <h3 class="font-bold text-sm text-[var(--text-primary)]">{{ $pi->sender->name }}</h3>
                            <p class="text-xs text-[var(--text-secondary)] mt-0.5">
                                {{ $pi->sender->age }} yrs &bull; {{ $pi->sender->profile?->city }}, {{ $pi->sender->profile?->country }} &bull;
                                <span>{{ $pi->sender->profile?->sect_madhhab }}</span>
                            </p>
                            @if($pi->message_note)
                                <p class="text-xs text-[var(--text-primary)] bg-[var(--bg-subtle)] p-2 rounded-[var(--radius-xs)] border border-[var(--border-subtle)] mt-2 italic">
                                    &ldquo;{{ $pi->message_note }}&rdquo;
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-2 w-full md:w-auto justify-end shrink-0 pt-2 md:pt-0 border-t md:border-t-0 border-[var(--border-subtle)]">
                        <a href="{{ route('discovery.show', $pi->sender_id) }}" target="_blank" class="btn-secondary text-xs h-9 py-0">
                            Inspect Profile
                        </a>
                        <form action="{{ route('wali.interests.approve', $pi->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-primary text-xs font-bold h-9 py-0">
                                Approve Request
                            </button>
                        </form>
                        <form action="{{ route('wali.interests.reject', $pi->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-secondary text-xs h-9 py-0 text-[var(--color-danger)] hover:bg-[var(--color-danger-bg)]">
                                Decline
                            </button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="card-ledger p-8 text-center text-xs text-[var(--text-muted)] bg-[var(--bg-surface)]">
                    No pending introduction requests awaiting guardian approval at this time.
                </div>
            @endforelse
        </div>

        <!-- My Linked Wards -->
        <div class="space-y-3">
            <h2 class="text-sm font-bold text-[var(--text-primary)] uppercase tracking-wider border-b border-[var(--border-subtle)] pb-2.5">My Linked Wards</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($wards as $wardLink)
                    <div class="card-ledger p-4 bg-[var(--bg-surface)] flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-[var(--radius-sm)] bg-[var(--accent-subtle)] text-[var(--accent-text)] font-bold flex items-center justify-center text-sm border border-[var(--accent-border)]">
                                {{ substr($wardLink->seeker->name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-sm text-[var(--text-primary)]">{{ $wardLink->seeker->name }}</h3>
                                <p class="text-xs text-[var(--text-muted)]">Relationship: <span class="capitalize font-semibold text-[var(--text-primary)]">{{ $wardLink->relationship_type }}</span></p>
                            </div>
                        </div>
                        <a href="{{ route('discovery.show', $wardLink->seeker_user_id) }}" class="text-xs font-semibold text-[var(--accent-text)] hover:underline">
                            View Ward &rarr;
                        </a>
                    </div>
                @empty
                    <div class="col-span-full card-ledger p-6 text-center text-xs text-[var(--text-muted)] bg-[var(--bg-surface)]">
                        No linked wards currently registered under your email address.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Chaperoned Conversations Under My Oversight -->
        <div class="space-y-3">
            <h2 class="text-sm font-bold text-[var(--text-primary)] uppercase tracking-wider border-b border-[var(--border-subtle)] pb-2.5">Chaperoned Conversations</h2>
            <div class="card-ledger bg-[var(--bg-surface)] divide-y divide-[var(--border-subtle)] overflow-hidden">
                @forelse($chaperonedConversations as $conv)
                    <a href="{{ route('chat.show', $conv->id) }}" class="p-4 flex items-center justify-between hover:bg-[var(--bg-subtle)] transition block">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-sm text-[var(--text-primary)]">
                                    {{ $conv->participants->pluck('name')->join(' & ') }}
                                </span>
                                <span class="badge-status text-[var(--accent-text)] bg-[var(--accent-subtle)] border border-[var(--accent-border)] text-[10px]">
                                    Observing as Wali
                                </span>
                            </div>
                            <p class="text-xs text-[var(--text-muted)] mt-0.5">
                                Last message: {{ $conv->latestMessage?->body ?? 'Chat active' }}
                            </p>
                        </div>
                        <span class="text-xs font-semibold text-[var(--accent-text)]">Enter Room &rarr;</span>
                    </a>
                @empty
                    <div class="p-8 text-center text-xs text-[var(--text-muted)]">
                        No active chaperoned conversations yet.
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection

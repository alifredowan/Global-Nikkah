@extends('layouts.app')

@section('title', 'Guardian (Wali) Console — Global Nikkah')

@section('content')
<div class="py-8 bg-[var(--bg-canvas)] min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Header Card -->
        <div class="card-ledger pattern-header p-6 sm:p-8 bg-[var(--bg-surface)]">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-[var(--radius-sm)] bg-[var(--accent-subtle)] text-[var(--accent-text)] text-xs font-semibold">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        <span>Guardian (Wali) Chaperone Portal</span>
                    </div>
                    <h1 class="font-serif text-2xl sm:text-3xl font-semibold text-[var(--text-primary)] tracking-tight">
                        Family Oversight &amp; Introduction Reviews
                    </h1>
                    <p class="text-xs text-[var(--text-secondary)] max-w-xl leading-relaxed">
                        Review candidate introductions, approve proposals, and chaperone serious matrimonial inquiries with dignity.
                    </p>
                </div>
                <div class="shrink-0 self-start md:self-center">
                    <span class="badge-status text-[var(--color-success)] bg-[var(--color-success-bg)] border border-[var(--color-success-border)]">
                        Oversight Active
                    </span>
                </div>
            </div>
        </div>

        @if(session('status'))
            <div class="p-4 rounded-[var(--radius-md)] bg-[var(--color-success-bg)] border border-[var(--color-success-border)] text-xs text-[var(--color-success)] flex items-start gap-3">
                <svg class="w-4 h-4 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <!-- Pending Interest Approvals (FR-4.3) -->
        <div class="space-y-4">
            <div class="flex items-center justify-between border-b border-[var(--border-subtle)] pb-3">
                <div class="flex items-center gap-2.5">
                    <span class="w-2 h-2 rounded-full bg-[var(--color-warning)]"></span>
                    <h2 class="font-serif text-lg font-semibold text-[var(--text-primary)]">
                        Pending Introduction Requests Requiring Review
                    </h2>
                </div>
                <span class="text-xs text-[var(--text-muted)]">{{ $pendingInterests->count() }} awaiting your decision</span>
            </div>

            @forelse($pendingInterests as $pi)
                <article class="card-ledger p-5 sm:p-6 bg-[var(--bg-surface)] flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                    <div class="flex items-start sm:items-center gap-4 min-w-0">
                        <div class="w-16 h-20 mihrab-arch bg-[var(--bg-subtle)] overflow-hidden shrink-0 border border-[var(--border-default)] shadow-xs">
                            @if($pi->sender->profile?->primaryPhoto)
                                <img src="{{ $pi->sender->profile->primaryPhoto->displayUrl() }}" alt="{{ $pi->sender->name }}" class="w-full h-full object-cover" loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-[var(--neutral-200)] dark:bg-[var(--neutral-800)] text-[var(--text-muted)] font-serif font-bold text-xl">
                                    {{ substr($pi->sender->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="min-w-0 space-y-1">
                            <span class="text-[10px] font-semibold text-[var(--accent-text)] uppercase tracking-wider block">
                                Introduction Request for Ward: {{ $pi->recipient->name }}
                            </span>
                            <h3 class="font-serif text-lg font-semibold text-[var(--text-primary)] leading-tight">
                                {{ $pi->sender->name }}
                            </h3>
                            <p class="text-xs text-[var(--text-secondary)]">
                                {{ $pi->sender->age }} yrs &bull; {{ $pi->sender->profile?->city ?? 'City' }}, {{ $pi->sender->profile?->country ?? 'Country' }}
                                @if($pi->sender->profile?->sect_madhhab)
                                    &bull; <span>{{ $pi->sender->profile->sect_madhhab }}</span>
                                @endif
                                @if($pi->sender->profile?->profession)
                                    &bull; <span>{{ $pi->sender->profile->profession }}</span>
                                @endif
                            </p>
                            @if($pi->message_note)
                                <div class="text-xs text-[var(--text-primary)] bg-[var(--bg-canvas)] p-3 rounded-[var(--radius-sm)] border border-[var(--border-subtle)] mt-2 italic">
                                    &ldquo;{{ $pi->message_note }}&rdquo;
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5 w-full md:w-auto justify-end shrink-0 pt-3 md:pt-0 border-t md:border-t-0 border-[var(--border-subtle)]">
                        <a href="{{ route('discovery.show', $pi->sender_id) }}" target="_blank" class="btn-secondary text-xs h-11 px-4 inline-flex items-center">
                            Review Profile
                        </a>
                        <form action="{{ route('wali.interests.approve', $pi->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-primary text-xs font-bold h-11 px-5">
                                Approve Request
                            </button>
                        </form>
                        <form action="{{ route('wali.interests.reject', $pi->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-secondary text-xs h-11 px-3 text-[var(--color-danger)] hover:bg-[var(--color-danger-bg)] inline-flex items-center">
                                Decline
                            </button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="card-ledger p-10 text-center bg-[var(--bg-surface)]">
                    <p class="font-serif text-base text-[var(--text-secondary)]">No pending introduction requests</p>
                    <p class="text-xs text-[var(--text-muted)] mt-1 max-w-md mx-auto">
                        When suitors express interest in your wards, their requests will appear here for your careful review before any communication begins.
                    </p>
                </div>
            @endforelse
        </div>

        <!-- My Linked Wards -->
        <div class="space-y-4">
            <div class="border-b border-[var(--border-subtle)] pb-3">
                <h2 class="font-serif text-lg font-semibold text-[var(--text-primary)]">
                    My Linked Wards
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($wards as $wardLink)
                    <div class="card-ledger p-5 bg-[var(--bg-surface)] flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3.5 min-w-0">
                            <div class="w-11 h-11 rounded-[var(--radius-sm)] bg-[var(--accent-subtle)] text-[var(--accent-text)] font-serif font-bold flex items-center justify-center text-base border border-[var(--accent-border)] shrink-0">
                                {{ substr($wardLink->seeker->name, 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-semibold text-sm text-[var(--text-primary)] truncate">{{ $wardLink->seeker->name }}</h3>
                                <p class="text-xs text-[var(--text-muted)] mt-0.5">
                                    Relationship: <span class="capitalize font-medium text-[var(--text-secondary)]">{{ $wardLink->relationship_type }}</span>
                                    &bull;
                                    <span class="capitalize">{{ str_replace('_', ' ', $wardLink->permission_level) }}</span>
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('discovery.show', $wardLink->seeker_user_id) }}" class="text-xs font-semibold text-[var(--accent-text)] hover:text-[var(--brand-primary)] shrink-0 transition flex items-center gap-1">
                            <span>Profile</span>
                            <span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>
                @empty
                    <div class="col-span-full card-ledger p-8 text-center text-xs text-[var(--text-muted)] bg-[var(--bg-surface)]">
                        No linked wards currently registered under your email address.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Chaperoned Conversations Under My Oversight -->
        <div class="space-y-4">
            <div class="border-b border-[var(--border-subtle)] pb-3">
                <h2 class="font-serif text-lg font-semibold text-[var(--text-primary)]">
                    Chaperoned Conversations
                </h2>
            </div>

            <div class="card-ledger bg-[var(--bg-surface)] divide-y divide-[var(--border-subtle)] overflow-hidden">
                @forelse($chaperonedConversations as $conv)
                    <a href="{{ route('chat.show', $conv->id) }}" class="p-4 sm:p-5 flex items-center justify-between hover:bg-[var(--bg-canvas)] transition block group">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2.5">
                                <span class="font-semibold text-sm text-[var(--text-primary)] group-hover:text-[var(--brand-primary)] transition">
                                    {{ $conv->participants->pluck('name')->join(' & ') }}
                                </span>
                                <span class="badge-status text-[var(--accent-text)] bg-[var(--accent-subtle)] border border-[var(--accent-border)] text-[10px]">
                                    Observing as Wali
                                </span>
                            </div>
                            <p class="text-xs text-[var(--text-muted)] line-clamp-1">
                                Latest: {{ $conv->latestMessage?->body ?? 'Conversation active' }}
                            </p>
                        </div>
                        <span class="text-xs font-semibold text-[var(--accent-text)] group-hover:text-[var(--brand-primary)] shrink-0 flex items-center gap-1 transition">
                            <span>Enter Room</span>
                            <span aria-hidden="true">&rarr;</span>
                        </span>
                    </a>
                @empty
                    <div class="p-10 text-center text-xs text-[var(--text-muted)]">
                        <p class="font-serif text-sm text-[var(--text-secondary)]">No active chaperoned conversations</p>
                        <p class="text-xs text-[var(--text-muted)] mt-1">When your ward enters an approved discussion, you will have read access right here.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection

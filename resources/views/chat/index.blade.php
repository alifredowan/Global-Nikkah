@extends('layouts.app')

@section('title', 'Conversations — Global Nikkah')

@section('content')
<div class="py-8 bg-[var(--bg-canvas)] min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Header -->
        <div class="card-ledger pattern-header p-6 sm:p-8 bg-[var(--bg-surface)]">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1.5">
                    <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-[var(--radius-sm)] bg-[var(--accent-subtle)] text-[var(--accent-text)] text-xs font-semibold">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        <span>Chaperoned Communication</span>
                    </div>
                    <h1 class="font-serif text-2xl sm:text-3xl font-semibold text-[var(--text-primary)] tracking-tight">
                        Halal Conversations
                    </h1>
                    <p class="text-xs text-[var(--text-secondary)] max-w-xl leading-relaxed">
                        Private messaging unlocked only after mutual interest and guardian approval, preserving modesty and serious intent.
                    </p>
                </div>
                <div class="shrink-0 self-start sm:self-center">
                    <span class="badge-status text-[var(--color-success)] bg-[var(--color-success-bg)] border border-[var(--color-success-border)] flex items-center gap-1.5">
                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <span>Haya &amp; Safety Protected</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Conversations List -->
        <div class="card-ledger bg-[var(--bg-surface)] divide-y divide-[var(--border-subtle)] overflow-hidden">
            @forelse($conversations as $conversation)
                @php
                    $isChaperoneUser = $conversation->participants->where('id', Auth::id())->first()?->pivot?->role === 'wali_chaperone';
                    $other = $conversation->getOtherParticipant(Auth::user());
                    $hasWali = $conversation->participants->contains(fn($p) => $p->pivot->role === 'wali_chaperone');
                    $seekers = $conversation->participants->where('pivot.role', 'seeker');
                @endphp
                <a href="{{ route('chat.show', $conversation->id) }}" class="p-5 flex items-center justify-between gap-4 hover:bg-[var(--bg-canvas)] transition group block">
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="w-13 h-16 mihrab-arch bg-[var(--bg-subtle)] overflow-hidden shrink-0 border border-[var(--border-default)] shadow-2xs">
                            @if($other?->profile?->primaryPhoto)
                                <img src="{{ $other->profile->primaryPhoto->displayUrl() }}" alt="{{ $other->name }}" class="w-full h-full object-cover" loading="lazy" decoding="async">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-[var(--neutral-200)] dark:bg-[var(--neutral-800)] text-[var(--text-muted)] font-serif font-bold text-lg">
                                    {{ substr($other?->name ?? 'User', 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="min-w-0 space-y-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                @if($isChaperoneUser)
                                    <h3 class="font-serif text-base font-semibold text-[var(--text-primary)] group-hover:text-[var(--brand-primary)] transition">
                                        {{ $seekers->pluck('name')->join(' & ') }}
                                    </h3>
                                    <span class="badge-status text-[var(--accent-text)] bg-[var(--accent-subtle)] border border-[var(--accent-border)] text-[10px]">
                                        Observing as Guardian
                                    </span>
                                @else
                                    <h3 class="font-serif text-base font-semibold text-[var(--text-primary)] group-hover:text-[var(--brand-primary)] transition">
                                        {{ $other?->name ?? 'Candidate' }}
                                    </h3>
                                    @if($hasWali)
                                        <span class="badge-status text-[var(--accent-text)] bg-[var(--accent-subtle)] border border-[var(--accent-border)] text-[10px] flex items-center gap-1">
                                            <svg class="w-2.5 h-2.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                            <span>Wali Chaperoned</span>
                                        </span>
                                    @endif
                                @endif
                            </div>
                            <p class="text-xs text-[var(--text-secondary)] line-clamp-1">
                                {{ $conversation->latestMessage?->body ?? 'Conversation opened. Send your greeting...' }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right shrink-0 space-y-1">
                        <span class="text-[11px] text-[var(--text-muted)] block">
                            {{ $conversation->latestMessage?->created_at?->diffForHumans() ?? $conversation->created_at->diffForHumans() }}
                        </span>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-[var(--accent-text)] group-hover:text-[var(--brand-primary)] transition">
                            <span>Open</span>
                            <span aria-hidden="true">&rarr;</span>
                        </span>
                    </div>
                </a>
            @empty
                <div class="p-12 text-center text-xs text-[var(--text-muted)]">
                    <p class="font-serif text-base text-[var(--text-secondary)]">No active conversations yet</p>
                    <p class="mt-1 max-w-sm mx-auto text-xs leading-relaxed">
                        Direct messaging unlocks once an introduction request has been mutually accepted by both parties and approved by any designated guardian.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('discovery.index') }}" class="btn-primary h-12 px-6 text-sm font-bold inline-flex items-center gap-2">
                            <span>Discover Candidates</span>
                            <span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

    </div>
</div>
@endsection

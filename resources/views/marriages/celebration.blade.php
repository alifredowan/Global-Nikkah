@extends('layouts.app')

@section('title', 'Nikah Mubarak — Sacred Milestone — Global Nikkah')

@section('content')
<div class="py-12 bg-[var(--bg-canvas)] min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        @if(session('success'))
            <div class="p-4 rounded-[var(--radius-md)] bg-[var(--color-success-bg)] border border-[var(--color-success-border)] text-xs text-[var(--color-success)] flex items-center gap-2.5">
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-[var(--radius-md)] bg-[var(--color-danger-bg)] border border-[var(--color-danger-border)] text-xs text-[var(--color-danger)] flex items-center gap-2.5">
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(session('info'))
            <div class="p-4 rounded-[var(--radius-md)] bg-[var(--bg-subtle)] border border-[var(--border-default)] text-xs text-[var(--text-secondary)] flex items-center gap-2.5">
                <svg class="w-4 h-4 text-[var(--accent-text)] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        <!-- Sacred Celebration Card -->
        <div class="card-ledger pattern-header p-8 sm:p-12 bg-[var(--bg-surface)] text-center space-y-8 border-t-4 border-t-[var(--accent-gold)]">
            
            <!-- Sacred Sunnah Du'a (Typeset with Amiri & Cormorant Garamond) -->
            <div class="space-y-4 max-w-2xl mx-auto">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-[var(--radius-sm)] bg-[var(--accent-subtle)] text-[var(--accent-text)] text-xs font-semibold">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><circle cx="12" cy="12" r="8"/><circle cx="12" cy="12" r="3"/></svg>
                    <span>Sacred Nikah Milestone</span>
                </div>

                <div class="font-arabic text-2xl sm:text-3xl text-[var(--accent-text)] tracking-wider pt-2 leading-relaxed" dir="rtl">
                    بَارَكَ اللَّهُ لَكَ وَبَارَكَ عَلَيْكَ وَجَمَعَ بَيْنَكُمَا فِي خَيْرٍ
                </div>

                <p class="font-serif text-sm sm:text-base text-[var(--text-secondary)] italic">
                    &ldquo;May Allah bless you, and bestow His blessings upon you, and join you together in goodness.&rdquo;
                    <span class="block text-xs not-italic text-[var(--text-muted)] mt-1 font-sans">— Sunan Abi Dawud</span>
                </p>
            </div>

            <!-- Main Heading -->
            <div class="space-y-2">
                <h1 class="font-serif text-3xl sm:text-4xl font-semibold text-[var(--text-primary)] tracking-tight">
                    @if($marriage->isConfirmed())
                        Alhamdulillah, Nikah Mubarak
                    @else
                        Nikah Milestone Awaiting Mutual Confirmation
                    @endif
                </h1>
                <p class="text-xs sm:text-sm text-[var(--text-secondary)] max-w-xl mx-auto leading-relaxed">
                    @if($marriage->isConfirmed())
                        May Allah grant your home peace, tenderness, and unwavering support as you embark upon this honorable path of companionship.
                    @else
                        A sacred marriage declaration has been initiated and is awaiting mutual consent to finalize account safeguards.
                    @endif
                </p>
            </div>

            <!-- Couple Presentation Card -->
            <div class="card-ledger p-6 sm:p-8 bg-[var(--bg-canvas)] max-w-xl mx-auto flex items-center justify-around gap-4 border border-[var(--border-default)]">
                <!-- Groom -->
                <div class="text-center space-y-2.5">
                    <div class="w-18 h-24 sm:w-20 sm:h-28 mx-auto mihrab-arch bg-[var(--bg-subtle)] border border-[var(--border-default)] overflow-hidden shadow-xs flex items-center justify-center">
                        @if($marriage->groom->profile?->primaryPhoto)
                            <img src="{{ $marriage->groom->profile->primaryPhoto->displayUrl() }}" alt="{{ $marriage->groom->name }}" class="w-full h-full object-cover">
                        @else
                            <span class="font-serif text-2xl font-bold text-[var(--text-muted)]">{{ substr($marriage->groom->name, 0, 1) }}</span>
                        @endif
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-semibold text-[var(--accent-text)] tracking-wider block">Groom</span>
                        <strong class="font-serif text-base font-semibold text-[var(--text-primary)] block truncate max-w-[130px]">{{ $marriage->groom->name }}</strong>
                        @if($marriage->groom->profile?->city)
                            <span class="text-[11px] text-[var(--text-muted)] block">{{ $marriage->groom->profile->city }}</span>
                        @endif
                    </div>
                </div>

                <!-- Center Decorative Arch / Knot -->
                <div class="flex flex-col items-center gap-1.5 px-2">
                    <div class="w-10 h-10 rounded-full bg-[var(--accent-subtle)] text-[var(--accent-text)] border border-[var(--accent-border)] flex items-center justify-center">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><circle cx="12" cy="12" r="8"/><circle cx="12" cy="12" r="3"/></svg>
                    </div>
                    <span class="text-[10px] font-semibold uppercase tracking-wider text-[var(--accent-text)]">United in Nikah</span>
                    @if($marriage->marriage_date)
                        <span class="text-[11px] text-[var(--text-muted)] font-mono">{{ $marriage->marriage_date->format('M Y') }}</span>
                    @endif
                </div>

                <!-- Bride -->
                <div class="text-center space-y-2.5">
                    <div class="w-18 h-24 sm:w-20 sm:h-28 mx-auto mihrab-arch bg-[var(--bg-subtle)] border border-[var(--border-default)] overflow-hidden shadow-xs flex items-center justify-center">
                        @if($marriage->bride->profile?->primaryPhoto)
                            <img src="{{ $marriage->bride->profile->primaryPhoto->displayUrl() }}" alt="{{ $marriage->bride->name }}" class="w-full h-full object-cover">
                        @else
                            <span class="font-serif text-2xl font-bold text-[var(--text-muted)]">{{ substr($marriage->bride->name, 0, 1) }}</span>
                        @endif
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-semibold text-[var(--accent-text)] tracking-wider block">Bride</span>
                        <strong class="font-serif text-base font-semibold text-[var(--text-primary)] block truncate max-w-[130px]">{{ $marriage->bride->name }}</strong>
                        @if($marriage->bride->profile?->city)
                            <span class="text-[11px] text-[var(--text-muted)] block">{{ $marriage->bride->profile->city }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Pending Confirmation Form -->
            @if($marriage->isPending())
                <div class="max-w-lg mx-auto card-ledger p-6 bg-[var(--accent-subtle)] border border-[var(--accent-border)] text-center space-y-4">
                    @if($user->id !== $marriage->initiated_by_user_id)
                        <div class="space-y-1">
                            <span class="text-xs font-semibold text-[var(--text-primary)] block">
                                {{ $marriage->initiator->name }} has declared that you both completed your Nikah.
                            </span>
                            <p class="text-xs text-[var(--text-secondary)] leading-relaxed">
                                Confirming will finalize your marriage milestone, transition both profiles to Married, delist public discovery, and conclude single marriage passes.
                            </p>
                        </div>
                        <div class="flex items-center justify-center gap-3 pt-2">
                            <form action="{{ route('marriages.confirm', $marriage->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-primary h-12 px-6 text-sm font-bold cursor-pointer">
                                    Confirm Nikah Milestone
                                </button>
                            </form>
                            <form action="{{ route('marriages.decline', $marriage->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-secondary h-12 px-5 text-sm font-semibold cursor-pointer text-[var(--color-danger)] hover:bg-[var(--color-danger-bg)]">
                                    Decline
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="space-y-1 text-xs text-[var(--text-secondary)]">
                            <span class="font-semibold text-[var(--text-primary)] block">
                                Waiting for {{ $spouse ? $spouse->name : 'your spouse' }} to confirm your declaration.
                            </span>
                            <p class="text-[11px] text-[var(--text-muted)]">
                                A notification was delivered to your spouse. As soon as they confirm, your privacy safeguards and married profile status take effect automatically.
                            </p>
                        </div>
                    @endif
                </div>
            @endif

        </div>

        <!-- Post-Nikah Privacy Safeguards & Actions -->
        <div class="card-ledger p-6 sm:p-8 bg-[var(--bg-surface)] space-y-6">
            <div class="border-b border-[var(--border-subtle)] pb-4">
                <h2 class="font-serif text-lg font-semibold text-[var(--text-primary)]">
                    Honorable Safeguards &amp; Privacy Shield
                </h2>
                <p class="text-xs text-[var(--text-muted)] mt-1">
                    How Global Nikkah preserves your family's modesty once marriage is finalized.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div class="p-4 rounded-[var(--radius-md)] bg-[var(--bg-canvas)] border border-[var(--border-default)] flex items-start gap-3">
                    <div class="w-8 h-8 rounded-[var(--radius-sm)] bg-[var(--accent-subtle)] text-[var(--accent-text)] flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </div>
                    <div>
                        <strong class="font-semibold text-[var(--text-primary)] block">Delisted from Discovery</strong>
                        <p class="text-[var(--text-secondary)] mt-0.5 leading-relaxed">Both candidate profiles are removed from public search, matchmaking criteria, and visitor feeds.</p>
                    </div>
                </div>

                <div class="p-4 rounded-[var(--radius-md)] bg-[var(--bg-canvas)] border border-[var(--border-default)] flex items-start gap-3">
                    <div class="w-8 h-8 rounded-[var(--radius-sm)] bg-[var(--accent-subtle)] text-[var(--accent-text)] flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><line x1="9" y1="12" x2="15" y2="12"/></svg>
                    </div>
                    <div>
                        <strong class="font-semibold text-[var(--text-primary)] block">Past Inquiries Concluded</strong>
                        <p class="text-[var(--text-secondary)] mt-0.5 leading-relaxed">All pending introduction requests are closed courteously with a quiet message of Du'a.</p>
                    </div>
                </div>

                <div class="p-4 rounded-[var(--radius-md)] bg-[var(--bg-canvas)] border border-[var(--border-default)] flex items-start gap-3">
                    <div class="w-8 h-8 rounded-[var(--radius-sm)] bg-[var(--accent-subtle)] text-[var(--accent-text)] flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    </div>
                    <div>
                        <strong class="font-semibold text-[var(--text-primary)] block">Candidate Rooms Archived</strong>
                        <p class="text-[var(--text-secondary)] mt-0.5 leading-relaxed">Past chats with others are placed in read-only archive; your private thread with your spouse remains open.</p>
                    </div>
                </div>

                <div class="p-4 rounded-[var(--radius-md)] bg-[var(--bg-canvas)] border border-[var(--border-default)] flex items-start gap-3">
                    <div class="w-8 h-8 rounded-[var(--radius-sm)] bg-[var(--accent-subtle)] text-[var(--accent-text)] flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 14 14"/></svg>
                    </div>
                    <div>
                        <strong class="font-semibold text-[var(--text-primary)] block">Single Pass Completed</strong>
                        <p class="text-[var(--text-secondary)] mt-0.5 leading-relaxed">Your one-time marriage pass has fulfilled its sacred purpose without recurring fees or unexpected charges.</p>
                    </div>
                </div>
            </div>
        </div>

        @if($marriage->isConfirmed())
            <!-- Barakah Story Submission Form -->
            <div class="card-ledger p-6 sm:p-8 bg-[var(--bg-surface)] space-y-6">
                <div>
                    <span class="text-[10px] font-semibold uppercase tracking-wider text-[var(--accent-text)]">Community Inspiration</span>
                    <h2 class="font-serif text-xl font-semibold text-[var(--text-primary)] mt-1">
                        Share Your Barakah Story
                    </h2>
                    <p class="text-xs text-[var(--text-muted)] mt-1 leading-relaxed">
                        Inspire brothers and sisters seeking marriage with your experiences, the role of your Wali, and sincere advice for practicing families.
                    </p>
                </div>

                <form action="{{ route('marriages.story', $marriage->id) }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label for="story_title" class="block text-xs font-semibold text-[var(--text-primary)] mb-1.5">
                            Story Title
                        </label>
                        <input type="text" id="story_title" name="story_title" value="{{ old('story_title', $marriage->story_title ?? 'How We Found Halal Compatibility Through Global Nikkah') }}" required
                               class="w-full h-11 px-3.5 rounded-[var(--radius-md)] border border-[var(--border-default)] bg-[var(--bg-canvas)] text-sm text-[var(--text-primary)] focus:outline-none focus:border-[var(--brand-primary)]">
                    </div>

                    <div>
                        <label for="story_body" class="block text-xs font-semibold text-[var(--text-primary)] mb-1.5">
                            Your Story &amp; Advice for Fellow Seekers
                        </label>
                        <textarea id="story_body" name="story_body" rows="4" required placeholder="Share how your values aligned, how your families conducted meetings, and words of encouragement..."
                                  class="w-full p-3.5 rounded-[var(--radius-md)] border border-[var(--border-default)] bg-[var(--bg-canvas)] text-sm text-[var(--text-primary)] placeholder:text-[var(--text-muted)] focus:outline-none focus:border-[var(--brand-primary)]">{{ old('story_body', $marriage->story_body) }}</textarea>
                    </div>

                    <div class="flex items-center justify-between flex-wrap gap-4 pt-2">
                        <label class="flex items-center gap-2 text-xs text-[var(--text-secondary)] cursor-pointer">
                            <input type="checkbox" name="story_is_public" value="1" {{ old('story_is_public', $marriage->story_is_public ?? true) ? 'checked' : '' }}
                                   class="rounded border-[var(--border-default)] text-[var(--brand-primary)] focus:ring-[var(--brand-primary)]">
                            <span>Publish anonymously in Community Success Stories</span>
                        </label>

                        <button type="submit" class="btn-primary h-12 px-8 text-sm font-bold cursor-pointer">
                            Save Barakah Story
                        </button>
                    </div>
                </form>
            </div>
        @endif

    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', $targetUser->name . ' - Candidate Biodata - Global Nikkah')

@section('content')
<div class="py-8 bg-[var(--bg-canvas)] min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Back Link & Breadcrumb -->
        <div>
            <a href="{{ route('discovery.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[var(--text-muted)] hover:text-[var(--text-primary)] transition">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
                <span>Back to candidate directory</span>
            </a>
        </div>

        <!-- ========================================================= -->
        <!-- PROFILE HEADER LEDGER                                     -->
        <!-- ========================================================= -->
        <div class="card-ledger overflow-hidden bg-[var(--bg-surface)]">
            <!-- Subtle Header Strip with Faint Pattern -->
            <div class="h-24 sm:h-28 pattern-header border-b border-[var(--border-subtle)] relative flex items-center justify-end px-6">
                @if($compatibilityScore)
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-[var(--radius-sm)] bg-[var(--bg-surface)] border border-[var(--border-default)] text-xs font-bold text-[var(--accent-text)] shadow-xs">
                        <span class="w-2 h-2 rounded-full bg-[var(--accent-primary)]"></span>
                        <span>{{ $compatibilityScore }}% Deen Compatibility</span>
                    </div>
                @endif
            </div>

            <div class="px-6 pb-6 pt-0 relative">
                <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between gap-4 -mt-12 sm:-mt-14 mb-6">
                    <!-- Avatar & Legal Profile Identity -->
                    <div class="flex items-end gap-4">
                        <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-[var(--radius-md)] overflow-hidden border-2 border-[var(--bg-surface)] bg-[var(--neutral-900)] shadow-md relative shrink-0">
                            @if($profile->primaryPhoto)
                                <img src="{{ $profile->primaryPhoto->displayUrl() }}" alt="{{ $targetUser->name }}"
                                     class="w-full h-full object-cover {{ $isPhotoVisible ? '' : 'photo-blur' }}"
                                     loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-[var(--neutral-800)] text-[var(--neutral-300)] font-bold text-3xl">
                                    {{ substr($targetUser->name, 0, 1) }}
                                </div>
                            @endif

                            @if(!$isPhotoVisible)
                                <div class="absolute inset-0 flex items-center justify-center bg-black/50 text-[10px] text-white font-semibold text-center px-1">
                                    <span>Modesty Shield</span>
                                </div>
                            @endif
                        </div>

                        <div class="pb-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h1 class="text-xl sm:text-2xl font-bold text-[var(--text-primary)]">{{ $targetUser->name }}</h1>
                                @if($targetUser->is_verified)
                                    <span class="badge-verified">
                                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                                        <span>Verified Candidate</span>
                                    </span>
                                @endif
                                @if($targetUser->isPro())
                                    <span class="px-2 py-0.5 rounded-[var(--radius-sm)] text-[10px] font-bold bg-[var(--secondary-subtle)] text-[var(--secondary-text)] border border-[var(--secondary-border)]">
                                        Pro Member
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-[var(--text-secondary)] mt-0.5">
                                {{ $targetUser->age }} years &bull; {{ $profile->city ?: 'District flexible' }}, {{ $profile->country ?: '' }} &bull;
                                <span class="capitalize">{{ str_replace('_', ' ', $targetUser->marital_status ?? 'Single') }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- Header Secondary Actions: Shortlist, Biodata, Block -->
                    <div class="flex items-center gap-2 flex-wrap w-full sm:w-auto justify-start sm:justify-end">
                        @php $isBookmarked = $isBookmarked ?? false; @endphp
                        <form action="{{ route('bookmarks.toggle', $targetUser->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="btn-secondary text-xs h-9 py-0">
                                <svg class="w-3.5 h-3.5 {{ $isBookmarked ? 'text-[var(--secondary-primary)] fill-current' : 'text-[var(--text-muted)]' }}" viewBox="0 0 24 24" fill="{{ $isBookmarked ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
                                <span>{{ $isBookmarked ? 'Shortlisted' : 'Shortlist' }}</span>
                            </button>
                        </form>

                        <a href="{{ route('profile.biodata.show', $targetUser->id) }}" target="_blank"
                           class="btn-secondary text-xs h-9 py-0" title="Printable Shariah Biodata">
                            <svg class="w-3.5 h-3.5 text-[var(--text-muted)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                            <span>Print Biodata</span>
                        </a>

                        <form action="{{ route('blocked-users.store', $targetUser->id) }}" method="POST" onsubmit="return confirm('Block this profile? You will no longer see or contact this candidate.')" class="inline">
                            @csrf
                            <button type="submit" class="btn-secondary text-xs h-9 py-0 text-[var(--color-danger)] hover:bg-[var(--color-danger-bg)]" title="Block candidate">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" x2="19.07" y1="4.93" y2="19.07"/></svg>
                                <span>Block</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Primary Action Status Bar -->
                <div class="border-t border-[var(--border-subtle)] pt-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <div>
                        <span class="text-xs font-semibold text-[var(--text-secondary)]">Guardian Status:</span>
                        <span class="text-xs font-bold text-[var(--text-primary)] ml-1">
                            {{ $profile->wali_required || ($activeWaliLink ?? null) ? 'Chaperoned by Guardian (Wali)' : 'Self-managed candidate' }}
                        </span>
                    </div>

                    <div>
                        @if($conversation || ($existingInterest && $existingInterest->status === 'accepted'))
                            <div class="flex items-center gap-2">
                                <span class="badge-status text-[var(--color-success)] bg-[var(--color-success-bg)] border border-[var(--color-success-border)]">
                                    Interest Accepted
                                </span>
                                <a href="{{ $conversation ? route('chat.show', $conversation->id) : route('chat.user', $targetUser->id) }}" class="btn-primary text-xs font-bold h-9 py-0">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                    <span>Send Message</span>
                                </a>
                            </div>
                        @elseif($existingInterest && $existingInterest->status === 'pending')
                            @if($existingInterest->recipient_id === Auth::id())
                                <div class="flex items-center gap-2">
                                    <form action="{{ route('interests.respond', $existingInterest->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="action" value="accept">
                                        <button type="submit" class="btn-primary text-xs font-bold h-9 py-0">
                                            <span>Accept Interest</span>
                                        </button>
                                    </form>
                                    <form action="{{ route('interests.respond', $existingInterest->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="action" value="decline">
                                        <button type="submit" class="btn-secondary text-xs font-semibold h-9 py-0">
                                            <span>Decline</span>
                                        </button>
                                    </form>
                                </div>
                            @else
                                <span class="badge-status text-[var(--secondary-text)] bg-[var(--secondary-subtle)] border border-[var(--secondary-border)]">
                                    Interest Sent (Pending)
                                </span>
                            @endif
                        @elseif($existingInterest && $existingInterest->status === 'pending_wali')
                            <span class="badge-status text-[var(--color-info)] bg-[var(--color-info-bg)] border border-[var(--color-info-border)]">
                                Awaiting Guardian (Wali) Approval
                            </span>
                        @elseif($existingInterest)
                            <span class="badge-status text-[var(--text-muted)] bg-[var(--bg-subtle)] border border-[var(--border-default)] capitalize">
                                Status: {{ str_replace('_', ' ', $existingInterest->status) }}
                            </span>
                        @else
                            <button type="button" onclick="document.getElementById('sendInterestModal').classList.remove('hidden')" class="btn-primary text-xs font-bold h-9 py-0">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                                <span>Express Halal Interest</span>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Photo Privacy Modesty Callout -->
                @if(!$isPhotoVisible)
                    <div class="mt-4 p-3.5 rounded-[var(--radius-sm)] bg-[var(--bg-subtle)] border border-[var(--border-default)] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-xs">
                        <div class="flex items-start gap-2.5 text-[var(--text-secondary)]">
                            <svg class="w-4 h-4 text-[var(--text-muted)] shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            <div>
                                <strong class="text-[var(--text-primary)]">Photo Privacy Active</strong>
                                <p class="text-[11px] text-[var(--text-secondary)] mt-0.5">Photos are shielded until mutual interest is accepted or explicit reciprocal photo permission is granted.</p>
                            </div>
                        </div>

                        @if(Auth::user()->profile && ! $hasGrantedPhotoAccess)
                            <form action="{{ route('profile.photos.grant', $targetUser->id) }}" method="POST" class="shrink-0">
                                @csrf
                                <button type="submit" class="btn-secondary text-xs h-8 py-0 font-semibold">
                                    <span>Grant them photo access</span>
                                </button>
                            </form>
                        @elseif($hasGrantedPhotoAccess)
                            <span class="badge-status text-[var(--color-success)] bg-[var(--color-success-bg)] border border-[var(--color-success-border)] shrink-0">
                                Photo access granted
                            </span>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- INCOMING PENDING INTEREST CALLOUT (ASSERTED IN TESTS)     -->
        <!-- ========================================================= -->
        @if($existingInterest && $existingInterest->status === 'pending' && $existingInterest->recipient_id === Auth::id())
            <div class="card-ledger p-5 bg-[var(--accent-subtle)] border-[var(--accent-border)] flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="space-y-1">
                    <span class="badge-status text-[var(--accent-text)] bg-[var(--bg-surface)] border border-[var(--accent-border)] font-bold text-[10px] uppercase">
                        Pending Interest Request Received
                    </span>
                    <h2 class="text-base font-bold text-[var(--text-primary)]">
                        {{ $targetUser->name }} has expressed Halal Interest in you!
                    </h2>
                    <p class="text-xs text-[var(--text-secondary)] max-w-xl">
                        Carefully review their biodata, religious practice, and expectations below. You can accept or decline this matrimonial request.
                    </p>
                    @if($existingInterest->message_note)
                        <p class="text-xs text-[var(--text-primary)] italic mt-2 bg-[var(--bg-surface)] p-2.5 rounded-[var(--radius-sm)] border border-[var(--border-subtle)]">
                            &ldquo;{{ $existingInterest->message_note }}&rdquo;
                        </p>
                    @endif
                </div>

                <div class="flex items-center gap-2 shrink-0 w-full sm:w-auto justify-end">
                    <form action="{{ route('interests.respond', $existingInterest->id) }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="action" value="accept">
                        <button type="submit" class="btn-primary text-xs font-bold">
                            <span>Accept Interest</span>
                        </button>
                    </form>
                    <form action="{{ route('interests.respond', $existingInterest->id) }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="action" value="decline">
                        <button type="submit" class="btn-secondary text-xs font-semibold">
                            <span>Decline</span>
                        </button>
                    </form>
                </div>
            </div>
        @endif

        @if($conversation || ($existingInterest && $existingInterest->status === 'accepted'))
            <div class="card-ledger p-4 bg-[var(--accent-subtle)] border-[var(--accent-border)] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-sm font-bold text-[var(--text-primary)]">Mutual Halal Interest Confirmed!</h2>
                    <p class="text-xs text-[var(--text-secondary)] mt-0.5">You and {{ $targetUser->name }} have mutually accepted interest. You can converse directly in protected chat.</p>
                </div>
                <a href="{{ $conversation ? route('chat.show', $conversation->id) : route('chat.user', $targetUser->id) }}" class="btn-primary text-xs font-bold shrink-0">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    <span>Send Message</span> &rarr;
                </a>
            </div>
        @endif

        <!-- ========================================================= -->
        <!-- STRUCTURED DEFINITION-LIST ROWS (NOT NESTED CARDS)        -->
        <!-- ========================================================= -->
        <div class="card-ledger p-6 sm:p-8 bg-[var(--bg-surface)] space-y-6">

            <!-- Section 1: Basic Information -->
            <section class="def-section">
                <h2 class="text-xs font-bold text-[var(--text-primary)] uppercase tracking-wider mb-3">
                    1. Basic Candidate Information
                </h2>
                <dl class="divide-y divide-[var(--border-subtle)]">
                    <div class="def-row">
                        <dt class="def-term">Legal Name</dt>
                        <dd class="def-desc">{{ $targetUser->name }}</dd>
                    </div>
                    <div class="def-row">
                        <dt class="def-term">Age</dt>
                        <dd class="def-desc">{{ $targetUser->age }} years</dd>
                    </div>
                    <div class="def-row">
                        <dt class="def-term">Marital Status</dt>
                        <dd class="def-desc capitalize">{{ str_replace('_', ' ', $targetUser->marital_status ?? 'Single') }}</dd>
                    </div>
                    <div class="def-row">
                        <dt class="def-term">Height</dt>
                        <dd class="def-desc">{{ $profile->height ? round($profile->height / 30.48, 1) . ' ft (' . $profile->height . ' cm)' : 'Not specified' }}</dd>
                    </div>
                    <div class="def-row">
                        <dt class="def-term">Current Location</dt>
                        <dd class="def-desc">{{ $profile->city ?: 'District flexible' }}, {{ $profile->country ?: '' }}</dd>
                    </div>
                    @if($profile->bio)
                        <div class="def-row">
                            <dt class="def-term">Personal Bio</dt>
                            <dd class="def-desc text-xs leading-relaxed text-[var(--text-secondary)] whitespace-pre-line">{{ $profile->bio }}</dd>
                        </div>
                    @endif
                </dl>
            </section>

            <!-- Section 2: Family Background -->
            <section class="def-section">
                <h2 class="text-xs font-bold text-[var(--text-primary)] uppercase tracking-wider mb-3">
                    2. Family &amp; Guardian Oversight
                </h2>
                <dl class="divide-y divide-[var(--border-subtle)]">
                    <div class="def-row">
                        <dt class="def-term">Guardian Requirement</dt>
                        <dd class="def-desc">{{ $profile->wali_required ? 'Required (Courtship chaperoned by Wali)' : 'Independent / Self-managed' }}</dd>
                    </div>
                    @if($activeWaliLink ?? null)
                        <div class="def-row">
                            <dt class="def-term">Linked Guardian</dt>
                            <dd class="def-desc">{{ $activeWaliLink->wali_name }} ({{ ucfirst(str_replace('_', ' ', $activeWaliLink->relationship_type)) }})</dd>
                        </div>
                    @endif
                    <div class="def-row">
                        <dt class="def-term">Family Values</dt>
                        <dd class="def-desc">{{ $profile->family_values ?? 'Traditional practicing family with deep respect for Islamic values.' }}</dd>
                    </div>
                </dl>
            </section>

            <!-- Section 3: Education & Career -->
            <section class="def-section">
                <h2 class="text-xs font-bold text-[var(--text-primary)] uppercase tracking-wider mb-3">
                    3. Education &amp; Professional Career
                </h2>
                <dl class="divide-y divide-[var(--border-subtle)]">
                    <div class="def-row">
                        <dt class="def-term">Education Level</dt>
                        <dd class="def-desc">{{ $profile->education_level ?: 'University Graduate' }}</dd>
                    </div>
                    <div class="def-row">
                        <dt class="def-term">Occupation / Profession</dt>
                        <dd class="def-desc">{{ $profile->occupation ?: 'Professional' }}</dd>
                    </div>
                    <div class="def-row">
                        <dt class="def-term">Employment Status</dt>
                        <dd class="def-desc">{{ $profile->employment_status ?: 'Employed' }}</dd>
                    </div>
                </dl>
            </section>

            <!-- Section 4: Religious Practice & Ethics -->
            <section class="def-section">
                <h2 class="text-xs font-bold text-[var(--text-primary)] uppercase tracking-wider mb-3">
                    4. Religious Practice &amp; Islamic Alignment
                </h2>
                <dl class="divide-y divide-[var(--border-subtle)]">
                    <div class="def-row">
                        <dt class="def-term">Sect / Madhhab</dt>
                        <dd class="def-desc">{{ $profile->sect_madhhab ?: 'Sunni' }}</dd>
                    </div>
                    <div class="def-row">
                        <dt class="def-term">Prayer Frequency</dt>
                        <dd class="def-desc">{{ str_replace('_', ' ', $profile->prayer_frequency ?: '5x Daily Prayer') }}</dd>
                    </div>
                    @if($targetUser->gender === 'female')
                        <div class="def-row">
                            <dt class="def-term">Hijab / Modesty Practice</dt>
                            <dd class="def-desc">{{ ucfirst(str_replace('_', ' ', $profile->hijab_niqab_practice ?: 'Observes Modesty')) }}</dd>
                        </div>
                    @else
                        <div class="def-row">
                            <dt class="def-term">Sunnah Beard Practice</dt>
                            <dd class="def-desc">{{ ucfirst(str_replace('_', ' ', $profile->beard_practice ?: 'Maintained')) }}</dd>
                        </div>
                    @endif
                    <div class="def-row">
                        <dt class="def-term">Quran Knowledge</dt>
                        <dd class="def-desc">{{ ucfirst(str_replace('_', ' ', $profile->quran_knowledge ?: 'Fluent Reciter')) }}</dd>
                    </div>
                    <div class="def-row">
                        <dt class="def-term">Halal Dietary Adherence</dt>
                        <dd class="def-desc">{{ ucfirst(str_replace('_', ' ', $profile->halal_dietary_adherence ?: 'Strictly Halal')) }}</dd>
                    </div>
                    <div class="def-row">
                        <dt class="def-term">Mosque Attendance</dt>
                        <dd class="def-desc">{{ ucfirst(str_replace('_', ' ', $profile->mosque_attendance ?: 'Weekly Jummah')) }}</dd>
                    </div>
                </dl>
            </section>

            <!-- Section 5: Partner Expectations & Preferences -->
            <section class="def-section">
                <h2 class="text-xs font-bold text-[var(--text-primary)] uppercase tracking-wider mb-3">
                    5. Partner Preferences &amp; Marital Expectations
                </h2>
                <dl class="divide-y divide-[var(--border-subtle)]">
                    <div class="def-row">
                        <dt class="def-term">Preferred Age Range</dt>
                        <dd class="def-desc">{{ $profile->partner_age_min ?? '18' }} to {{ $profile->partner_age_max ?? '40' }} years</dd>
                    </div>
                    <div class="def-row">
                        <dt class="def-term">Preferred Marital Status</dt>
                        <dd class="def-desc capitalize">{{ str_replace('_', ' ', $profile->partner_marital_status ?? 'Any status open') }}</dd>
                    </div>
                    <div class="def-row">
                        <dt class="def-term">Preferred Location</dt>
                        <dd class="def-desc">{{ $profile->partner_location ?? 'Open to relocation with family consent' }}</dd>
                    </div>
                </dl>
            </section>

            <!-- Report Inappropriate Profile Trigger -->
            <div class="pt-4 text-center">
                <button type="button" onclick="document.getElementById('reportModal').classList.remove('hidden')"
                        class="text-xs text-[var(--color-danger)] hover:underline font-medium inline-flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" x2="4" y1="22" y2="15"/></svg>
                    <span>Report inappropriate profile or conduct</span>
                </button>
            </div>
        </div>

    </div>
</div>

<!-- ========================================================= -->
<!-- SEND INTEREST MODAL                                       -->
<!-- ========================================================= -->
<div id="sendInterestModal" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4" role="dialog" aria-modal="true">
    <div class="card-ledger max-w-md w-full p-6 bg-[var(--bg-surface)] shadow-2xl space-y-4">
        <div>
            <h3 class="text-base font-bold text-[var(--text-primary)]">Express Halal Interest</h3>
            <p class="text-xs text-[var(--text-secondary)] mt-1">
                @if($profile->wali_required)
                    Notice: This candidate has a linked guardian (Wali). Communications will be chaperoned according to Islamic protocol.
                @else
                    Express your interest respectfully. If accepted, private messaging will unlock.
                @endif
            </p>
        </div>

        <form action="{{ route('interests.send', $targetUser->id) }}" method="POST" class="space-y-3">
            @csrf
            <div>
                <label for="interest_note" class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">Optional Introductory Note</label>
                <textarea id="interest_note" name="note" rows="3"
                          class="w-full p-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs focus:ring-2 focus:ring-[var(--accent-primary)] outline-none"
                          placeholder="As-salamu alaykum, I have reviewed your biodata and share similar values..."></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('sendInterestModal').classList.add('hidden')" class="btn-secondary text-xs">Cancel</button>
                <button type="submit" class="btn-primary text-xs font-bold">Send Interest</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================= -->
<!-- REPORT CANDIDATE MODAL                                    -->
<!-- ========================================================= -->
<div id="reportModal" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4" role="dialog" aria-modal="true">
    <div class="card-ledger max-w-md w-full p-6 bg-[var(--bg-surface)] shadow-2xl space-y-4">
        <div>
            <h3 class="text-base font-bold text-[var(--text-primary)]">Report Candidate</h3>
            <p class="text-xs text-[var(--text-secondary)] mt-1">Reports are investigated promptly by female and male moderation staff.</p>
        </div>

        <form action="{{ route('reports.store') }}" method="POST" class="space-y-3">
            @csrf
            <input type="hidden" name="reported_id" value="{{ $targetUser->id }}">

            <div>
                <label for="report_reason" class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">Reason for Report</label>
                <select id="report_reason" name="reason" required class="w-full h-10 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs outline-none">
                    <option value="inappropriate_language">Inappropriate or vulgar language</option>
                    <option value="harassment">Harassment or undue pressure</option>
                    <option value="fake_profile">Suspicious or fake profile details</option>
                    <option value="external_solicitation">Commercial solicitation or fraud</option>
                    <option value="contact_sharing_violation">Unauthorized phone / contact sharing</option>
                </select>
            </div>

            <div>
                <label for="report_details" class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">Details for Staff Review</label>
                <textarea id="report_details" name="details" rows="3" class="w-full p-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs outline-none" placeholder="Please describe what occurred..."></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('reportModal').classList.add('hidden')" class="btn-secondary text-xs">Cancel</button>
                <button type="submit" class="btn-primary text-xs font-bold bg-[var(--color-danger)] text-white hover:bg-[var(--color-danger)]">Submit Report</button>
            </div>
        </form>
    </div>
</div>
@endsection

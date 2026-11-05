@extends('layouts.app')

@section('title', 'My Profile & Biodata - Global Nikkah')

@section('content')
<div class="py-8 bg-[var(--bg-canvas)] min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        @if($user->isMarried() || $user->activeMarriage())
            @php $marriage = $user->activeMarriage(); @endphp
            <div class="card-ledger p-5 bg-[var(--accent-subtle)] border-[var(--accent-border)] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <span class="badge-status text-[var(--accent-text)] bg-[var(--bg-surface)] border border-[var(--accent-border)] font-bold text-[10px] uppercase">
                        Nikah Milestone Completed
                    </span>
                    <h2 class="text-base font-bold text-[var(--text-primary)]">
                        @if($marriage && $marriage->spouseOf($user))
                            United in Nikah with {{ $marriage->spouseOf($user)->name }}
                        @else
                            Alhamdulillah, Marital Status: Married
                        @endif
                    </h2>
                    <p class="text-xs text-[var(--text-secondary)]">Your profile is safely shielded from public discovery.</p>
                </div>
                <a href="{{ route('marriages.celebration') }}" class="btn-primary text-xs font-bold shrink-0">
                    <span>View Story</span>
                    <span>&rarr;</span>
                </a>
            </div>
        @endif

        <!-- Profile Overview & Completeness Card -->
        <div class="card-ledger overflow-hidden bg-[var(--bg-surface)]">
            <div class="h-24 sm:h-28 pattern-header border-b border-[var(--border-subtle)] relative flex items-center justify-end px-6">
                <a href="{{ route('profile.edit') }}" class="btn-secondary text-xs h-8 py-0 font-semibold shadow-xs">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
                    <span>Edit Profile</span>
                </a>
            </div>

            <div class="px-6 pb-6 pt-0 relative">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between -mt-12 sm:-mt-14 gap-4 mb-6">
                    <div class="flex items-end gap-4">
                        <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-[var(--radius-md)] border-2 border-[var(--bg-surface)] shadow-md bg-[var(--neutral-900)] overflow-hidden relative shrink-0">
                            @if($profile->primaryPhoto)
                                <img src="{{ $profile->primaryPhoto->displayUrl() }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-[var(--neutral-800)] text-[var(--neutral-300)] font-bold text-3xl">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            @endif
                        </div>

                        <div class="pb-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h1 class="text-xl sm:text-2xl font-bold text-[var(--text-primary)]">{{ $user->name }}</h1>
                                @if($user->isPro())
                                    <span class="px-2 py-0.5 rounded-[var(--radius-sm)] text-[10px] font-bold bg-[var(--secondary-subtle)] text-[var(--secondary-text)] border border-[var(--secondary-border)]">
                                        Pro Member
                                    </span>
                                @endif
                                @if($user->is_verified)
                                    <span class="badge-verified">
                                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                                        <span>Verified Seeker</span>
                                    </span>
                                @else
                                    <span class="badge-status text-[var(--secondary-text)] bg-[var(--secondary-subtle)] border border-[var(--secondary-border)]">
                                        Unverified
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-[var(--text-secondary)] mt-0.5">
                                {{ $user->age }} Years &bull; {{ $profile->city ?? 'Location not set' }}, {{ $profile->country ?? '' }} &bull; {{ $user->email }}
                            </p>
                        </div>
                    </div>

                    <!-- Quick Navigation Actions -->
                    <div class="flex flex-wrap items-center gap-2 pb-1">
                        <a href="{{ route('profile.biodata') }}" target="_blank" class="btn-secondary text-xs h-9 py-0">
                            <svg class="w-3.5 h-3.5 text-[var(--text-muted)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                            <span>Print Biodata</span>
                        </a>
                        <a href="{{ route('profile.visitors') }}" class="btn-secondary text-xs h-9 py-0">
                            <svg class="w-3.5 h-3.5 text-[var(--text-muted)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            <span>Visitors</span>
                        </a>
                        <a href="{{ route('bookmarks.index') }}" class="btn-secondary text-xs h-9 py-0">
                            <svg class="w-3.5 h-3.5 text-[var(--text-muted)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
                            <span>Shortlist</span>
                        </a>
                    </div>
                </div>

                <!-- Completeness Bar Card -->
                <div class="card-ledger p-3.5 bg-[var(--bg-subtle)] flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-[var(--text-primary)]">Profile Readiness:</span>
                        <span class="font-bold text-[var(--accent-text)]">{{ $profile->completeness_percentage }}%</span>
                    </div>
                    <div class="w-full sm:w-64 bg-[var(--neutral-200)] dark:bg-[var(--neutral-800)] rounded-full h-2 overflow-hidden">
                        <div class="bg-[var(--accent-primary)] h-full rounded-full transition-all duration-300" style="width: {{ $profile->completeness_percentage }}%"></div>
                    </div>
                    <div class="text-[11px] text-[var(--text-muted)]">
                        Complete profiles receive significantly higher discovery.
                    </div>
                </div>
            </div>
        </div>

        <!-- Photos Section with Blurred Privacy -->
        <div class="card-ledger p-6 bg-[var(--bg-surface)] space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-[var(--border-subtle)]">
                <div>
                    <h2 class="text-sm font-bold text-[var(--text-primary)] uppercase tracking-wider">Profile Photos ({{ $profile->photos->count() }}/6)</h2>
                    <p class="text-xs text-[var(--text-muted)] mt-0.5">Photos remain shielded to other seekers by default until mutual approval.</p>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-3">
                @foreach($profile->photos as $photo)
                    <div class="relative group rounded-[var(--radius-sm)] overflow-hidden border border-[var(--border-default)] aspect-square bg-[var(--neutral-900)]">
                        <img src="{{ $photo->displayUrl() }}" alt="Profile photo" class="w-full h-full object-cover">
                        @if($photo->is_primary)
                            <span class="absolute top-1.5 left-1.5 bg-[var(--accent-primary)] text-white text-[9px] font-bold px-1.5 py-0.5 rounded-[var(--radius-xs)]">Primary</span>
                        @endif
                        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition flex flex-col items-center justify-center gap-1.5 p-2">
                            @if(!$photo->is_primary)
                                <form action="{{ route('profile.photos.primary', $photo->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-[10px] bg-white text-slate-900 font-bold px-2 py-1 rounded-[var(--radius-xs)]">Set Primary</button>
                                </form>
                            @endif
                            <form action="{{ route('profile.photos.delete', $photo->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-[10px] bg-[var(--color-danger)] text-white font-bold px-2 py-1 rounded-[var(--radius-xs)]">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach

                @if($profile->photos->count() < 6)
                    <form action="{{ route('profile.photos.upload') }}" method="POST" enctype="multipart/form-data" class="border border-dashed border-[var(--border-strong)] rounded-[var(--radius-sm)] aspect-square flex flex-col items-center justify-center p-3 text-center hover:bg-[var(--bg-subtle)] transition cursor-pointer relative">
                        @csrf
                        <input type="file" name="photo" required accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer" onchange="this.form.submit()">
                        <svg class="w-5 h-5 text-[var(--text-muted)] mb-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                        <span class="text-[11px] font-semibold text-[var(--text-primary)]">Upload Photo</span>
                        <span class="text-[9px] text-[var(--text-muted)]">Max 5MB</span>
                    </form>
                @endif
            </div>

            @if($profile->photoAccessGrants->where('status', 'active')->isNotEmpty())
                <div class="border-t border-[var(--border-subtle)] pt-3">
                    <h3 class="text-xs font-bold text-[var(--text-secondary)] mb-2">Members with Photo Permission</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($profile->photoAccessGrants->where('status', 'active') as $grant)
                            <form action="{{ route('profile.photos.revoke', $grant->granted_to_user_id) }}" method="POST" class="inline-flex items-center gap-2 rounded-[var(--radius-sm)] bg-[var(--bg-subtle)] px-2.5 py-1 text-xs">
                                @csrf
                                @method('DELETE')
                                <span class="font-medium text-[var(--text-primary)]">{{ $grant->grantedToUser?->name ?? 'Candidate' }}</span>
                                <button type="submit" class="text-[10px] font-bold text-[var(--color-danger)] hover:underline">Revoke</button>
                            </form>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- KYC Verification Section -->
        <div class="card-ledger p-6 bg-[var(--bg-surface)] space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-[var(--border-subtle)]">
                <div>
                    <h2 class="text-sm font-bold text-[var(--text-primary)] uppercase tracking-wider">Government ID & KYC Verification</h2>
                    <p class="text-xs text-[var(--text-muted)] mt-0.5">Verification builds credibility and reassures prospective families.</p>
                </div>
                @if($user->is_verified)
                    <span class="badge-verified">Verified Account</span>
                @else
                    <span class="badge-status text-[var(--secondary-text)] bg-[var(--secondary-subtle)] border border-[var(--secondary-border)]">
                        Pending Verification
                    </span>
                @endif
            </div>

            @if(!$user->is_verified)
                <form action="{{ route('profile.verification.submit') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                    @csrf
                    <div>
                        <label class="block font-semibold text-[var(--text-secondary)] mb-1">Document Type</label>
                        <select name="document_type" required class="w-full h-10 px-2.5 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs outline-none">
                            <option value="passport">Passport</option>
                            <option value="national_id">National ID Card</option>
                            <option value="driving_license">Driver's License</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold text-[var(--text-secondary)] mb-1">Upload Document (Front)</label>
                        <input type="file" name="document" required accept=".jpg,.jpeg,.png,.pdf"
                               class="w-full h-10 p-2 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-xs text-[var(--text-secondary)]">
                    </div>
                    <div>
                        <label class="block font-semibold text-[var(--text-secondary)] mb-1">Selfie Verification</label>
                        <input type="file" name="selfie" accept="image/*"
                               class="w-full h-10 p-2 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-xs text-[var(--text-secondary)]">
                    </div>
                    <div class="sm:col-span-3 flex justify-end pt-1">
                        <button type="submit" class="btn-primary text-xs font-bold">
                            <span>Submit for Verification</span>
                        </button>
                    </div>
                </form>
            @else
                <p class="text-xs text-[var(--text-secondary)]">Your identity documents have been verified by moderation staff.</p>
            @endif
        </div>

        <!-- Account Deactivation & GDPR Rights -->
        <div class="card-ledger p-5 bg-[var(--bg-subtle)] border-[var(--border-default)] space-y-2">
            <h3 class="text-xs font-bold text-[var(--text-primary)] uppercase tracking-wider">Account Deactivation & Privacy Rights</h3>
            <p class="text-xs text-[var(--text-muted)]">You can pause or deactivate your profile at any time. When married, you can complete the sacred union milestone.</p>
            <form action="{{ route('account.deactivate') }}" method="POST" onsubmit="return confirm('Are you sure you want to deactivate your profile?');" class="flex flex-col sm:flex-row gap-2 pt-1">
                @csrf
                <input type="text" name="reason" required placeholder="Reason for deactivation (e.g. Completed Nikah alhamdulillah, personal break)"
                       class="flex-1 h-10 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] text-xs bg-[var(--bg-surface)] text-[var(--text-primary)] outline-none">
                <button type="submit" class="btn-secondary text-xs font-semibold text-[var(--color-danger)] hover:bg-[var(--color-danger-bg)]">
                    Deactivate Account
                </button>
            </form>
        </div>

    </div>
</div>
@endsection

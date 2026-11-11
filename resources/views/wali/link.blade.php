@extends('layouts.app')

@section('title', 'Guardian (Wali) Involvement — Global Nikkah')

@section('content')
<div class="py-10 bg-[var(--bg-canvas)] min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Breadcrumb & Header -->
        <div class="space-y-3">
            <nav class="flex items-center gap-2 text-xs text-[var(--text-muted)]" aria-label="Breadcrumb">
                <a href="{{ route('profile.show') }}" class="hover:text-[var(--text-primary)] transition">Profile</a>
                <span>/</span>
                <span class="text-[var(--text-primary)] font-medium">Guardian Involvement</span>
            </nav>

            <div class="card-ledger pattern-header p-6 sm:p-8 bg-[var(--bg-surface)]">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="space-y-2">
                        <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-[var(--radius-sm)] bg-[var(--accent-subtle)] text-[var(--accent-text)] text-xs font-semibold">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            <span>Family-First Nikah</span>
                        </div>
                        <h1 class="font-serif text-2xl sm:text-3xl font-semibold text-[var(--text-primary)] tracking-tight">
                            Link Your Guardian (Wali)
                        </h1>
                        <p class="text-sm text-[var(--text-secondary)] max-w-xl leading-relaxed">
                            A trusted guardian provides wisdom, vets prospective suitors, and ensures every conversation remains respectful. Guardian accounts are free forever.
                        </p>
                    </div>
                    <div class="shrink-0 self-start sm:self-center">
                        <span class="badge-status text-[var(--accent-text)] bg-[var(--accent-subtle)] border border-[var(--accent-border)]">
                            Halal Matrimony Standard
                        </span>
                    </div>
                </div>
            </div>
        </div>

        @if(session('error'))
            <div class="p-4 rounded-[var(--radius-md)] bg-[var(--color-danger-bg)] border border-[var(--color-danger-border)] text-xs text-[var(--color-danger)] flex items-start gap-3">
                <svg class="w-4 h-4 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(session('status'))
            <div class="p-4 rounded-[var(--radius-md)] bg-[var(--color-success-bg)] border border-[var(--color-success-border)] text-xs text-[var(--color-success)] flex items-start gap-3">
                <svg class="w-4 h-4 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: Guardian Link Form -->
            <div class="lg:col-span-2 card-ledger p-6 sm:p-8 bg-[var(--bg-surface)]">
                <div class="border-b border-[var(--border-subtle)] pb-4 mb-6">
                    <h2 class="font-serif text-lg font-semibold text-[var(--text-primary)]">Guardian Details</h2>
                    <p class="text-xs text-[var(--text-muted)] mt-1">
                        Your guardian must already have a registered account on Global Nikkah using the email provided below.
                    </p>
                </div>

                <form action="{{ route('wali.link.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label for="wali_name" class="block text-xs font-semibold text-[var(--text-primary)] mb-1.5">
                            Guardian's Full Name <span class="text-[var(--color-danger)]">*</span>
                        </label>
                        <input type="text" id="wali_name" name="wali_name" required value="{{ old('wali_name') }}" placeholder="e.g. Dr. Tariq Al-Mansoor"
                               class="w-full h-11 px-3.5 rounded-[var(--radius-md)] border border-[var(--border-default)] bg-[var(--bg-canvas)] text-sm text-[var(--text-primary)] placeholder:text-[var(--text-muted)] focus:outline-none focus:border-[var(--brand-primary)] focus:ring-1 focus:ring-[var(--brand-primary)] transition">
                        @error('wali_name')
                            <p class="text-[var(--color-danger)] text-xs mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="relationship_type" class="block text-xs font-semibold text-[var(--text-primary)] mb-1.5">
                                Relationship to You <span class="text-[var(--color-danger)]">*</span>
                            </label>
                            <select id="relationship_type" name="relationship_type" required class="w-full h-11 px-3.5 rounded-[var(--radius-md)] border border-[var(--border-default)] bg-[var(--bg-canvas)] text-sm text-[var(--text-primary)] focus:outline-none focus:border-[var(--brand-primary)] focus:ring-1 focus:ring-[var(--brand-primary)] transition">
                                <option value="father" {{ old('relationship_type') === 'father' ? 'selected' : '' }}>Father</option>
                                <option value="brother" {{ old('relationship_type') === 'brother' ? 'selected' : '' }}>Brother</option>
                                <option value="uncle" {{ old('relationship_type') === 'uncle' ? 'selected' : '' }}>Uncle (Paternal / Maternal)</option>
                                <option value="grandfather" {{ old('relationship_type') === 'grandfather' ? 'selected' : '' }}>Grandfather</option>
                                <option value="other_mahram" {{ old('relationship_type') === 'other_mahram' ? 'selected' : '' }}>Other Mahram Guardian</option>
                            </select>
                            @error('relationship_type')
                                <p class="text-[var(--color-danger)] text-xs mt-1.5 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="permission_level" class="block text-xs font-semibold text-[var(--text-primary)] mb-1.5">
                                Oversight Level <span class="text-[var(--color-danger)]">*</span>
                            </label>
                            <select id="permission_level" name="permission_level" required class="w-full h-11 px-3.5 rounded-[var(--radius-md)] border border-[var(--border-default)] bg-[var(--bg-canvas)] text-sm text-[var(--text-primary)] focus:outline-none focus:border-[var(--brand-primary)] focus:ring-1 focus:ring-[var(--brand-primary)] transition">
                                <option value="approve_required" {{ old('permission_level') === 'approve_required' ? 'selected' : '' }}>Approval Required (Chat locked until Wali confirms)</option>
                                <option value="view_only" {{ old('permission_level') === 'view_only' ? 'selected' : '' }}>Observer Only (Silent chaperone in chats)</option>
                                <option value="full_proxy" {{ old('permission_level') === 'full_proxy' ? 'selected' : '' }}>Full Proxy (Guardian conducts communications)</option>
                            </select>
                            @error('permission_level')
                                <p class="text-[var(--color-danger)] text-xs mt-1.5 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="wali_email" class="block text-xs font-semibold text-[var(--text-primary)] mb-1.5">
                                Guardian's Account Email <span class="text-[var(--color-danger)]">*</span>
                            </label>
                            <input type="email" id="wali_email" name="wali_email" required value="{{ old('wali_email') }}" placeholder="guardian@example.com"
                                   class="w-full h-11 px-3.5 rounded-[var(--radius-md)] border @error('wali_email') border-[var(--color-danger)] @else border-[var(--border-default)] @enderror bg-[var(--bg-canvas)] text-sm text-[var(--text-primary)] placeholder:text-[var(--text-muted)] focus:outline-none focus:border-[var(--brand-primary)] focus:ring-1 focus:ring-[var(--brand-primary)] transition">
                            @error('wali_email')
                                <p class="text-[var(--color-danger)] text-xs mt-1.5 font-medium">{{ $message }}</p>
                            @else
                                <p class="text-[11px] text-[var(--text-muted)] mt-1">Must match their registered Global Nikkah account.</p>
                            @enderror
                        </div>

                        <div>
                            <label for="wali_phone" class="block text-xs font-semibold text-[var(--text-primary)] mb-1.5">
                                Guardian's Phone / WhatsApp <span class="text-[var(--text-muted)] font-normal">(Optional)</span>
                            </label>
                            <input type="tel" id="wali_phone" name="wali_phone" value="{{ old('wali_phone') }}" placeholder="+1 555 019 2834"
                                   class="w-full h-11 px-3.5 rounded-[var(--radius-md)] border border-[var(--border-default)] bg-[var(--bg-canvas)] text-sm text-[var(--text-primary)] placeholder:text-[var(--text-muted)] focus:outline-none focus:border-[var(--brand-primary)] focus:ring-1 focus:ring-[var(--brand-primary)] transition">
                            @error('wali_phone')
                                <p class="text-[var(--color-danger)] text-xs mt-1.5 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="pt-4 border-t border-[var(--border-subtle)] flex items-center justify-between gap-4">
                        <p class="text-xs text-[var(--text-muted)]">
                            We will send a notification asking your guardian to accept this linkage.
                        </p>
                        <button type="submit" class="btn-primary h-11 px-6 shrink-0 text-xs font-semibold tracking-wide">
                            Save Guardian Link
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right: Explanatory & Current Links -->
            <div class="space-y-6">
                <!-- Why Wali Card -->
                <div class="card-ledger p-6 bg-[var(--bg-surface)] border-l-2 border-l-[var(--accent-gold)]">
                    <h3 class="font-serif text-base font-semibold text-[var(--text-primary)] mb-2">
                        How Guardian Oversight Works
                    </h3>
                    <div class="space-y-3 text-xs text-[var(--text-secondary)] leading-relaxed">
                        <p>
                            When <strong>Approval Required</strong> is selected, prospective suitors can view your profile and express interest, but no private messaging begins until your guardian reviews and confirms the match.
                        </p>
                        <p>
                            Your guardian can read ongoing discussions as a silent chaperone, providing advice and peace of mind to both families.
                        </p>
                        <div class="pt-2 text-[11px] text-[var(--accent-text)] font-medium flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                            <span>Phone numbers stay private until families are ready.</span>
                        </div>
                    </div>
                </div>

                <!-- Current Linked Guardians -->
                <div class="card-ledger p-6 bg-[var(--bg-surface)]">
                    <div class="flex items-center justify-between border-b border-[var(--border-subtle)] pb-3 mb-4">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-[var(--text-secondary)]">
                            Current Linked Guardians
                        </h3>
                        <span class="text-xs text-[var(--text-muted)]">{{ count($existingLinks) }} linked</span>
                    </div>

                    <div class="space-y-3">
                        @forelse($existingLinks as $link)
                            <div class="p-3.5 rounded-[var(--radius-md)] bg-[var(--bg-canvas)] border border-[var(--border-default)]">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="font-semibold text-sm text-[var(--text-primary)]">
                                        {{ $link->wali_name ?? ($link->wali?->name ?? 'Guardian') }}
                                    </span>
                                    <span class="badge-status text-[10px] capitalize {{ $link->status === 'active' || $link->status === 'approved' ? 'text-[var(--color-success)] bg-[var(--color-success-bg)] border-[var(--color-success-border)]' : 'text-[var(--accent-text)] bg-[var(--accent-subtle)] border-[var(--accent-border)]' }}">
                                        {{ $link->status }}
                                    </span>
                                </div>
                                <div class="mt-1.5 flex items-center gap-2 text-xs text-[var(--text-muted)] capitalize">
                                    <span>{{ $link->relationship_type }}</span>
                                    <span>&bull;</span>
                                    <span>{{ str_replace('_', ' ', $link->permission_level) }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="py-6 text-center text-xs text-[var(--text-muted)]">
                                <p>No guardian currently linked.</p>
                                <p class="mt-1 text-[11px]">Add your guardian's email to activate family chaperone protection.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

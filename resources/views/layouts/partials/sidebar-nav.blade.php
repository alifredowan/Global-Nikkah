@php
    $user = Auth::user();
    $isSuperAdmin = $user->isSuperAdmin();
    $isAdminOrStaff = $user->isAdmin() || $user->isModerator();
    $pendingInterests = $user->isSeeker() ? $user->receivedInterests()->where('status', 'pending')->count() : 0;
    $pendingVerifications = $isAdminOrStaff ? \App\Models\VerificationRequest::where('status', 'pending')->count() : 0;
    $pendingReports = $isAdminOrStaff ? \App\Models\Report::where('status', 'pending')->count() : 0;
    $unreadMessages = $user->unreadMessagesCount();
    $pendingMarriage = $user->pendingMarriage();
@endphp

<div class="space-y-6">

    {{-- ========================================================= --}}
    {{-- 1. SUPER ADMIN & STAFF CONSOLE                            --}}
    {{-- ========================================================= --}}
    @if($isAdminOrStaff)
        <div>
            <div class="px-3 mb-2 text-[10px] font-extrabold uppercase tracking-wider text-[var(--secondary-text)] flex items-center justify-between">
                <span>Admin Control</span>
                <span class="text-[9px] px-1.5 py-0.2 rounded-[var(--radius-xs)] bg-[var(--secondary-subtle)] text-[var(--secondary-text)] font-bold uppercase">
                    {{ $isSuperAdmin ? 'Super Admin' : 'Staff' }}
                </span>
            </div>
            <div class="space-y-1">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-[var(--radius-sm)] text-xs font-semibold transition {{ request()->routeIs('admin.dashboard') ? 'bg-[var(--accent-primary)] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                    <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                    <span class="flex-1">Dashboard Overview</span>
                </a>

                @if($isSuperAdmin || $user->hasPermission('manage_verifications'))
                    <a href="{{ route('admin.verifications') }}"
                       class="flex items-center justify-between px-3 py-2 rounded-[var(--radius-sm)] text-xs font-semibold transition {{ request()->routeIs('admin.verifications') ? 'bg-[var(--accent-primary)] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                            <span>KYC Queue</span>
                        </div>
                        @if($pendingVerifications > 0)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-500 text-white">
                                {{ $pendingVerifications }}
                            </span>
                        @endif
                    </a>
                @endif

                @if($isSuperAdmin || $user->hasPermission('manage_reports'))
                    <a href="{{ route('admin.reports') }}"
                       class="flex items-center justify-between px-3 py-2 rounded-[var(--radius-sm)] text-xs font-semibold transition {{ request()->routeIs('admin.reports') ? 'bg-[var(--accent-primary)] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" x2="4" y1="22" y2="15"/></svg>
                            <span>User Reports</span>
                        </div>
                        @if($pendingReports > 0)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500 text-white">
                                {{ $pendingReports }}
                            </span>
                        @endif
                    </a>
                @endif
            </div>
        </div>

        <!-- PLANS & PROMO ENGINE -->
        @if($isSuperAdmin || $user->hasPermission('manage_settings'))
            <div>
                <div class="px-3 mb-2 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                    Plans & Discounts
                </div>
                <div class="space-y-1">
                    <a href="{{ route('admin.packages.index') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-[var(--radius-sm)] text-xs font-semibold transition {{ request()->routeIs('admin.packages.*') ? 'bg-[var(--accent-primary)] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                        <span class="flex-1">Dynamic Packages</span>
                    </a>

                    <a href="{{ route('admin.promo-codes.index') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-[var(--radius-sm)] text-xs font-semibold transition {{ request()->routeIs('admin.promo-codes.*') ? 'bg-[var(--accent-primary)] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="m20.59 13.41-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" x2="7.01" y1="7" y2="7"/></svg>
                        <span class="flex-1">Promo Codes</span>
                    </a>

                    <a href="{{ route('admin.payment-settings') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-[var(--radius-sm)] text-xs font-semibold transition {{ request()->routeIs('admin.payment-settings*') ? 'bg-[var(--accent-primary)] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        <span class="flex-1">Payment Gateways</span>
                    </a>
                </div>
            </div>
        @endif

        <!-- GOVERNANCE & AUDIT -->
        <div>
            <div class="px-3 mb-2 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                Governance
            </div>
            <div class="space-y-1">
                @if($isSuperAdmin)
                    <a href="{{ route('admin.staff') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-[var(--radius-sm)] text-xs font-semibold transition {{ request()->routeIs('admin.staff') ? 'bg-[var(--accent-primary)] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        <span class="flex-1">Staff & Permissions</span>
                    </a>
                @endif

                @if($isSuperAdmin || $user->hasPermission('view_audit_logs'))
                    <a href="{{ route('admin.audit-logs') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-[var(--radius-sm)] text-xs font-semibold transition {{ request()->routeIs('admin.audit-logs') ? 'bg-[var(--accent-primary)] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M12 11h4"/><path d="M12 16h4"/><path d="M8 11h.01"/><path d="M8 16h.01"/></svg>
                        <span class="flex-1">Audit Logs</span>
                    </a>
                @endif

                @if($isSuperAdmin || $user->hasPermission('manage_settings'))
                    <a href="{{ route('admin.settings') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-[var(--radius-sm)] text-xs font-semibold transition {{ request()->routeIs('admin.settings') ? 'bg-[var(--accent-primary)] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                        <span class="flex-1">Platform Policies</span>
                    </a>
                @endif
            </div>
        </div>
    @endif

    {{-- ========================================================= --}}
    {{-- 2. MATCHMAKING & DISCOVERY                                --}}
    {{-- ========================================================= --}}
    @if($isSuperAdmin || (!$isAdminOrStaff && $user->isSeeker()))
        <div>
            <div class="px-3 mb-2 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                Matchmaking
            </div>
            <div class="space-y-1">
                @if(!$isAdminOrStaff)
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-[var(--radius-sm)] text-xs font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-[var(--accent-primary)] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        <span class="flex-1">My Dashboard</span>
                    </a>
                @endif

                <a href="{{ route('discovery.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-[var(--radius-sm)] text-xs font-semibold transition {{ request()->routeIs('discovery.*') ? 'bg-[var(--accent-primary)] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                    <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <span class="flex-1">Find Matches</span>
                </a>

                <a href="{{ route('interests.index') }}"
                   class="flex items-center justify-between px-3 py-2 rounded-[var(--radius-sm)] text-xs font-semibold transition {{ request()->routeIs('interests.*') ? 'bg-[var(--accent-primary)] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                        <span>Interests & Requests</span>
                    </div>
                    @if($pendingInterests > 0)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[var(--secondary-primary)] text-white">
                            {{ $pendingInterests }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('chat.index') }}"
                   class="flex items-center justify-between gap-3 px-3 py-2 rounded-[var(--radius-sm)] text-xs font-semibold transition {{ request()->routeIs('chat.*') ? 'bg-[var(--accent-primary)] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                    <span class="flex items-center gap-3">
                        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        <span>Messages</span>
                    </span>
                    <span class="sidebar-unread-messages-badge rounded-full bg-[var(--secondary-primary)] px-2 py-0.5 text-[10px] font-bold text-white {{ $unreadMessages > 0 ? '' : 'hidden' }}">
                        {{ $unreadMessages > 99 ? '99+' : $unreadMessages }}
                    </span>
                </a>

                <a href="{{ route('bookmarks.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-[var(--radius-sm)] text-xs font-semibold transition {{ request()->routeIs('bookmarks.*') ? 'bg-[var(--accent-primary)] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                    <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
                    <span class="flex-1">Shortlist</span>
                </a>
            </div>
        </div>
    @endif

    {{-- ========================================================= --}}
    {{-- 3. MY BIODATA & PRO PROFILES                              --}}
    {{-- ========================================================= --}}
    @if(!$isAdminOrStaff && $user->isSeeker())
        <div>
            <div class="px-3 mb-2 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                My Biodata
            </div>
            <div class="space-y-1">
                <a href="{{ route('profile.show') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-[var(--radius-sm)] text-xs font-semibold transition {{ request()->routeIs('profile.show') ? 'bg-[var(--accent-primary)] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                    <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <span class="flex-1">View Profile & Photos</span>
                </a>

                <a href="{{ route('profile.edit') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-[var(--radius-sm)] text-xs font-semibold transition {{ request()->routeIs('profile.edit') ? 'bg-[var(--accent-primary)] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                    <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
                    <span class="flex-1">Edit Deen & Preferences</span>
                </a>

                <a href="{{ route('profile.biodata') }}"
                   target="_blank"
                   class="flex items-center justify-between px-3 py-2 rounded-[var(--radius-sm)] text-xs font-semibold text-slate-300 hover:bg-slate-800/60 hover:text-white transition">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                        <span>Printable Biodata</span>
                    </div>
                    <span class="text-[9px] font-bold text-slate-400 uppercase">PDF</span>
                </a>

                <a href="{{ route('wali.link') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-[var(--radius-sm)] text-xs font-semibold transition {{ request()->routeIs('wali.link') ? 'bg-[var(--accent-primary)] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                    <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <span class="flex-1">Link Guardian (Wali)</span>
                </a>
            </div>
        </div>
    @endif

    {{-- ========================================================= --}}
    {{-- 4. GUARDIAN (WALI) WORKFLOW                               --}}
    {{-- ========================================================= --}}
    @if($isSuperAdmin || (!$isAdminOrStaff && ($user->isWali() || $user->waliLinksAsWali()->exists())))
        <div>
            <div class="px-3 mb-2 text-[10px] font-extrabold uppercase tracking-wider text-[var(--accent-text)] flex items-center justify-between">
                <span class="flex items-center gap-1.5">Guardian Console</span>
                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-[var(--radius-xs)] bg-[var(--accent-subtle)] text-[var(--accent-text)]">FREE</span>
            </div>
            <div class="space-y-1">
                <a href="{{ route('wali.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-[var(--radius-sm)] text-xs font-semibold transition {{ request()->routeIs('wali.dashboard') ? 'bg-[var(--accent-primary)] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                    <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <span class="flex-1">Wali Dashboard</span>
                </a>

                <a href="{{ route('wali.link') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-[var(--radius-sm)] text-xs font-semibold transition {{ request()->routeIs('wali.link') ? 'bg-[var(--accent-primary)] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                    <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                    <span class="flex-1">Link Ward</span>
                </a>
            </div>
        </div>
    @endif

    {{-- ========================================================= --}}
    {{-- 5. PLANS & PRO MEMBERSHIP                                 --}}
    {{-- ========================================================= --}}
    @if(($isSuperAdmin || (!$isAdminOrStaff && $user->isSeeker())) && ! $user->isWali())
        <div>
            <div class="px-3 mb-2 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                Pro & Plans
            </div>
            <div class="space-y-1">
                @if(!$isAdminOrStaff && $user->isSeeker())
                    <a href="{{ route('profile.visitors') }}"
                       class="flex items-center justify-between px-3 py-2 rounded-[var(--radius-sm)] text-xs font-semibold transition {{ request()->routeIs('profile.visitors') ? 'bg-[var(--accent-primary)] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            <span>Who Viewed You</span>
                        </div>
                        <span class="text-[9px] font-extrabold uppercase px-1.5 py-0.5 rounded-[var(--radius-xs)] bg-[var(--accent-subtle)] text-[var(--accent-text)]">PRO</span>
                    </a>
                @endif

                <a href="{{ route('subscription.pricing') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-[var(--radius-sm)] text-xs font-semibold transition {{ request()->routeIs('subscription.pricing') ? 'bg-[var(--accent-primary)] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                    <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M6 3h12l4 6-10 13L2 9Z"/><path d="M11 3 8 9l4 13 4-13-3-6"/><path d="M2 9h20"/></svg>
                    <span class="flex-1">Plans & Pricing</span>
                </a>

                @if($user->isMarried() || $pendingMarriage)
                    <a href="{{ route('marriages.celebration') }}"
                       class="flex items-center justify-between px-3 py-2 rounded-[var(--radius-sm)] text-xs font-bold transition {{ request()->routeIs('marriages.*') ? 'bg-[var(--accent-primary)] text-white shadow-xs' : 'text-emerald-300 hover:bg-slate-800/60' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                            <span>Nikah Milestone</span>
                        </div>
                        <span class="text-[9px] font-bold uppercase px-1.5 py-0.5 rounded-[var(--radius-xs)] {{ $pendingMarriage ? 'bg-amber-500 text-slate-950' : 'bg-emerald-500 text-white' }}">
                            {{ $pendingMarriage ? 'Pending' : 'Mubarak' }}
                        </span>
                    </a>
                @endif
            </div>
        </div>
    @endif

    {{-- ========================================================= --}}
    {{-- 6. GENERAL ACCOUNT SETTINGS                               --}}
    {{-- ========================================================= --}}
    <div>
        <div class="px-3 mb-2 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500">
            Account & System
        </div>
        <div class="space-y-1">
            <a href="{{ route('settings.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-[var(--radius-sm)] text-xs font-semibold transition {{ request()->routeIs('settings.*') ? 'bg-[var(--accent-primary)] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                <span class="flex-1">Account Settings</span>
            </a>

            @if(!$isAdminOrStaff && $user->isSeeker())
                <a href="{{ route('blocked-users.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-[var(--radius-sm)] text-xs font-semibold transition {{ request()->routeIs('blocked-users.*') ? 'bg-[var(--accent-primary)] text-white shadow-xs' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                    <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><circle cx="12" cy="12" r="10"/><line x1="4.93" x2="19.07" y1="4.93" y2="19.07"/></svg>
                    <span class="flex-1">Blocked Profiles</span>
                </a>
            @endif

            <a href="{{ route('landing') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-[var(--radius-sm)] text-xs font-semibold text-slate-400 hover:bg-slate-800/60 hover:text-white transition">
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
                <span class="flex-1">Public Home</span>
            </a>
        </div>
    </div>

</div>

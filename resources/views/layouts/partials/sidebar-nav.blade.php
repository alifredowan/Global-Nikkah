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
    {{-- (Only visible to Super Admin and Moderator Staff)         --}}
    {{-- ========================================================= --}}
    @if($isAdminOrStaff)
        <div>
            <div class="px-3 mb-2 text-[10px] font-extrabold uppercase tracking-wider text-amber-400/90 flex items-center justify-between">
                <span>⚡ Admin Control</span>
                <span class="text-[9px] px-1.5 py-0.2 rounded-md bg-amber-500/20 text-amber-300 font-bold uppercase">
                    {{ $isSuperAdmin ? 'Super Admin' : 'Staff' }}
                </span>
            </div>
            <div class="space-y-1">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <span class="text-base">📊</span>
                    <span class="flex-1">Dashboard Overview</span>
                </a>

                @if($isSuperAdmin || $user->hasPermission('manage_verifications'))
                    <a href="{{ route('admin.verifications') }}"
                       class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.verifications') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <div class="flex items-center gap-3">
                            <span class="text-base">🪪</span>
                            <span>KYC Queue</span>
                        </div>
                        @if($pendingVerifications > 0)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-500 text-white animate-pulse">
                                {{ $pendingVerifications }}
                            </span>
                        @endif
                    </a>
                @endif

                @if($isSuperAdmin || $user->hasPermission('manage_reports'))
                    <a href="{{ route('admin.reports') }}"
                       class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.reports') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <div class="flex items-center gap-3">
                            <span class="text-base">🚩</span>
                            <span>User Reports</span>
                        </div>
                        @if($pendingReports > 0)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500 text-white animate-pulse">
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
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.packages.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="text-base">💳</span>
                        <span class="flex-1">Dynamic Packages</span>
                    </a>

                    <a href="{{ route('admin.promo-codes.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.promo-codes.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="text-base">🏷️</span>
                        <span class="flex-1">Promo Codes</span>
                    </a>

                    <a href="{{ route('admin.payment-settings') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.payment-settings*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="text-base">💰</span>
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
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.staff') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="text-base">👥</span>
                        <span class="flex-1">Staff & Permissions</span>
                    </a>
                @endif

                @if($isSuperAdmin || $user->hasPermission('view_audit_logs'))
                    <a href="{{ route('admin.audit-logs') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.audit-logs') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="text-base">📋</span>
                        <span class="flex-1">Audit Logs</span>
                    </a>
                @endif

                @if($isSuperAdmin || $user->hasPermission('manage_settings'))
                    <a href="{{ route('admin.settings') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.settings') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="text-base">⚙️</span>
                        <span class="flex-1">Platform Policies</span>
                    </a>
                @endif
            </div>
        </div>
    @endif

    {{-- ========================================================= --}}
    {{-- 2. MATCHMAKING & DISCOVERY                                --}}
    {{-- (Visible to Normal Seekers AND Super Admin who gets all)  --}}
    {{-- ========================================================= --}}
    @if($isSuperAdmin || (!$isAdminOrStaff && $user->isSeeker()))
        <div>
            <div class="px-3 mb-2 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                Matchmaking
            </div>
            <div class="space-y-1">
                @if(!$isAdminOrStaff)
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="text-base">⌂</span>
                        <span class="flex-1">My Dashboard</span>
                    </a>
                @endif

                <a href="{{ route('discovery.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('discovery.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <span class="text-base">🔍</span>
                    <span class="flex-1">Find Matches</span>
                </a>

                <a href="{{ route('interests.index') }}"
                   class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('interests.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <span class="text-base">💌</span>
                        <span>Interests & Requests</span>
                    </div>
                    @if($pendingInterests > 0)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-500 text-white animate-pulse">
                            {{ $pendingInterests }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('chat.index') }}"
                   class="flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('chat.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <span class="flex items-center gap-3"><span class="text-base">💬</span><span>Messages</span></span>
                    @if($unreadMessages > 0)
                        <span class="rounded-full bg-amber-500 px-2 py-0.5 text-[10px] font-extrabold text-slate-950">{{ $unreadMessages > 99 ? '99+' : $unreadMessages }}</span>
                    @endif
                </a>

                <a href="{{ route('bookmarks.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('bookmarks.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <span class="text-base">⭐</span>
                    <span class="flex-1">Shortlist</span>
                </a>
            </div>
        </div>
    @endif

    {{-- ========================================================= --}}
    {{-- 3. MY BIODATA & PRO PROFILES                              --}}
    {{-- (Visible to Normal Seekers)                               --}}
    {{-- ========================================================= --}}
    @if(!$isAdminOrStaff && $user->isSeeker())
        <div>
            <div class="px-3 mb-2 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                My Biodata
            </div>
            <div class="space-y-1">
                <a href="{{ route('profile.show') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('profile.show') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <span class="text-base">👤</span>
                    <span class="flex-1">View Profile & Photos</span>
                </a>

                <a href="{{ route('profile.edit') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('profile.edit') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <span class="text-base">✏️</span>
                    <span class="flex-1">Edit Deen & Preferences</span>
                </a>

                <a href="{{ route('profile.biodata') }}"
                   target="_blank"
                   class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-300 hover:bg-slate-800 hover:text-white transition">
                    <div class="flex items-center gap-3">
                        <span class="text-base">🖨️</span>
                        <span>Printable Biodata</span>
                    </div>
                    <span class="text-[9px] font-bold text-slate-400 uppercase">PDF</span>
                </a>

                <a href="{{ route('wali.link') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('wali.link') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <span class="text-base">🛡️</span>
                    <span class="flex-1">Link Guardian (Wali)</span>
                </a>
            </div>
        </div>
    @endif

    {{-- ========================================================= --}}
    {{-- 4. GUARDIAN (WALI) WORKFLOW                               --}}
    {{-- (Visible to Guardians/Walis AND Super Admin)               --}}
    {{-- ========================================================= --}}
    @if($isSuperAdmin || (!$isAdminOrStaff && ($user->isWali() || $user->waliLinksAsWali()->exists())))
        <div>
            <div class="px-3 mb-2 text-[10px] font-extrabold uppercase tracking-wider text-emerald-400 flex items-center gap-1.5">
                <span>🛡️</span> Guardian Console
            </div>
            <div class="space-y-1">
                <a href="{{ route('wali.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('wali.dashboard') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <span class="text-base">🛡️</span>
                    <span class="flex-1">Wali Dashboard</span>
                </a>

                <a href="{{ route('wali.link') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('wali.link') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <span class="text-base">🔗</span>
                    <span class="flex-1">Link Ward</span>
                </a>
            </div>
        </div>
    @endif

    {{-- ========================================================= --}}
    {{-- 5. PLANS & PRO MEMBERSHIP                                 --}}
    {{-- (Visible to Seekers and Super Admin)                      --}}
    {{-- ========================================================= --}}
    @if($isSuperAdmin || (!$isAdminOrStaff && $user->isSeeker()))
        <div>
            <div class="px-3 mb-2 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                Pro & Plans
            </div>
            <div class="space-y-1">
                @if(!$isAdminOrStaff && $user->isSeeker())
                    <a href="{{ route('profile.visitors') }}"
                       class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('profile.visitors') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <div class="flex items-center gap-3">
                            <span class="text-base">👁️</span>
                            <span>Who Viewed You</span>
                        </div>
                        <span class="text-[9px] font-extrabold uppercase px-1.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300">PRO</span>
                    </a>
                @endif

                <a href="{{ route('subscription.pricing') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('subscription.pricing') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <span class="text-base">💎</span>
                    <span class="flex-1">Plans & Pricing</span>
                </a>

                @if($user->isMarried() || $pendingMarriage)
                    <a href="{{ route('marriages.celebration') }}"
                       class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('marriages.*') ? 'bg-emerald-600 text-white' : 'text-emerald-300 hover:bg-slate-800' }}">
                        <div class="flex items-center gap-3">
                            <span class="text-base">💍</span>
                            <span>Nikah Milestone</span>
                        </div>
                        <span class="text-[9px] font-black uppercase px-1.5 py-0.5 rounded-full {{ $pendingMarriage ? 'bg-amber-500 text-slate-950' : 'bg-emerald-500 text-white' }}">
                            {{ $pendingMarriage ? 'Pending' : 'Mubarak' }}
                        </span>
                    </a>
                @endif
            </div>
        </div>
    @endif

    {{-- ========================================================= --}}
    {{-- 6. GENERAL ACCOUNT SETTINGS (FOR EVERYONE)                --}}
    {{-- ========================================================= --}}
    <div>
        <div class="px-3 mb-2 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500">
            Account & System
        </div>
        <div class="space-y-1">
            <a href="{{ route('settings.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('settings.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <span class="text-base">⚙️</span>
                <span class="flex-1">Account Settings</span>
            </a>

            @if(!$isAdminOrStaff && $user->isSeeker())
                <a href="{{ route('blocked-users.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('blocked-users.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <span class="text-base">⛔</span>
                    <span class="flex-1">Blocked Profiles</span>
                </a>
            @endif

            <a href="{{ route('landing') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-400 hover:bg-slate-800 hover:text-emerald-300 transition">
                <span class="text-base">🌐</span>
                <span class="flex-1">Public Home</span>
            </a>
        </div>
    </div>

</div>

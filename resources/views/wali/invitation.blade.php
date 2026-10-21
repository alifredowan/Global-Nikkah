@extends('layouts.app')

@section('title', 'Guardian (Wali) Invitation - Global Nikkah')

@section('content')
<div class="py-12 bg-slate-50 dark:bg-slate-950 min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full space-y-6">

        <!-- Islamic Header Banner -->
        <div class="text-center space-y-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                <span>🛡️</span> Halal Matrimonial Guardianship
            </span>
            <p class="font-arabic text-xl sm:text-2xl text-amber-600 dark:text-amber-400 font-medium">بِسْمِ ٱللَّهِ ٱلرَّحْمَٰنِ ٱلرَّحِيمِ</p>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white font-heading">
                Guardian (Wali) Connection Request
            </h1>
            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 max-w-lg mx-auto">
                You have been honored with a request to act as a Mahram Guardian (Wali) on <strong class="text-slate-900 dark:text-white">Global Nikkah</strong>.
            </p>
        </div>

        <!-- Main Card -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl overflow-hidden p-6 sm:p-8 space-y-6">

            <!-- Ward Profile Preview -->
            <div class="flex flex-col sm:flex-row items-center gap-5 p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/60">
                <div class="relative w-20 h-20 rounded-2xl overflow-hidden bg-emerald-100 dark:bg-emerald-950 flex items-center justify-center text-3xl font-bold text-emerald-700 dark:text-emerald-400 border-2 border-emerald-500/20 shadow-xs flex-shrink-0">
                    @if($link->seeker->profile?->primaryPhoto)
                        <img src="{{ $link->seeker->profile->primaryPhoto->displayUrl() }}" alt="{{ $link->seeker->name }}" class="w-full h-full object-cover" loading="lazy" decoding="async">
                    @else
                        {{ strtoupper(substr($link->seeker->name, 0, 1)) }}
                    @endif
                </div>

                <div class="text-center sm:text-left flex-1 min-w-0">
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mb-1">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white font-heading truncate">{{ $link->seeker->name }}</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-300">
                            {{ ucfirst($link->seeker->gender) }}
                        </span>
                        @if($link->seeker->profile?->age)
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-medium bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300">
                                {{ $link->seeker->profile->age }} yrs old
                            </span>
                        @endif
                    </div>

                    <p class="text-xs text-slate-600 dark:text-slate-400">
                        Designated Relationship:
                        <strong class="text-amber-600 dark:text-amber-400 uppercase tracking-wide font-bold">
                            {{ ucfirst(str_replace('_', ' ', $link->relationship_type)) }}
                        </strong>
                        ({{ $link->seeker->gender === 'female' ? 'Her Guardian / Mahram' : 'His Guardian / Mahram' }})
                    </p>

                    @if($link->seeker->profile?->city || $link->seeker->profile?->country)
                        <p class="text-[11px] text-slate-500 dark:text-slate-500 mt-0.5 flex items-center justify-center sm:justify-start gap-1">
                            <span>📍</span> {{ $link->seeker->profile->city ? $link->seeker->profile->city . ', ' : '' }}{{ $link->seeker->profile->country ?? '' }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Permission & Role Details -->
            <div class="space-y-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Guardian Privileges & Supervision Level</h3>
                <div class="p-4 rounded-2xl bg-amber-500/5 border border-amber-500/20 text-xs space-y-2">
                    <div class="flex items-start gap-2.5">
                        <span class="text-amber-600 dark:text-amber-400 text-sm mt-0.5">🔒</span>
                        <div>
                            <span class="font-bold text-slate-900 dark:text-white">Permission Level:</span>
                            <span class="text-slate-700 dark:text-slate-300 ml-1">
                                @if($link->permission_level === 'approve_required')
                                    <strong>Approval Required</strong> — Suitors cannot chat with {{ $link->seeker->name }} until you review and grant explicit approval.
                                @elseif($link->permission_level === 'full_proxy')
                                    <strong>Full Proxy</strong> — You manage incoming communications and represent {{ $link->seeker->name }} in discussions.
                                @else
                                    <strong>Observer / View Only</strong> — You are automatically added as a silent chaperone in all conversations.
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <span class="text-amber-600 dark:text-amber-400 text-sm mt-0.5">✉️</span>
                        <div>
                            <span class="font-bold text-slate-900 dark:text-white">Assigned Guardian Email:</span>
                            <span class="text-slate-700 dark:text-slate-300 ml-1">{{ $link->wali_email ?: 'Not specified' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Islamic Sunnah Note -->
            <div class="p-4 rounded-2xl bg-emerald-500/5 border border-emerald-500/20 text-xs text-slate-600 dark:text-slate-400 space-y-1.5">
                <p class="font-semibold text-emerald-800 dark:text-emerald-300 flex items-center gap-1.5">
                    <span>📖</span> Hadith on Matrimonial Guardianship
                </p>
                <p class="italic text-slate-600 dark:text-slate-300">
                    "There is no marriage without a Wali (guardian)." — Sunan Abi Dawud & Jami` at-Tirmidhi
                </p>
                <p class="text-[11px] text-slate-500 dark:text-slate-400">
                    Global Nikkah honors this sacred trust. By accepting, you protect your family member's modesty and assist them in finding a righteous partner.
                </p>
            </div>

            <!-- Action Status / Buttons -->
            @if($link->status === 'active')
                <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-center space-y-2">
                    <span class="text-2xl">✅</span>
                    <h4 class="text-sm font-bold text-emerald-800 dark:text-emerald-200">Guardian Connection Active</h4>
                    <p class="text-xs text-emerald-700 dark:text-emerald-400">
                        You are already verified as the active guardian for {{ $link->seeker->name }}.
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('wali.dashboard') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition-colors shadow-xs">
                            Go to Guardian Dashboard &rarr;
                        </a>
                    </div>
                </div>
            @elseif($link->status === 'declined')
                <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-center space-y-1">
                    <span class="text-2xl">❌</span>
                    <h4 class="text-sm font-bold text-rose-800 dark:text-rose-200">Connection Declined</h4>
                    <p class="text-xs text-rose-600 dark:text-rose-400">
                        This guardian connection request has been declined.
                    </p>
                </div>
            @else
                <div class="space-y-3 pt-2">
                    <form action="{{ route('wali.invite.accept', $link->invite_token) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-700 hover:from-emerald-500 hover:to-teal-600 text-white font-bold text-sm shadow-lg shadow-emerald-900/20 hover:shadow-emerald-900/30 transition-all flex items-center justify-center gap-2 cursor-pointer">
                            <span>🛡️</span> Accept & Confirm Guardian Connection
                        </button>
                    </form>

                    <form action="{{ route('wali.invite.decline', $link->invite_token) }}" method="POST">
                        @csrf
                        <button type="submit" onclick="return confirm('Are you sure you want to decline this guardian request?');" class="w-full py-2.5 px-4 rounded-xl text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/20 transition-colors cursor-pointer text-center">
                            Decline Request
                        </button>
                    </form>
                </div>
            @endif

        </div>

        <!-- Footer Help -->
        <p class="text-center text-xs text-slate-500 dark:text-slate-400">
            Need help? Reach out to our family support team at <a href="mailto:info@globalnikkah.com" class="text-emerald-600 dark:text-emerald-400 hover:underline">info@globalnikkah.com</a>
        </p>

    </div>
</div>
@endsection

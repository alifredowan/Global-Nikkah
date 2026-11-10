@extends('layouts.app')

@section('title', 'Verify Your Email Address - Global Nikkah')

@section('content')
<div class="py-16 bg-slate-50 dark:bg-slate-950 min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-6">

        <!-- Top Islamic Calligraphy & Badge -->
        <div class="text-center space-y-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                <span>🛡️</span> Mandatory Email Verification
            </span>
            <p class="font-arabic text-xl sm:text-2xl text-amber-600 dark:text-amber-400 font-medium">بِسْمِ ٱللَّهِ ٱلرَّحْمَٰنِ ٱلرَّحِيمِ</p>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white font-heading">
                Check Your Email to Activate
            </h1>
            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 max-w-sm mx-auto">
                Your account will remain locked until you confirm and approve the verification link sent to your email.
            </p>
        </div>

        <!-- Verification Notice Card -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl overflow-hidden p-6 sm:p-8 space-y-6">

            <!-- Logo with Verified Status Badge -->
            <div class="text-center">
                <div class="relative inline-flex items-center justify-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Global Nikah Logo" class="w-20 h-20 rounded-3xl object-cover shadow-xl border-2 border-emerald-500/30">
                    <span class="absolute -top-1 -right-1 flex h-4 w-4">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-4 w-4 bg-emerald-500"></span>
                    </span>
                </div>

                @php
                    $targetEmail = session('registered_email') ?? session('unverified_email') ?? (Auth::check() ? Auth::user()->email : null);
                    $devUser = Auth::user() ?? ($targetEmail ? \App\Models\User::where('email', $targetEmail)->first() : null);
                    $devVerifyUrl = ($devUser && app()->environment('local')) ? URL::temporarySignedRoute(
                        'verification.verify',
                        now()->addMinutes(2880),
                        ['id' => $devUser->getKey(), 'hash' => sha1($devUser->getEmailForVerification())]
                    ) : null;
                @endphp

                <div class="mt-4">
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Verification Sent To</p>
                    <p class="text-sm font-extrabold text-slate-900 dark:text-white mt-0.5 break-all">
                        {{ $targetEmail ?: 'your registered email address' }}
                    </p>
                </div>
            </div>

            <!-- Steps & Explanation -->
            <div class="space-y-3 p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/60 text-xs text-slate-600 dark:text-slate-300">
                <div class="flex items-start gap-2.5">
                    <span class="w-5 h-5 rounded-full bg-emerald-600 text-white font-bold flex items-center justify-center text-[10px] shrink-0 mt-0.5">1</span>
                    <p>Open your email inbox (and check spam or junk if needed) for a message from <strong>info@globalnikkah.com</strong>.</p>
                </div>
                <div class="flex items-start gap-2.5">
                    <span class="w-5 h-5 rounded-full bg-emerald-600 text-white font-bold flex items-center justify-center text-[10px] shrink-0 mt-0.5">2</span>
                    <p>Click the green <strong>"Verify Email & Activate Profile"</strong> button.</p>
                </div>
                <div class="flex items-start gap-2.5">
                    <span class="w-5 h-5 rounded-full bg-emerald-600 text-white font-bold flex items-center justify-center text-[10px] shrink-0 mt-0.5">3</span>
                    <p>Your profile will be approved and you can proceed with your matrimonial profile and preferences.</p>
                </div>
            </div>

            <!-- Resend Section -->
            <div class="pt-2 border-t border-slate-100 dark:border-slate-800">
                <p class="text-xs text-center text-slate-500 dark:text-slate-400 mb-3">
                    Didn't receive the email? We can dispatch a new link.
                </p>

                <form action="{{ route('verification.send') }}" method="POST" class="space-y-3" x-data="{ submitting: false }" @submit="submitting = true">
                    @csrf
                    @if(! $targetEmail)
                        <div>
                            <input type="email" name="email" required placeholder="Enter your registered email address"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                    @else
                        <input type="hidden" name="email" value="{{ $targetEmail }}">
                    @endif

                    <button type="submit"
                            :disabled="submitting"
                            class="w-full py-3 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 disabled:opacity-60 disabled:cursor-not-allowed dark:bg-slate-700 dark:hover:bg-slate-600 text-white font-bold text-xs transition shadow-xs flex items-center justify-center gap-2 cursor-pointer">
                        <span x-show="!submitting">🔄 Resend Verification Email</span>
                        <span x-show="submitting" x-cloak>⏳ Sending fresh link...</span>
                    </button>
                </form>

                @if($devVerifyUrl)
                    <div class="mt-4 p-3.5 rounded-2xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-center space-y-2">
                        <div class="flex items-center justify-center gap-1.5 text-xs font-bold text-amber-800 dark:text-amber-300">
                            <span>⚡</span>
                            <span>Local Development Shortcut</span>
                        </div>
                        <p class="text-[11px] text-amber-700 dark:text-amber-400">
                            Local SMTP/Brevo delivery may be delayed or blocked by email providers. Click below to verify instantly:
                        </p>
                        <a href="{{ $devVerifyUrl }}"
                           class="inline-flex items-center justify-center w-full py-2.5 px-4 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs shadow-sm transition">
                            🚀 Verify Account Instantly (Local Dev)
                        </a>
                    </div>
                @endif
            </div>

            <!-- Back to Sign in link -->
            <div class="text-center pt-2">
                <a href="{{ route('login') }}" class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:underline">
                    &larr; Return to Sign In
                </a>
            </div>

        </div>

        <p class="text-center text-[11px] text-slate-400 dark:text-slate-500">
            Need assistance? Reach out to our support team at <a href="mailto:info@globalnikkah.com" class="text-emerald-600 dark:text-emerald-400 hover:underline">info@globalnikkah.com</a>
        </p>

    </div>
</div>
@endsection

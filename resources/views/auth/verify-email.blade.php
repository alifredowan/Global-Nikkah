@extends('layouts.app')

@section('title', 'Verify Your Email Address • Global Nikkah')

@section('content')
<div class="py-12 sm:py-16 bg-[var(--bg-canvas)] min-h-[calc(100vh-4rem)] flex items-center justify-center px-4 sm:px-6">
    <div class="max-w-md w-full space-y-6">

        <!-- Verification Notice Card -->
        <div class="card-ledger pattern-header p-6 sm:p-8 bg-[var(--bg-surface)] border border-[var(--border-default)] shadow-sm space-y-6">

            <div class="text-center pb-4 border-b border-[var(--border-subtle)]">
                <a href="{{ route('landing') }}" class="inline-block mb-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Global Nikah" class="w-12 h-12 rounded-[var(--radius-sm)] object-cover mx-auto border border-[var(--border-default)] shadow-xs">
                </a>
                <h1 class="text-2xl font-serif font-bold text-[var(--text-primary)]">
                    Verify your email address
                </h1>
                <p class="text-xs text-[var(--text-secondary)] mt-1.5 leading-relaxed">
                    To keep our matrimonial directory safe and serious, every candidate confirms their email before searching or contacting others.
                </p>
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

            @if(session('status') === 'verification-link-sent')
                <div class="p-3.5 rounded-[var(--radius-sm)] bg-[var(--color-success-bg)] border border-[var(--color-success-border)] text-xs text-[var(--color-success)] flex items-start gap-2">
                    <svg class="w-4 h-4 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
                    <span>A fresh verification link has been sent to your email inbox.</span>
                </div>
            @endif

            <div class="p-4 rounded-[var(--radius-sm)] bg-[var(--bg-subtle)] border border-[var(--border-subtle)] text-xs text-[var(--text-secondary)] space-y-2">
                <p class="text-[11px] font-bold uppercase tracking-wider text-[var(--text-muted)]">Verification sent to</p>
                <p class="text-sm font-bold text-[var(--text-primary)] break-all">
                    {{ $targetEmail ?: 'your registered email address' }}
                </p>
                <p class="text-[11px] text-[var(--text-muted)] pt-1">
                    Please click the link inside to activate your candidate profile. If you don't see it within a couple of minutes, please check your spam or junk folder.
                </p>
            </div>

            <!-- Resend Form -->
            <div class="pt-2 border-t border-[var(--border-subtle)] space-y-3">
                <p class="text-xs text-center text-[var(--text-secondary)]">
                    Did not receive the message?
                </p>

                <form action="{{ route('verification.send') }}" method="POST" class="space-y-3" x-data="{ submitting: false }" @submit="submitting = true">
                    @csrf
                    @if(! $targetEmail)
                        <div>
                            <input type="email" name="email" required placeholder="Enter your registered email"
                                   class="w-full h-11 px-3.5 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-xs text-[var(--text-primary)] outline-none focus:ring-2 focus:ring-[var(--accent-primary)]">
                        </div>
                    @else
                        <input type="hidden" name="email" value="{{ $targetEmail }}">
                    @endif

                    <button type="submit"
                            :disabled="submitting"
                            class="btn-secondary w-full text-xs font-semibold py-2.5">
                        <span x-show="!submitting">Resend verification email</span>
                        <span x-show="submitting" x-cloak>Sending link...</span>
                    </button>
                </form>

                @if($devVerifyUrl)
                    <div class="mt-4 p-3 rounded-[var(--radius-sm)] bg-[var(--secondary-subtle)] border border-[var(--secondary-border)] text-center space-y-1.5">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[var(--secondary-text)] block">Local Development Link</span>
                        <a href="{{ $devVerifyUrl }}" class="text-xs font-bold text-[var(--accent-text)] underline block">
                            Click here to verify email directly &rarr;
                        </a>
                    </div>
                @endif
            </div>

            <div class="text-center pt-2 text-xs text-[var(--text-muted)]">
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-[var(--text-secondary)] hover:text-[var(--color-danger)] underline cursor-pointer">
                        Sign out or use a different account
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection

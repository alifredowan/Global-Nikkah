@extends('layouts.app')

@section('title', 'Sign In • Global Nikkah')

@section('content')
<div class="py-12 sm:py-16 bg-[var(--bg-canvas)] min-h-[calc(100vh-4rem)] flex items-center justify-center">
    <div class="w-full max-w-md px-4 sm:px-6">
        <div class="card-ledger pattern-header p-6 sm:p-8 bg-[var(--bg-surface)] border border-[var(--border-default)] shadow-sm">

            <!-- Dignified Header -->
            <div class="text-center pb-5 mb-5 border-b border-[var(--border-subtle)]">
                <a href="{{ route('landing') }}" class="inline-block mb-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Global Nikah" class="w-12 h-12 rounded-[var(--radius-sm)] object-cover mx-auto border border-[var(--border-default)] shadow-xs">
                </a>
                <h1 class="text-2xl font-serif font-bold text-[var(--text-primary)]">
                    Assalamu Alaikum
                </h1>
                <p class="text-xs text-[var(--text-secondary)] mt-1">
                    Sign in to continue your matrimonial search or review guardian requests.
                </p>
            </div>

            @if(session('status'))
                <div class="mb-4 p-3 rounded-[var(--radius-sm)] bg-[var(--color-info-bg)] border border-[var(--color-info-border)] text-[var(--color-info)] text-xs">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-3 rounded-[var(--radius-sm)] bg-[var(--color-danger-bg)] border border-[var(--color-danger-border)] text-[var(--color-danger)] text-xs space-y-1">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-[var(--text-secondary)] mb-1.5">Email address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full h-11 px-3.5 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs focus:ring-2 focus:ring-[var(--accent-primary)] outline-none"
                           placeholder="your.email@example.com">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-semibold text-[var(--text-secondary)]">Password</label>
                        <a href="{{ route('password.request') }}" class="text-xs font-semibold text-[var(--secondary-text)] hover:underline">
                            Forgot password?
                        </a>
                    </div>
                    <div class="relative" x-data="{ showPassword: false }">
                        <input :type="showPassword ? 'text' : 'password'" id="password" name="password" required
                               class="w-full h-11 px-3.5 pr-11 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs focus:ring-2 focus:ring-[var(--accent-primary)] outline-none"
                               placeholder="Enter your password">
                        <button type="button" @click="showPassword = !showPassword"
                                aria-label="Toggle password visibility"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-[var(--text-muted)] hover:text-[var(--text-primary)] focus:outline-none cursor-pointer p-1"
                                tabindex="-1">
                            <svg x-show="!showPassword" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg x-show="showPassword" style="display: none;" class="w-4 h-4 text-[var(--accent-primary)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                                <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 text-[var(--text-secondary)] cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded-[var(--radius-xs)] text-[var(--accent-primary)] focus:ring-[var(--accent-primary)] border-[var(--border-default)]">
                        <span>Keep me signed in</span>
                    </label>
                </div>

                <x-turnstile-captcha :enabled="$turnstileEnabled" :site-key="$turnstileSiteKey" action="login" />

                <button type="submit" class="btn-primary w-full text-sm font-bold h-12 mt-2">
                    Sign in to account
                </button>
            </form>

            <div class="text-center mt-6 pt-4 text-xs text-[var(--text-secondary)] border-t border-[var(--border-subtle)] space-y-2">
                <p>
                    Seeking marriage? <a href="{{ route('register') }}" class="font-bold text-[var(--accent-text)] hover:underline">Create a new profile</a>
                </p>
                <p class="text-[11px] text-[var(--text-muted)]">
                    Guardian (Wali) accounts are free forever.
                </p>
            </div>
        </div>
    </div>
</div>

@if($turnstileEnabled)
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
@endif
@endsection

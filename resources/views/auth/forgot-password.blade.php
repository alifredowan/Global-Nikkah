@extends('layouts.app')

@section('title', 'Forgot Password • Global Nikkah')

@section('content')
<div class="py-12 sm:py-16 bg-[var(--bg-canvas)] min-h-[calc(100vh-4rem)] flex items-center justify-center">
    <div class="w-full max-w-md px-4 sm:px-6">
        <div class="card-ledger pattern-header p-6 sm:p-8 bg-[var(--bg-surface)] border border-[var(--border-default)] shadow-sm">

            <div class="text-center pb-5 mb-5 border-b border-[var(--border-subtle)]">
                <a href="{{ route('landing') }}" class="inline-block mb-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Global Nikah" class="w-12 h-12 rounded-[var(--radius-sm)] object-cover mx-auto border border-[var(--border-default)] shadow-xs">
                </a>
                <h1 class="text-2xl font-serif font-bold text-[var(--text-primary)]">Reset your password</h1>
                <p class="text-xs text-[var(--text-secondary)] mt-1">
                    Enter your email address and we will send you a secure link to choose a new password.
                </p>
            </div>

            <!-- Session Status Alert -->
            @if (session('status'))
                <div class="mb-4 p-3.5 rounded-[var(--radius-sm)] bg-[var(--color-success-bg)] border border-[var(--color-success-border)] text-xs text-[var(--color-success)] flex items-start gap-2">
                    <svg class="w-4 h-4 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- Developer Local Testing Quick Link when in log mode -->
            @if (session('dev_reset_url'))
                <div class="mb-4 p-3.5 rounded-[var(--radius-sm)] bg-[var(--secondary-subtle)] border border-[var(--secondary-border)] text-xs text-[var(--secondary-text)]">
                    <strong class="font-bold block mb-1">Local Testing Shortcut:</strong>
                    <p class="text-[11px] mb-2 leading-relaxed">
                        @if(config('mail.default') === 'log')
                            Your environment is currently set to log mail. You can use the link below to test immediately:
                        @else
                            Direct test link:
                        @endif
                    </p>
                    <a href="{{ session('dev_reset_url') }}" class="font-bold underline">
                        Proceed to reset password &rarr;
                    </a>
                </div>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="mb-4 p-3.5 rounded-[var(--radius-sm)] bg-[var(--color-danger-bg)] border border-[var(--color-danger-border)] text-xs text-[var(--color-danger)]">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-[var(--text-secondary)] mb-1.5">
                        Email address
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full h-11 px-3.5 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs focus:ring-2 focus:ring-[var(--accent-primary)] outline-none"
                           placeholder="your.email@example.com">
                </div>

                <button type="submit" class="btn-primary w-full text-xs font-bold py-3">
                    Send password reset link
                </button>
            </form>

            <div class="text-center mt-6 pt-4 text-xs text-[var(--text-muted)] border-t border-[var(--border-subtle)]">
                Remembered your password? <a href="{{ route('login') }}" class="font-bold text-[var(--accent-text)] hover:underline">Return to sign in</a>
            </div>
        </div>
    </div>
</div>
@endsection

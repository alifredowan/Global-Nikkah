@extends('layouts.app')

@section('title', 'Set New Password • Global Nikkah')

@section('content')
<div class="py-12 sm:py-16 bg-[var(--bg-canvas)] min-h-[calc(100vh-4rem)] flex items-center justify-center">
    <div class="w-full max-w-md px-4 sm:px-6">
        <div class="card-ledger pattern-header p-6 sm:p-8 bg-[var(--bg-surface)] border border-[var(--border-default)] shadow-sm">

            <div class="text-center pb-5 mb-5 border-b border-[var(--border-subtle)]">
                <a href="{{ route('landing') }}" class="inline-block mb-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Global Nikah" class="w-12 h-12 rounded-[var(--radius-sm)] object-cover mx-auto border border-[var(--border-default)] shadow-xs">
                </a>
                <h1 class="text-2xl font-serif font-bold text-[var(--text-primary)]">Set a new password</h1>
                <p class="text-xs text-[var(--text-secondary)] mt-1">
                    Choose a strong, memorable password to secure your matrimonial profile.
                </p>
            </div>

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

            <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label for="email" class="block text-xs font-semibold text-[var(--text-secondary)] mb-1.5">
                        Email address
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email', $email) }}" required autofocus
                           class="w-full h-11 px-3.5 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs focus:ring-2 focus:ring-[var(--accent-primary)] outline-none"
                           placeholder="your.email@example.com">
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-[var(--text-secondary)] mb-1.5">
                        New password
                    </label>
                    <div class="relative" x-data="{ showPass: false }">
                        <input :type="showPass ? 'text' : 'password'" id="password" name="password" required
                               class="w-full h-11 px-3.5 pr-11 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs focus:ring-2 focus:ring-[var(--accent-primary)] outline-none"
                               placeholder="At least 8 characters">
                        <button type="button" @click="showPass = !showPass"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-[var(--text-muted)] hover:text-[var(--text-primary)] p-1 cursor-pointer"
                                aria-label="Toggle password visibility"
                                tabindex="-1">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-[var(--text-secondary)] mb-1.5">
                        Confirm new password
                    </label>
                    <div class="relative" x-data="{ showConfirm: false }">
                        <input :type="showConfirm ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" required
                               class="w-full h-11 px-3.5 pr-11 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs focus:ring-2 focus:ring-[var(--accent-primary)] outline-none"
                               placeholder="Re-enter password">
                        <button type="button" @click="showConfirm = !showConfirm"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-[var(--text-muted)] hover:text-[var(--text-primary)] p-1 cursor-pointer"
                                aria-label="Toggle password visibility"
                                tabindex="-1">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-primary w-full text-xs font-bold py-3 mt-2">
                    Update password &amp; sign in
                </button>
            </form>

            <div class="text-center mt-6 pt-4 text-xs text-[var(--text-muted)] border-t border-[var(--border-subtle)]">
                Back to <a href="{{ route('login') }}" class="font-bold text-[var(--accent-text)] hover:underline">Sign in</a>
            </div>

        </div>
    </div>
</div>
@endsection

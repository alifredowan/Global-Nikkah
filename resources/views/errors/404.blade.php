@extends('layouts.app')

@section('title', 'Page Not Found — Global Nikkah')

@section('content')
<div class="py-16 sm:py-24 bg-[var(--bg-canvas)] min-h-[75vh] flex items-center justify-center">
    <div class="max-w-md mx-auto px-4 sm:px-6 text-center space-y-6">

        <!-- Decorative Mihrab Arch Artwork -->
        <div class="w-20 h-28 mx-auto mihrab-arch bg-[var(--accent-subtle)] border border-[var(--accent-border)] flex items-center justify-center text-[var(--accent-text)] shadow-xs">
            <svg class="w-10 h-10 text-[var(--accent-gold)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M12 2C8 6 4 10 4 16a8 8 0 0 0 16 0c0-6-4-10-8-14z"/>
                <circle cx="12" cy="15" r="2"/>
            </svg>
        </div>

        <div class="space-y-2">
            <span class="text-xs font-semibold uppercase tracking-wider text-[var(--accent-text)]">
                Error 404 &bull; Page Not Found
            </span>
            <h1 class="font-serif text-3xl sm:text-4xl font-semibold text-[var(--text-primary)]">
                Take a gentle breath.
            </h1>
            <p class="text-sm text-[var(--text-secondary)] leading-relaxed">
                The page you are looking for has been moved or does not exist. No rush — take your time and return when you are ready.
            </p>
        </div>

        <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('home') }}" class="btn-primary w-full sm:w-auto h-11 px-6 text-xs font-semibold inline-flex items-center justify-center gap-1.5">
                <span aria-hidden="true">&larr;</span>
                <span>Return to Home</span>
            </a>
            @auth
                <a href="{{ route('dashboard') }}" class="btn-secondary w-full sm:w-auto h-11 px-6 text-xs font-semibold inline-flex items-center justify-center">
                    My Dashboard
                </a>
            @else
                <a href="{{ route('discovery.index') }}" class="btn-secondary w-full sm:w-auto h-11 px-6 text-xs font-semibold inline-flex items-center justify-center">
                    Browse Candidates
                </a>
            @endauth
        </div>

    </div>
</div>
@endsection


@props([
    'user' => null,
])

@php
    $u = $user ?? Auth::user();
    if (!$u) return;

    $unreadMsgCount = $u->unreadMessagesCount();

    $discoverRoute = Route::has('discovery.index') ? route('discovery.index') : url('/discovery');
    $interestsRoute = Route::has('interests.index') ? route('interests.index') : (Route::has('proposals.index') ? route('proposals.index') : url('/interests'));
    $chatRoute = Route::has('chat.index') ? route('chat.index') : url('/chat');
    $shortlistRoute = Route::has('bookmarks.index') ? route('bookmarks.index') : (Route::has('shortlists.index') ? route('shortlists.index') : url('/bookmarks'));
    $profileRoute = Route::has('profile.show') ? route('profile.show') : url('/profile');

    $isDiscover = request()->routeIs('discovery.*');
    $isInterests = request()->routeIs('interests.*') || request()->routeIs('proposals.*');
    $isChat = request()->routeIs('chat.*');
    $isShortlist = request()->routeIs('bookmarks.*') || request()->routeIs('shortlists.*');
    $isProfile = request()->routeIs('profile.*');
@endphp

<nav class="lg:hidden fixed bottom-0 inset-x-0 z-40 h-14 bg-[var(--bg-surface)] border-t border-[var(--border-default)] flex items-center justify-around px-2 shadow-lg transition-colors"
     aria-label="Mobile Navigation"
     x-data="{
         tabMessageCount: {{ (int)$unreadMsgCount }}
     }"
     @update-message-badge.window="tabMessageCount = $event.detail.count">

    {{-- 1. Discover --}}
    <a href="{{ $discoverRoute }}"
       class="flex-1 flex flex-col items-center justify-center h-full py-1 transition-colors {{ $isDiscover ? 'text-[var(--accent-primary)] font-semibold' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)]' }}"
       aria-label="Discover">
        <svg class="w-5 h-5 {{ $isDiscover ? 'stroke-2' : 'stroke-[1.5]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
        </svg>
        <span class="text-[10px] mt-0.5 tracking-tight">Discover</span>
    </a>

    {{-- 2. Interests --}}
    <a href="{{ $interestsRoute }}"
       class="flex-1 flex flex-col items-center justify-center h-full py-1 transition-colors {{ $isInterests ? 'text-[var(--accent-primary)] font-semibold' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)]' }}"
       aria-label="Interests">
        <svg class="w-5 h-5 {{ $isInterests ? 'stroke-2 fill-[var(--accent-primary)]/15' : 'stroke-[1.5]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
        </svg>
        <span class="text-[10px] mt-0.5 tracking-tight">Interests</span>
    </a>

    {{-- 3. Messages (with badge) --}}
    <a href="{{ $chatRoute }}"
       class="relative flex-1 flex flex-col items-center justify-center h-full py-1 transition-colors {{ $isChat ? 'text-[var(--accent-primary)] font-semibold' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)]' }}"
       aria-label="Messages">
        <div class="relative">
            <svg class="w-5 h-5 {{ $isChat ? 'stroke-2 fill-[var(--accent-primary)]/15' : 'stroke-[1.5]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/>
            </svg>
            <x-header.icon-badge :count="$unreadMsgCount" label="unread messages" alpine="tabMessageCount" />
        </div>
        <span class="text-[10px] mt-0.5 tracking-tight">Messages</span>
    </a>

    {{-- 4. Shortlist --}}
    <a href="{{ $shortlistRoute }}"
       class="flex-1 flex flex-col items-center justify-center h-full py-1 transition-colors {{ $isShortlist ? 'text-[var(--accent-primary)] font-semibold' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)]' }}"
       aria-label="Shortlist">
        <svg class="w-5 h-5 {{ $isShortlist ? 'stroke-2 fill-[var(--accent-primary)]/15' : 'stroke-[1.5]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0Z"/>
        </svg>
        <span class="text-[10px] mt-0.5 tracking-tight">Shortlist</span>
    </a>

    {{-- 5. Profile --}}
    <a href="{{ $profileRoute }}"
       class="flex-1 flex flex-col items-center justify-center h-full py-1 transition-colors {{ $isProfile ? 'text-[var(--accent-primary)] font-semibold' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)]' }}"
       aria-label="Profile">
        <svg class="w-5 h-5 {{ $isProfile ? 'stroke-2' : 'stroke-[1.5]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
        </svg>
        <span class="text-[10px] mt-0.5 tracking-tight">Profile</span>
    </a>
</nav>


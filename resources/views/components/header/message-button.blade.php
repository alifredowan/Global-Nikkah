@props([
    'route' => 'chat.index',
    'count' => null,
])

@php
    $resolvedCount = $count ?? (Auth::check() ? Auth::user()->unreadMessagesCount() : 0);
    $isActive = request()->routeIs('chat.*');
    $url = Route::has($route) ? route($route) : url('/chat');
@endphp

<div class="relative"
     x-data="{
         messageCount: {{ (int)$resolvedCount }}
     }"
     @update-message-badge.window="messageCount = $event.detail.count">
    <a href="{{ $url }}"
       class="group relative w-11 h-11 flex items-center justify-center rounded-full text-[var(--text-secondary)] hover:text-[var(--text-primary)] hover:bg-[var(--text-primary)]/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[var(--accent-primary)] focus-visible:ring-offset-2 transition-colors cursor-pointer {{ $isActive ? 'text-[var(--accent-primary)] after:absolute after:bottom-0 after:left-2 after:right-2 after:h-0.5 after:bg-[var(--accent-primary)]' : '' }}"
       title="Messages"
       aria-label="{{ $resolvedCount > 0 ? $resolvedCount . ' unread messages' : 'Messages' }}">

        {{-- Lucide MessageSquare / Chat Bubble Icon (22px, 1.5px stroke, filled/stroke-2 when active) --}}
        <svg class="w-[22px] h-[22px] {{ $isActive ? 'fill-[var(--accent-primary)]/15 stroke-[var(--accent-primary)]' : 'stroke-current' }}"
             fill="none"
             stroke="currentColor"
             stroke-width="{{ $isActive ? '2' : '1.5' }}"
             stroke-linecap="round"
             stroke-linejoin="round"
             viewBox="0 0 24 24"
             aria-hidden="true">
            <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z" />
        </svg>

        {{-- 18px Badge (Accent Color, White Text, 1-9 then 9+, hidden at 0) --}}
        <x-header.icon-badge :count="$resolvedCount" label="unread messages" alpine="messageCount" />
    </a>
</div>


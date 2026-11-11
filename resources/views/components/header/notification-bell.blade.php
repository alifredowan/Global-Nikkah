@props([
    'count' => null,
])

@php
    $resolvedCount = $count ?? (Auth::check() ? Auth::user()->unreadNotifications->count() : 0);
@endphp

<div class="relative"
     x-data="{
         notifOpen: false,
         activeTab: 'all',
         unreadCount: {{ (int)$resolvedCount }},
         notifications: [],
         loading: false,

         get filteredNotifications() {
             if (this.activeTab === 'all') return this.notifications;
             return this.notifications.filter(item => item.category === this.activeTab);
         },

         getTabCount(category) {
             if (category === 'all') return this.notifications.length;
             return this.notifications.filter(item => item.category === category).length;
         },

         fetchNotifications(silent = false) {
             if (!silent) this.loading = true;
             fetch('{{ route('notifications.index') }}', {
                 headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
             })
             .then(res => res.json())
             .then(data => {
                 this.notifications = data.notifications || [];
                 this.unreadCount = typeof data.unread_count !== 'undefined' ? data.unread_count : 0;
                 if (!silent) this.loading = false;

                 if (typeof data.unread_messages_count !== 'undefined') {
                     window.dispatchEvent(new CustomEvent('update-message-badge', {
                         detail: { count: data.unread_messages_count }
                     }));
                 }
             })
             .catch(() => { if (!silent) this.loading = false; });
         },

         markAsRead(id, url) {
             fetch('/notifications/' + id + '/read', {
                 method: 'POST',
                 headers: {
                     'X-CSRF-TOKEN': '{{ csrf_token() }}',
                     'Accept': 'application/json',
                     'X-Requested-With': 'XMLHttpRequest'
                 }
             })
             .then(res => res.json())
             .then(data => {
                 this.unreadCount = data.unread_count ?? Math.max(0, this.unreadCount - 1);
                 const item = this.notifications.find(n => n.id === id);
                 if (item) item.read_at = new Date().toISOString();
                 if (url) window.location.href = url;
             })
             .catch(() => {
                 if (url) window.location.href = url;
             });
         },

         markAllRead() {
             fetch('{{ route('notifications.read-all') }}', {
                 method: 'POST',
                 headers: {
                     'X-CSRF-TOKEN': '{{ csrf_token() }}',
                     'Accept': 'application/json',
                     'X-Requested-With': 'XMLHttpRequest'
                 }
             })
             .then(res => res.json())
             .then(data => {
                 this.unreadCount = 0;
                 this.notifications.forEach(n => n.read_at = new Date().toISOString());
             })
             .catch(() => {});
         }
     }"
     x-init="fetchNotifications(); setInterval(() => fetchNotifications(true), 5000);"
     @click.outside="notifOpen = false"
     @keydown.escape.window="notifOpen = false"
     @new-notification-received.window="
         unreadCount = Math.max(unreadCount + 1, 1);
         notifications.unshift($event.detail);
         if(notifications.length > 30) notifications.pop();
     ">

    {{-- Bell Button (44x44px hit area, 22px Lucide icon, 1.5px stroke) --}}
    <button type="button"
            @click="notifOpen = !notifOpen; if(notifOpen) { fetchNotifications(); }"
            class="group relative w-11 h-11 flex items-center justify-center rounded-full text-[var(--text-secondary)] hover:text-[var(--text-primary)] hover:bg-[var(--text-primary)]/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[var(--accent-primary)] focus-visible:ring-offset-2 transition-colors cursor-pointer"
            :class="notifOpen ? 'text-[var(--accent-primary)] after:absolute after:bottom-0 after:left-2 after:right-2 after:h-0.5 after:bg-[var(--accent-primary)]' : ''"
            title="Notifications"
            aria-label="{{ $resolvedCount > 0 ? $resolvedCount . ' notifications' : 'Notifications' }}"
            :aria-expanded="notifOpen"
            aria-haspopup="dialog">

        {{-- Monoline Lucide Bell Icon --}}
        <svg class="w-[22px] h-[22px] transition {{ $resolvedCount > 0 ? 'stroke-[var(--accent-primary)]' : 'stroke-current' }}"
             fill="none"
             stroke="currentColor"
             stroke-width="1.5"
             stroke-linecap="round"
             stroke-linejoin="round"
             viewBox="0 0 24 24"
             aria-hidden="true">
            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9" />
            <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0" />
        </svg>

        {{-- 18px Badge (Accent Color, White Text, 1-9 then 9+, hidden at 0) --}}
        <x-header.icon-badge :count="$resolvedCount" label="notifications" alpine="unreadCount" />
    </button>

    {{-- DESKTOP DROPDOWN (360px wide, max 70vh, 8px radius, subtle shadow) --}}
    <div x-show="notifOpen"
         x-cloak
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 translate-y-1 scale-98"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-1 scale-98"
         class="hidden sm:block absolute right-0 mt-2 w-[360px] max-h-[70vh] rounded-[8px] bg-[var(--bg-surface)] border border-[var(--border-default)] shadow-lg overflow-hidden z-50 text-[var(--text-primary)]">

        {{-- Header & Title --}}
        <div class="px-4 py-3 border-b border-[var(--border-subtle)] flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-sm font-semibold text-[var(--text-primary)]">Notifications</span>
                <span x-show="unreadCount > 0"
                      x-text="unreadCount + ' new'"
                      class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-[var(--accent-subtle)] text-[var(--accent-primary)] border border-[var(--accent-border)]">
                </span>
            </div>
            <button type="button"
                    x-show="unreadCount > 0"
                    @click="markAllRead()"
                    class="text-xs font-medium text-[var(--accent-primary)] hover:underline focus:outline-none cursor-pointer">
                Mark all as read
            </button>
        </div>

        {{-- 4 Category Tabs: All, Interests, Messages, Family --}}
        <div class="flex items-center border-b border-[var(--border-subtle)] bg-[var(--bg-subtle)]/50 px-2 text-xs font-medium text-[var(--text-secondary)]">
            <button type="button"
                    @click="activeTab = 'all'"
                    class="flex-1 py-2 text-center border-b-2 transition cursor-pointer"
                    :class="activeTab === 'all' ? 'border-[var(--accent-primary)] text-[var(--accent-primary)] font-semibold' : 'border-transparent hover:text-[var(--text-primary)]'">
                All
            </button>
            <button type="button"
                    @click="activeTab = 'interests'"
                    class="flex-1 py-2 text-center border-b-2 transition cursor-pointer"
                    :class="activeTab === 'interests' ? 'border-[var(--accent-primary)] text-[var(--accent-primary)] font-semibold' : 'border-transparent hover:text-[var(--text-primary)]'">
                Interests
            </button>
            <button type="button"
                    @click="activeTab = 'messages'"
                    class="flex-1 py-2 text-center border-b-2 transition cursor-pointer"
                    :class="activeTab === 'messages' ? 'border-[var(--accent-primary)] text-[var(--accent-primary)] font-semibold' : 'border-transparent hover:text-[var(--text-primary)]'">
                Messages
            </button>
            <button type="button"
                    @click="activeTab = 'family'"
                    class="flex-1 py-2 text-center border-b-2 transition cursor-pointer"
                    :class="activeTab === 'family' ? 'border-[var(--accent-primary)] text-[var(--accent-primary)] font-semibold' : 'border-transparent hover:text-[var(--text-primary)]'">
                Family
            </button>
        </div>

        {{-- Scrollable Notification List --}}
        <div class="overflow-y-auto divide-y divide-[var(--border-subtle)] max-h-[calc(70vh-140px)]"
             style="scrollbar-width: thin;">

            <div x-show="loading" class="py-8 text-center text-xs text-[var(--text-muted)]">
                Loading notifications...
            </div>

            <template x-for="item in filteredNotifications" :key="item.id">
                <div @click="markAsRead(item.id, item.data?.action_url)"
                     class="p-3 sm:p-3.5 hover:bg-[var(--bg-subtle)] transition flex items-start gap-3 cursor-pointer group"
                     :class="{ 'bg-[var(--accent-subtle)]/40': !item.read_at }">

                    {{-- Icon Badge --}}
                    <div class="w-8 h-8 rounded-full bg-[var(--bg-subtle)] border border-[var(--border-subtle)] flex items-center justify-center text-sm shrink-0">
                        <template x-if="item.category === 'interests'">
                            <span class="text-[var(--accent-primary)]">💌</span>
                        </template>
                        <template x-if="item.category === 'messages'">
                            <span class="text-[var(--secondary-primary)]">💬</span>
                        </template>
                        <template x-if="item.category === 'family'">
                            <span class="text-[var(--accent-primary)]">🛡️</span>
                        </template>
                        <template x-if="item.category === 'general'">
                            <span x-text="item.data?.icon || '🔔'"></span>
                        </template>
                    </div>

                    {{-- Text Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <h4 class="text-xs font-semibold text-[var(--text-primary)] truncate"
                                x-text="item.data?.title || 'Notification'">
                            </h4>
                            <div class="flex items-center gap-1.5 shrink-0">
                                <span class="text-[11px] text-[var(--text-muted)] whitespace-nowrap"
                                      x-text="item.created_at"></span>
                                <span x-show="!item.read_at"
                                      class="w-2 h-2 rounded-full bg-[var(--accent-primary)] shrink-0"
                                      title="Unread"></span>
                            </div>
                        </div>
                        <p class="text-xs text-[var(--text-secondary)] mt-0.5 leading-snug line-clamp-1"
                           x-text="item.data?.message"></p>
                    </div>
                </div>
            </template>

            {{-- Empty State Friendly One-Liner --}}
            <div x-show="!loading && filteredNotifications.length === 0"
                 class="py-10 px-4 text-center text-xs text-[var(--text-muted)]">
                You're all caught up. No notifications here.
            </div>
        </div>

        {{-- Footer --}}
        <div class="p-2.5 border-t border-[var(--border-subtle)] bg-[var(--bg-subtle)]/40 flex items-center justify-between text-xs">
            <a href="{{ route('notifications.index') }}"
               class="text-xs font-medium text-[var(--accent-primary)] hover:underline">
                View all notifications &rarr;
            </a>
            <button type="button"
                    x-show="unreadCount > 0"
                    @click="markAllRead()"
                    class="text-xs font-medium text-[var(--text-muted)] hover:text-[var(--text-primary)]">
                Mark all as read
            </button>
        </div>
    </div>

    {{-- MOBILE FULL-SCREEN SHEET (Mobile: full-screen sheet instead of floating dropdown) --}}
    <div x-show="notifOpen"
         x-cloak
         class="sm:hidden fixed inset-0 z-50 bg-[var(--bg-surface)] flex flex-col text-[var(--text-primary)] animate-in fade-in duration-200">

        {{-- Mobile Top Bar --}}
        <div class="h-14 px-4 border-b border-[var(--border-default)] flex items-center justify-between bg-[var(--bg-surface)]">
            <div class="flex items-center gap-2">
                <span class="text-base font-semibold text-[var(--text-primary)]">Notifications</span>
                <span x-show="unreadCount > 0"
                      x-text="unreadCount + ' new'"
                      class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-[var(--accent-subtle)] text-[var(--accent-primary)]">
                </span>
            </div>
            <button type="button"
                    @click="notifOpen = false"
                    class="p-2 text-[var(--text-secondary)] hover:text-[var(--text-primary)] focus:outline-none"
                    aria-label="Close Notifications">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Mobile Category Tabs --}}
        <div class="flex items-center border-b border-[var(--border-subtle)] bg-[var(--bg-subtle)]/40 px-2 text-xs font-medium text-[var(--text-secondary)]">
            <button type="button"
                    @click="activeTab = 'all'"
                    class="flex-1 py-3 text-center border-b-2 transition"
                    :class="activeTab === 'all' ? 'border-[var(--accent-primary)] text-[var(--accent-primary)] font-semibold' : 'border-transparent'">
                All
            </button>
            <button type="button"
                    @click="activeTab = 'interests'"
                    class="flex-1 py-3 text-center border-b-2 transition"
                    :class="activeTab === 'interests' ? 'border-[var(--accent-primary)] text-[var(--accent-primary)] font-semibold' : 'border-transparent'">
                Interests
            </button>
            <button type="button"
                    @click="activeTab = 'messages'"
                    class="flex-1 py-3 text-center border-b-2 transition"
                    :class="activeTab === 'messages' ? 'border-[var(--accent-primary)] text-[var(--accent-primary)] font-semibold' : 'border-transparent'">
                Messages
            </button>
            <button type="button"
                    @click="activeTab = 'family'"
                    class="flex-1 py-3 text-center border-b-2 transition"
                    :class="activeTab === 'family' ? 'border-[var(--accent-primary)] text-[var(--accent-primary)] font-semibold' : 'border-transparent'">
                Family
            </button>
        </div>

        {{-- Mobile Notification List --}}
        <div class="flex-1 overflow-y-auto divide-y divide-[var(--border-subtle)] p-2">
            <div x-show="loading" class="py-8 text-center text-xs text-[var(--text-muted)]">
                Loading notifications...
            </div>

            <template x-for="item in filteredNotifications" :key="item.id">
                <div @click="markAsRead(item.id, item.data?.action_url); notifOpen = false;"
                     class="p-4 hover:bg-[var(--bg-subtle)] transition flex items-start gap-3 rounded-lg cursor-pointer"
                     :class="{ 'bg-[var(--accent-subtle)]/40': !item.read_at }">

                    <div class="w-9 h-9 rounded-full bg-[var(--bg-subtle)] border border-[var(--border-subtle)] flex items-center justify-center text-base shrink-0">
                        <template x-if="item.category === 'interests'">
                            <span class="text-[var(--accent-primary)]">💌</span>
                        </template>
                        <template x-if="item.category === 'messages'">
                            <span class="text-[var(--secondary-primary)]">💬</span>
                        </template>
                        <template x-if="item.category === 'family'">
                            <span class="text-[var(--accent-primary)]">🛡️</span>
                        </template>
                        <template x-if="item.category === 'general'">
                            <span x-text="item.data?.icon || '🔔'"></span>
                        </template>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-1">
                            <h4 class="text-sm font-semibold text-[var(--text-primary)] truncate"
                                x-text="item.data?.title || 'Notification'">
                            </h4>
                            <div class="flex items-center gap-1.5 shrink-0">
                                <span class="text-xs text-[var(--text-muted)] whitespace-nowrap"
                                      x-text="item.created_at"></span>
                                <span x-show="!item.read_at"
                                      class="w-2 h-2 rounded-full bg-[var(--accent-primary)] shrink-0"></span>
                            </div>
                        </div>
                        <p class="text-xs text-[var(--text-secondary)] mt-1 leading-snug line-clamp-2"
                           x-text="item.data?.message"></p>
                    </div>
                </div>
            </template>

            <div x-show="!loading && filteredNotifications.length === 0"
                 class="py-16 text-center text-sm text-[var(--text-muted)]">
                You're all caught up. No notifications here.
            </div>
        </div>

        {{-- Mobile Footer --}}
        <div class="p-3 border-t border-[var(--border-subtle)] bg-[var(--bg-surface)] flex items-center justify-between text-xs">
            <a href="{{ route('notifications.index') }}"
               @click="notifOpen = false"
               class="text-xs font-semibold text-[var(--accent-primary)] hover:underline">
                View all notifications &rarr;
            </a>
            <button type="button"
                    x-show="unreadCount > 0"
                    @click="markAllRead()"
                    class="text-xs font-medium text-[var(--text-muted)] hover:text-[var(--text-primary)]">
                Mark all as read
            </button>
        </div>
    </div>
</div>


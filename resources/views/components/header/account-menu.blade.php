@props([
    'user' => null,
])

@php
    $u = $user ?? Auth::user();
    if (!$u) return;

    $isVerified = (bool) ($u->is_verified ?? false);
    $plan = method_exists($u, 'isPro') && $u->isPro() ? 'Pro' : (isset($u->plan) && $u->plan !== 'free' ? 'Pro' : 'Free');
    $isGuardian = method_exists($u, 'isWali') ? $u->isWali() : ($u->role === 'family_rep' || $u->role === 'wali');
    $isAdmin = (method_exists($u, 'isAdmin') && $u->isAdmin()) || (method_exists($u, 'isModerator') && $u->isModerator()) || in_array($u->role, ['admin', 'super_admin', 'moderator'], true);

    $profileRoute = Route::has('profile.show') ? route('profile.show') : url('/profile');
    $familyRoute = Route::has('wali.dashboard') ? route('wali.dashboard') : (Route::has('family.dashboard') ? route('family.dashboard') : url('/wali'));
    $settingsRoute = Route::has('settings.index') ? route('settings.index') : url('/settings');
    $privacyRoute = Route::has('privacy') ? route('privacy') : url('/privacy');
    $adminRoute = Route::has('admin.dashboard') ? route('admin.dashboard') : url('/admin');
    $membershipRoute = Route::has('subscription.pricing') ? route('subscription.pricing') : (Route::has('membership.index') ? route('membership.index') : url('/pricing'));
    $helpRoute = Route::has('contact') ? route('contact') : (Route::has('how-it-works') ? route('how-it-works') : url('/contact'));
    $logoutRoute = Route::has('logout') ? route('logout') : url('/logout');

    $avatarUrl = $u->primary_photo_url ?? ($u->profile?->primary_photo_url ?? null);
    $initials = strtoupper(substr($u->name, 0, 1));
@endphp

<div class="relative"
     x-data="{
         open: false,
         mobileOpen: false,
         focusedIndex: -1,
         get menuItems() {
             const container = this.$refs.menuDropdown;
             return container ? Array.from(container.querySelectorAll('[role=\'menuitem\']')) : [];
         },
         focusItem(index) {
             const items = this.menuItems;
             if (!items.length) return;
             if (index < 0) index = items.length - 1;
             if (index >= items.length) index = 0;
             this.focusedIndex = index;
             items[index].focus();
         },
         closeMenu() {
             this.open = false;
             this.mobileOpen = false;
             this.focusedIndex = -1;
             if (this.$refs.avatarButton) {
                 this.$refs.avatarButton.focus();
             }
         }
     }"
     @click.outside="closeMenu()"
     @keydown.escape.window="closeMenu()">

    {{-- Avatar Button (44x44px hit area with 32x32px avatar + chevron) --}}
    <button type="button"
            x-ref="avatarButton"
            @click="if (window.innerWidth < 640) { mobileOpen = true; } else { open = !open; if(open) { $nextTick(() => focusItem(0)); } }"
            class="group w-11 h-11 flex items-center justify-center rounded-full text-[var(--text-secondary)] hover:bg-[var(--text-primary)]/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[var(--accent-primary)] focus-visible:ring-offset-2 transition-colors cursor-pointer"
            :class="open ? 'text-[var(--accent-primary)] after:absolute after:bottom-0 after:left-2 after:right-2 after:h-0.5 after:bg-[var(--accent-primary)]' : ''"
            title="Account"
            aria-label="Account menu for {{ $u->name }}"
            :aria-expanded="open"
            aria-haspopup="menu"
            id="account-menu-button">

        <div class="flex items-center gap-1">
            {{-- 32x32px Avatar Circle --}}
            <div class="w-8 h-8 rounded-full bg-[var(--accent-primary)] text-[var(--accent-contrast,#ffffff)] font-medium text-xs flex items-center justify-center overflow-hidden border border-[var(--border-subtle)] ring-1 ring-black/5 shrink-0">
                @if($avatarUrl && !str_contains($avatarUrl, 'unsplash'))
                    <img src="{{ $avatarUrl }}" alt="{{ $u->name }}" class="w-full h-full object-cover">
                @else
                    <span>{{ $initials }}</span>
                @endif
            </div>

            {{-- Small 14px Chevron Down --}}
            <svg class="w-3.5 h-3.5 text-[var(--text-muted)] group-hover:text-[var(--text-primary)] transition-transform duration-150"
                 :class="open ? 'rotate-180' : ''"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="1.5"
                 viewBox="0 0 24 24"
                 aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
            </svg>
        </div>
    </button>

    {{-- DESKTOP DROPDOWN (Width 272px [260-280px], 8px radius, subtle shadow) --}}
    <div x-show="open"
         x-ref="menuDropdown"
         x-cloak
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 translate-y-1 scale-98"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-1 scale-98"
         @keydown.down.prevent="focusItem(focusedIndex + 1)"
         @keydown.up.prevent="focusItem(focusedIndex - 1)"
         @keydown.home.prevent="focusItem(0)"
         @keydown.end.prevent="focusItem(menuItems.length - 1)"
         class="hidden sm:block absolute right-0 mt-2 w-[272px] rounded-[8px] bg-[var(--bg-surface)] border border-[var(--border-default)] shadow-lg overflow-hidden z-50 text-[var(--text-primary)]"
         role="menu"
         aria-labelledby="account-menu-button">

        {{-- Header row: avatar, display name, plan badge (Free or Pro) and Verified badge --}}
        <div class="p-3.5 border-b border-[var(--border-subtle)] bg-[var(--bg-subtle)]/40">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-[var(--accent-primary)] text-[var(--accent-contrast,#ffffff)] font-medium text-sm flex items-center justify-center overflow-hidden border border-[var(--border-subtle)] shrink-0">
                    @if($avatarUrl && !str_contains($avatarUrl, 'unsplash'))
                        <img src="{{ $avatarUrl }}" alt="{{ $u->name }}" class="w-full h-full object-cover">
                    @else
                        <span>{{ $initials }}</span>
                    @endif
                </div>

                <div class="min-w-0 flex-1">
                    <p class="text-xs font-semibold text-[var(--text-primary)] truncate" title="{{ $u->name }}">
                        {{ $u->name }}
                    </p>
                    <div class="flex items-center gap-1.5 mt-1">
                        {{-- Plan Badge (Free or Pro) --}}
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold leading-none {{ $plan === 'Pro' ? 'bg-[var(--accent-subtle)] text-[var(--accent-primary)] border border-[var(--accent-border)]' : 'bg-[var(--bg-muted)] text-[var(--text-secondary)] border border-[var(--border-default)]' }}">
                            {{ $plan }}
                        </span>

                        {{-- Verified Badge --}}
                        @if($isVerified)
                            <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded text-[10px] font-semibold leading-none bg-emerald-500/10 text-emerald-700 dark:text-emerald-300 border border-emerald-500/20" title="Verified Member">
                                <svg class="w-3 h-3 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                </svg>
                                <span>Verified</span>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Menu items in exact required order --}}
        <div class="py-1">
            {{-- 1. My profile --}}
            <a href="{{ $profileRoute }}"
               @click="closeMenu()"
               role="menuitem"
               tabindex="-1"
               class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-medium text-[var(--text-primary)] hover:bg-[var(--bg-subtle)] focus:bg-[var(--bg-subtle)] focus:outline-none transition-colors">
                <svg class="w-4 h-4 text-[var(--text-muted)]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                </svg>
                <span>My profile</span>
            </a>

            {{-- 2. If guardian or family rep: Family dashboard --}}
            @if($isGuardian)
                <a href="{{ $familyRoute }}"
                   @click="closeMenu()"
                   role="menuitem"
                   tabindex="-1"
                   class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-medium text-[var(--text-primary)] hover:bg-[var(--bg-subtle)] focus:bg-[var(--bg-subtle)] focus:outline-none transition-colors">
                    <svg class="w-4 h-4 text-[var(--text-muted)]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/>
                    </svg>
                    <span>Family dashboard</span>
                </a>
            @endif

            {{-- 3. Settings --}}
            <a href="{{ $settingsRoute }}"
               @click="closeMenu()"
               role="menuitem"
               tabindex="-1"
               class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-medium text-[var(--text-primary)] hover:bg-[var(--bg-subtle)] focus:bg-[var(--bg-subtle)] focus:outline-none transition-colors">
                <svg class="w-4 h-4 text-[var(--text-muted)]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                </svg>
                <span>Settings</span>
            </a>

            {{-- 4. Privacy & cookies --}}
            <a href="{{ $privacyRoute }}"
               @click="closeMenu()"
               role="menuitem"
               tabindex="-1"
               class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-medium text-[var(--text-primary)] hover:bg-[var(--bg-subtle)] focus:bg-[var(--bg-subtle)] focus:outline-none transition-colors">
                <svg class="w-4 h-4 text-[var(--text-muted)]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>
                </svg>
                <span>Privacy &amp; cookies</span>
            </a>

            {{-- 5. If admin, show Admin above Help --}}
            @if($isAdmin)
                <a href="{{ $adminRoute }}"
                   @click="closeMenu()"
                   role="menuitem"
                   tabindex="-1"
                   class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-medium text-[var(--text-primary)] hover:bg-[var(--bg-subtle)] focus:bg-[var(--bg-subtle)] focus:outline-none transition-colors">
                    <svg class="w-4 h-4 text-[var(--text-muted)]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"/>
                    </svg>
                    <span>Admin</span>
                </a>
            @endif

            {{-- 6. Membership (Pro) --}}
            <a href="{{ $membershipRoute }}"
               @click="closeMenu()"
               role="menuitem"
               tabindex="-1"
               class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-medium text-[var(--text-primary)] hover:bg-[var(--bg-subtle)] focus:bg-[var(--bg-subtle)] focus:outline-none transition-colors">
                <svg class="w-4 h-4 text-[var(--secondary-primary)]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z"/>
                </svg>
                <span>Membership (Pro)</span>
            </a>

            {{-- 7. Help --}}
            <a href="{{ $helpRoute }}"
               @click="closeMenu()"
               role="menuitem"
               tabindex="-1"
               class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-medium text-[var(--text-primary)] hover:bg-[var(--bg-subtle)] focus:bg-[var(--bg-subtle)] focus:outline-none transition-colors">
                <svg class="w-4 h-4 text-[var(--text-muted)]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z"/>
                </svg>
                <span>Help</span>
            </a>

            {{-- Divider --}}
            <hr class="my-1 border-[var(--border-subtle)]">

            {{-- 9. Log out in neutral color (not red!), with an icon --}}
            <form action="{{ $logoutRoute }}" method="POST" class="m-0 p-0">
                @csrf
                <button type="submit"
                        role="menuitem"
                        tabindex="-1"
                        class="w-full flex items-center gap-2.5 px-3.5 py-2 text-xs font-medium text-[var(--text-secondary)] hover:text-[var(--text-primary)] hover:bg-[var(--bg-subtle)] focus:bg-[var(--bg-subtle)] focus:text-[var(--text-primary)] focus:outline-none transition-colors cursor-pointer text-left">
                    <svg class="w-4 h-4 text-[var(--text-muted)]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/>
                    </svg>
                    <span>Log out</span>
                </button>
            </form>
        </div>
    </div>

    {{-- MOBILE BOTTOM SHEET (Avatar menu opens as mobile bottom sheet with logout at bottom) --}}
    <div x-show="mobileOpen"
         x-cloak
         class="sm:hidden fixed inset-0 z-50 flex flex-col justify-end">

        {{-- Backdrop --}}
        <div x-show="mobileOpen"
             x-transition:enter="transition-opacity ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="mobileOpen = false"
             class="fixed inset-0 bg-black/60 backdrop-blur-xs"></div>

        {{-- Sheet Container --}}
        <div x-show="mobileOpen"
             x-transition:enter="transition ease-out duration-250 transform"
             x-transition:enter-start="translate-y-full"
             x-transition:enter-end="translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-y-0"
             x-transition:leave-end="translate-y-full"
             class="relative w-full bg-[var(--bg-surface)] rounded-t-2xl shadow-2xl border-t border-[var(--border-default)] p-4 text-[var(--text-primary)] z-10 max-h-[85vh] overflow-y-auto">

            {{-- Handle Bar --}}
            <div class="w-12 h-1 bg-[var(--border-strong)] rounded-full mx-auto mb-3"></div>

            {{-- User Header --}}
            <div class="flex items-center gap-3 pb-3 border-b border-[var(--border-subtle)]">
                <div class="w-10 h-10 rounded-full bg-[var(--accent-primary)] text-[var(--accent-contrast,#ffffff)] font-medium text-sm flex items-center justify-center overflow-hidden border border-[var(--border-subtle)] shrink-0">
                    @if($avatarUrl && !str_contains($avatarUrl, 'unsplash'))
                        <img src="{{ $avatarUrl }}" alt="{{ $u->name }}" class="w-full h-full object-cover">
                    @else
                        <span>{{ $initials }}</span>
                    @endif
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-[var(--text-primary)] truncate">{{ $u->name }}</p>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold leading-none {{ $plan === 'Pro' ? 'bg-[var(--accent-subtle)] text-[var(--accent-primary)] border border-[var(--accent-border)]' : 'bg-[var(--bg-muted)] text-[var(--text-secondary)] border border-[var(--border-default)]' }}">
                            {{ $plan }}
                        </span>
                        @if($isVerified)
                            <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded text-[10px] font-semibold leading-none bg-emerald-500/10 text-emerald-700 dark:text-emerald-300 border border-emerald-500/20">
                                <svg class="w-3 h-3 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                </svg>
                                <span>Verified</span>
                            </span>
                        @endif
                    </div>
                </div>
                <button type="button"
                        @click="mobileOpen = false"
                        class="p-2 text-[var(--text-secondary)] hover:text-[var(--text-primary)]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Menu Items List --}}
            <div class="py-2 space-y-1">
                <a href="{{ $profileRoute }}"
                   @click="mobileOpen = false"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-[var(--text-primary)] hover:bg-[var(--bg-subtle)]">
                    <svg class="w-5 h-5 text-[var(--text-muted)]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21c-2.676 0-5.216-.584-7.499-1.632Z"/>
                    </svg>
                    <span>My profile</span>
                </a>

                @if($isGuardian)
                    <a href="{{ $familyRoute }}"
                       @click="mobileOpen = false"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-[var(--text-primary)] hover:bg-[var(--bg-subtle)]">
                        <svg class="w-5 h-5 text-[var(--text-muted)]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/>
                        </svg>
                        <span>Family dashboard</span>
                    </a>
                @endif

                <a href="{{ $settingsRoute }}"
                   @click="mobileOpen = false"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-[var(--text-primary)] hover:bg-[var(--bg-subtle)]">
                    <svg class="w-5 h-5 text-[var(--text-muted)]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    </svg>
                    <span>Settings</span>
                </a>

                <a href="{{ $privacyRoute }}"
                   @click="mobileOpen = false"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-[var(--text-primary)] hover:bg-[var(--bg-subtle)]">
                    <svg class="w-5 h-5 text-[var(--text-muted)]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>
                    </svg>
                    <span>Privacy &amp; cookies</span>
                </a>

                @if($isAdmin)
                    <a href="{{ $adminRoute }}"
                       @click="mobileOpen = false"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-[var(--text-primary)] hover:bg-[var(--bg-subtle)]">
                        <svg class="w-5 h-5 text-[var(--text-muted)]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"/>
                        </svg>
                        <span>Admin</span>
                    </a>
                @endif

                <a href="{{ $membershipRoute }}"
                   @click="mobileOpen = false"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-[var(--text-primary)] hover:bg-[var(--bg-subtle)]">
                    <svg class="w-5 h-5 text-[var(--secondary-primary)]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z"/>
                    </svg>
                    <span>Membership (Pro)</span>
                </a>

                <a href="{{ $helpRoute }}"
                   @click="mobileOpen = false"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-[var(--text-primary)] hover:bg-[var(--bg-subtle)]">
                    <svg class="w-5 h-5 text-[var(--text-muted)]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z"/>
                    </svg>
                    <span>Help</span>
                </a>
            </div>

            {{-- Divider & Logout at Bottom --}}
            <div class="pt-2 border-t border-[var(--border-subtle)] mt-2">
                <form action="{{ $logoutRoute }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2.5 px-4 py-3 rounded-xl bg-[var(--bg-subtle)] text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition cursor-pointer">
                        <svg class="w-5 h-5 text-[var(--text-muted)]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/>
                        </svg>
                        <span>Log out</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

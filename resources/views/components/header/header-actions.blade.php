@props([
    'showThemeToggle' => true,
    'user' => null,
])

@php
    $u = $user ?? Auth::user();
@endphp

<div class="flex items-center gap-2 shrink-0">
    {{-- 1. Messages icon (chat bubble) with unread count badge --}}
    {{-- Visible on desktop; on mobile, Messages is placed in the mobile bottom tab bar --}}
    <div class="hidden sm:block">
        <x-header.message-button />
    </div>

    {{-- 2. Notifications icon (bell) with unread count badge --}}
    <x-header.notification-bell />

    {{-- 3. Theme toggle (sun/moon), icon-only, only if the site already has it --}}
    @if($showThemeToggle)
        <button type="button"
                @click="
                    const isDark = document.documentElement.classList.contains('dark');
                    const next = isDark ? 'light' : 'dark';
                    if (next === 'dark') {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                    try { localStorage.setItem('theme', next); } catch(e){}
                    window.dispatchEvent(new CustomEvent('theme-changed', { detail: { theme: next } }));
                "
                class="hidden sm:flex group relative w-11 h-11 items-center justify-center rounded-full text-[var(--text-secondary)] hover:text-[var(--text-primary)] hover:bg-[var(--text-primary)]/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[var(--accent-primary)] focus-visible:ring-offset-2 transition-colors cursor-pointer"
                title="Toggle light and dark mode"
                aria-label="Toggle light and dark mode">

            {{-- Sun icon (shown in dark mode) --}}
            <svg class="w-[22px] h-[22px] hidden dark:block text-[var(--secondary-primary)]"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="1.5"
                 stroke-linecap="round"
                 stroke-linejoin="round"
                 viewBox="0 0 24 24"
                 aria-hidden="true">
                <circle cx="12" cy="12" r="4"/>
                <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/>
            </svg>

            {{-- Moon icon (shown in light mode) --}}
            <svg class="w-[22px] h-[22px] block dark:hidden text-[var(--text-secondary)]"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="1.5"
                 stroke-linecap="round"
                 stroke-linejoin="round"
                 viewBox="0 0 24 24"
                 aria-hidden="true">
                <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/>
            </svg>
        </button>
    @endif

    {{-- 4. Avatar button (user photo or initials) with small chevron, opening Account Menu --}}
    <x-header.account-menu :user="$u" />
</div>

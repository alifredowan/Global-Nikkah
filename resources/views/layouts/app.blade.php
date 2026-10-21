<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Global Nikkah - Muslim Matrimonial Platform')</title>

    <!-- Theme Initialization (Prevent FOUC: 4 Moods Supported) -->
    <script>
        (function() {
            const savedMood = localStorage.getItem('nikah_theme') || (localStorage.getItem('theme') === 'dark' ? 'dark' : 'light');
            document.documentElement.classList.remove('dark', 'theme-emerald', 'theme-sand');
            if (savedMood === 'dark') {
                document.documentElement.classList.add('dark');
            } else if (savedMood === 'emerald') {
                document.documentElement.classList.add('dark', 'theme-emerald');
            } else if (savedMood === 'sand') {
                document.documentElement.classList.add('theme-sand');
            }
        })();
    </script>

    <!-- Google Fonts: Outfit & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        h1, h2, h3, h4, .font-heading { font-family: 'Outfit', sans-serif; }
        .bg-emerald-gradient {
            background: linear-gradient(135deg, #064e3b 0%, #065f46 60%, #047857 100%);
        }
        .bg-gold-gradient {
            background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);
        }
        .glass-header {
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(12px);
        }
        .dark .glass-header {
            background: rgba(15, 23, 42, 0.92);
        }
        .photo-blur {
            filter: blur(14px);
            transition: filter 0.3s ease;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 antialiased selection:bg-emerald-500 selection:text-white transition-colors duration-200"
      x-data="{
          sidebarOpen: (function() {
              try { localStorage.removeItem('sidebar_open'); } catch(e){}
              return localStorage.getItem('sidebar_expanded') === 'true';
          })(),
          mobileSidebarOpen: false,
          commandPaletteOpen: false,
          toggleSidebar() {
              if (window.innerWidth >= 1024) {
                  this.sidebarOpen = !this.sidebarOpen;
                  localStorage.setItem('sidebar_expanded', this.sidebarOpen);
              } else {
                  this.mobileSidebarOpen = !this.mobileSidebarOpen;
              }
          }
      }"
      @keydown.window.ctrl.k.prevent="commandPaletteOpen = true"
      @keydown.window.cmd.k.prevent="commandPaletteOpen = true"
      @open-command-palette.window="commandPaletteOpen = true">

    @auth
        {{-- ========================================================= --}}
        {{-- AUTHENTICATED USER: FULL SIDEBAR LAYOUT (NO TOP TABS)    --}}
        {{-- ========================================================= --}}
        <div class="min-h-full flex">

            <!-- Mobile Off-Canvas Sidebar Backdrop & Drawer -->
            <div x-show="mobileSidebarOpen"
                 x-cloak
                 class="fixed inset-0 z-50 lg:hidden flex"
                 role="dialog"
                 aria-modal="true">
                <div x-show="mobileSidebarOpen"
                     x-transition:enter="transition-opacity ease-linear duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition-opacity ease-linear duration-300"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="mobileSidebarOpen = false"
                     class="fixed inset-0 bg-slate-950/80 backdrop-blur-xs"></div>

                <div x-show="mobileSidebarOpen"
                     x-transition:enter="transition ease-in-out duration-300 transform"
                     x-transition:enter-start="-translate-x-full"
                     x-transition:enter-end="translate-x-0"
                     x-transition:leave="transition ease-in-out duration-300 transform"
                     x-transition:leave-start="translate-x-0"
                     x-transition:leave-end="-translate-x-full"
                     class="relative mr-16 flex-1 w-full max-w-xs bg-slate-900 border-r border-slate-800 text-slate-200 flex flex-col py-6 px-4">
                    
                    <!-- Close Button -->
                    <div class="absolute top-4 right-4">
                        <button type="button"
                                @click="mobileSidebarOpen = false"
                                class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Brand in Mobile Drawer (Clicking closes drawer) -->
                    <div @click="mobileSidebarOpen = false" class="flex items-center gap-3 px-2 mb-6 cursor-pointer select-none" title="Close Drawer">
                        <div class="w-10 h-10 rounded-xl bg-emerald-gradient flex items-center justify-center text-white shadow-md shadow-emerald-950/50">
                            <svg class="w-6 h-6 text-amber-300" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="font-heading font-extrabold text-lg text-white tracking-tight flex items-center gap-1">
                                Nikah<span class="text-amber-400">Connect</span>
                            </span>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-400 block -mt-0.5">
                                {{ Auth::user()->isSuperAdmin() ? 'Super Admin' : (Auth::user()->isModerator() ? 'Staff' : (Auth::user()->isWali() ? 'Guardian (Wali)' : 'Seeker')) }}
                            </span>
                        </div>
                    </div>

                    <!-- Role-Based Nav Links -->
                    <nav class="flex-1 space-y-1 overflow-y-auto pr-1">
                        @include('layouts.partials.sidebar-nav')
                    </nav>

                    <!-- Mobile User Card & Logout -->
                    <div class="pt-4 mt-4 border-t border-slate-800">
                        <div class="flex items-center gap-3 px-2 py-2">
                            <div class="w-9 h-9 rounded-full bg-emerald-900 border border-emerald-600 text-emerald-200 font-bold flex items-center justify-center text-sm">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name }}</p>
                                <p class="text-[10px] text-emerald-400 font-medium capitalize">{{ Auth::user()->role }}</p>
                            </div>
                        </div>
                        <form action="{{ route('logout') }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-xs font-semibold text-rose-400 hover:bg-rose-950/40 rounded-xl transition">
                                <span>🚪</span> Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Desktop Collapsible Sidebar -->
            <aside x-show="sidebarOpen"
                   x-cloak
                   x-transition:enter="transition-all ease-in-out duration-300"
                   x-transition:enter-start="opacity-0 -translate-x-full"
                   x-transition:enter-end="opacity-100 translate-x-0"
                   x-transition:leave="transition-all ease-in-out duration-300"
                   x-transition:leave-start="opacity-100 translate-x-0"
                   x-transition:leave-end="opacity-0 -translate-x-full"
                   class="hidden lg:flex lg:flex-col lg:w-64 bg-slate-900 text-slate-300 border-r border-slate-800 shrink-0 sticky top-0 h-screen z-30 transition-all duration-300">
                <!-- Sidebar Header -->
                <div class="p-4 border-b border-slate-800/80 flex items-center justify-between gap-2">
                    <button type="button"
                            @click="toggleSidebar()"
                            class="flex items-center gap-3 group flex-1 min-w-0 text-left cursor-pointer focus:outline-none"
                            title="Open / Close Sidebar (Global Nikkah Logo)">
                        <div class="w-10 h-10 rounded-xl bg-emerald-gradient flex items-center justify-center text-white shadow-lg shadow-emerald-950/50 group-hover:scale-105 transition transform">
                            <svg class="w-6 h-6 text-amber-300" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-heading font-extrabold text-base text-white tracking-tight flex items-center gap-1 group-hover:text-emerald-400 transition">
                                Global<span class="text-amber-400">Nikkah</span>
                            </div>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-400">
                                    @if(Auth::user()->isSuperAdmin())
                                        Super Admin
                                    @elseif(Auth::user()->isModerator())
                                        Staff Admin
                                    @elseif(Auth::user()->isWali())
                                        Guardian (Wali)
                                    @else
                                        {{ Auth::user()->gender === 'female' ? 'Sister Portal' : 'Brother Portal' }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </button>
                </div>

                <!-- Navigation Links -->
                <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                    @include('layouts.partials.sidebar-nav')
                </nav>

                <!-- Sidebar Footer User Card with Integrated Arrow Logout -->
                <div class="p-4 border-t border-slate-800/80 bg-slate-950/40">
                    <div class="flex items-center justify-between gap-2 p-2.5 rounded-2xl bg-slate-800/50 border border-slate-700/60">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-8 h-8 rounded-full bg-emerald-900 border border-emerald-500 text-emerald-200 font-bold flex items-center justify-center text-xs shrink-0">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name }}</p>
                                <p class="text-[10px] text-slate-400 truncate">{{ Auth::user()->email }}</p>
                                <span class="inline-flex items-center gap-1 text-[9px] font-bold text-emerald-400 uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                    {{ Auth::user()->isSuperAdmin() ? 'Super Admin' : (Auth::user()->isModerator() ? 'Staff' : (Auth::user()->isWali() ? 'Wali' : 'Seeker')) }}
                                </span>
                            </div>
                        </div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" title="Sign Out" class="p-2 text-slate-400 hover:text-rose-400 hover:bg-slate-700/60 rounded-xl transition cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <!-- Main Canvas (Top Header with ZERO TABS + Content) -->
            <div class="flex-1 flex flex-col min-w-0">

                <!-- Clean Top Navigation Header (NO TABS) -->
                <header class="sticky top-0 z-40 h-16 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-slate-200/80 dark:border-slate-800 flex items-center justify-between px-4 sm:px-6 lg:px-8">
                    <!-- Left: Single 3-Dots Button AND NikahConnect Logo (Both trigger open/close) -->
                    <div class="flex items-center gap-3">
                        <!-- 3-Dots Toggle Button (The ONE 3-Dots Button) -->
                        <button type="button"
                                @click="toggleSidebar()"
                                class="p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition focus:outline-none cursor-pointer flex items-center justify-center shrink-0"
                                aria-label="Toggle Sidebar Menu"
                                title="Open / Close Sidebar (3 Dots)">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <circle cx="5" cy="12" r="2.25"/>
                                <circle cx="12" cy="12" r="2.25"/>
                                <circle cx="19" cy="12" r="2.25"/>
                            </svg>
                        </button>

                        <!-- Global Nikkah Brand Logo & Name (Shown when sidebar is collapsed, or on mobile) -->
                        <div @click="toggleSidebar()"
                             x-show="!sidebarOpen || window.innerWidth < 1024"
                             class="flex items-center gap-2.5 cursor-pointer select-none group"
                             title="Open / Close Sidebar (Global Nikkah Logo)">
                            <div class="w-9 h-9 rounded-xl bg-emerald-gradient flex items-center justify-center text-white shadow-md shadow-emerald-950/20 group-hover:scale-105 transition transform">
                                <svg class="w-5 h-5 text-amber-300" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
                                </svg>
                            </div>
                            <span class="font-heading font-extrabold text-base tracking-tight text-slate-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition">
                                Global<span class="text-amber-500">Nikkah</span>
                            </span>
                        </div>
                    </div>

                    <!-- Center: Quick Command Search Trigger (Standard SaaS Benchmark) -->
                    <div class="hidden md:flex flex-1 max-w-sm mx-4">
                        <button type="button"
                                @click="commandPaletteOpen = true"
                                class="w-full flex items-center justify-between gap-3 px-3.5 py-1.5 rounded-xl border border-slate-200/90 dark:border-slate-800 bg-slate-100/60 dark:bg-slate-800/40 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:border-slate-300 dark:hover:border-slate-700 transition cursor-pointer text-xs group"
                                title="Quick Command Palette & Search (Ctrl + K)">
                            <div class="flex items-center gap-2 min-w-0">
                                <svg class="w-4 h-4 text-emerald-500 shrink-0 group-hover:scale-110 transition transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <span class="font-medium text-slate-500 dark:text-slate-400 truncate">Quick search & navigation...</span>
                            </div>
                            <div class="flex items-center gap-1 font-mono text-[10px] bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 rounded-md px-1.5 py-0.5 shadow-xs shrink-0">
                                <kbd>Ctrl</kbd>+<kbd>K</kbd>
                            </div>
                        </button>
                    </div>

                    <!-- Right Controls: Status Pill, 4-Mood Switcher, Reverb Bell, Profile Dropdown -->
                    <div class="flex items-center gap-2 sm:gap-3">
                        <!-- Operational System Live Status Pill -->
                        <div class="hidden xl:flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200/80 dark:border-emerald-800/60 text-[11px] font-semibold text-emerald-700 dark:text-emerald-300">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>Live Operational</span>
                        </div>

                        <!-- 4-Mood Visual Theme Picker Dropdown -->
                        <div class="relative" x-data="{
                            moodOpen: false,
                            currentMood: localStorage.getItem('nikah_theme') || (localStorage.getItem('theme') === 'dark' ? 'dark' : 'light'),
                            moods: [
                                { id: 'light', name: 'Daylight', icon: '☀️', desc: 'Crisp daylight & emerald', dot: 'bg-emerald-400 border-amber-300' },
                                { id: 'dark', name: 'Midnight', icon: '🌙', desc: 'Deep slate dark mode', dot: 'bg-slate-900 border-slate-700' },
                                { id: 'emerald', name: 'Royal Emerald', icon: '💎', desc: 'Deep Islamic forest & gold', dot: 'bg-emerald-800 border-amber-400' },
                                { id: 'sand', name: 'Desert Sand', icon: '🏜️', desc: 'Warm sand, coffee & cream', dot: 'bg-amber-100 border-amber-600' }
                            ],
                            selectMood(mood) {
                                this.currentMood = mood;
                                localStorage.setItem('nikah_theme', mood);
                                localStorage.setItem('theme', (mood === 'light' || mood === 'sand') ? 'light' : 'dark');
                                document.documentElement.classList.remove('dark', 'theme-emerald', 'theme-sand');
                                if (mood === 'dark') {
                                    document.documentElement.classList.add('dark');
                                } else if (mood === 'emerald') {
                                    document.documentElement.classList.add('dark', 'theme-emerald');
                                } else if (mood === 'sand') {
                                    document.documentElement.classList.add('theme-sand');
                                }
                                this.moodOpen = false;
                                window.dispatchEvent(new CustomEvent('nikah-mood-changed', { detail: { mood } }));
                            },
                            get activeMood() {
                                return this.moods.find(m => m.id === this.currentMood) || this.moods[0];
                            }
                        }">
                            <button type="button"
                                    id="theme-toggle"
                                    @click="moodOpen = !moodOpen"
                                    @click.outside="moodOpen = false"
                                    class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-100/70 dark:bg-slate-800/70 hover:bg-slate-200/80 dark:hover:bg-slate-700/80 text-xs font-semibold text-slate-700 dark:text-slate-200 transition focus:outline-none"
                                    title="Switch Visual Mood (4 Themes Available)"
                                    aria-label="Switch Theme Mood">
                                <span x-text="activeMood.icon" class="text-sm"></span>
                                <span x-text="activeMood.name" class="hidden sm:inline text-xs font-bold text-slate-700 dark:text-slate-200"></span>
                                <svg class="w-3.5 h-3.5 text-slate-400 transition transform" :class="moodOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <!-- Dropdown Menu of 4 Moods -->
                            <div x-show="moodOpen"
                                 x-cloak
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-64 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-2xl p-2 z-50">
                                <div class="px-3 py-2 border-b border-slate-100 dark:border-slate-800 mb-1 flex items-center justify-between">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Visual Mood (4 Available)</span>
                                    <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400">Halal Aesthetics</span>
                                </div>
                                <div class="space-y-1">
                                    <template x-for="mood in moods" :key="mood.id">
                                        <button type="button"
                                                @click="selectMood(mood.id)"
                                                class="w-full flex items-center justify-between p-2 rounded-xl text-left transition cursor-pointer"
                                                :class="currentMood === mood.id ? 'bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-300 dark:border-emerald-700' : 'hover:bg-slate-100 dark:hover:bg-slate-800 border border-transparent'">
                                            <div class="flex items-center gap-2.5">
                                                <span x-text="mood.icon" class="text-base"></span>
                                                <div>
                                                    <p class="text-xs font-bold text-slate-900 dark:text-white" x-text="mood.name"></p>
                                                    <p class="text-[10px] text-slate-500 dark:text-slate-400" x-text="mood.desc"></p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-1.5">
                                                <span class="w-3 h-3 rounded-full border shadow-xs" :class="mood.dot"></span>
                                                <svg x-show="currentMood === mood.id" class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Real-time Notification Bell -->
                        <div class="relative" x-data="{
                            notifOpen: false,
                            unreadCount: {{ Auth::user()->unreadNotifications->count() }},
                            notifications: [],
                            loading: false,
                            fetchNotifications() {
                                this.loading = true;
                                fetch('{{ route('notifications.index') }}', {
                                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                                })
                                .then(res => res.json())
                                .then(data => {
                                    this.notifications = data.notifications;
                                    this.unreadCount = data.unread_count;
                                    this.loading = false;
                                })
                                .catch(() => { this.loading = false; });
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
                                    this.unreadCount = data.unread_count;
                                    const n = this.notifications.find(item => item.id === id);
                                    if (n) n.read_at = new Date().toISOString();
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
                                });
                            }
                        }"
                        x-init="fetchNotifications()"
                        @new-notification-received.window="unreadCount++; notifications.unshift($event.detail); if(notifications.length > 20) notifications.pop();">
                            <button type="button"
                                    @click="notifOpen = !notifOpen; if(notifOpen) { fetchNotifications(); if(unreadCount > 0) markAllRead(); }"
                                    id="notification-bell-btn"
                                    aria-label="Notifications"
                                    title="Real-Time Notifications"
                                    class="relative p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-800 transition focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                <span x-show="unreadCount > 0"
                                      x-text="unreadCount > 99 ? '99+' : unreadCount"
                                      x-cloak
                                      class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 bg-amber-500 text-white font-extrabold text-[10px] rounded-full flex items-center justify-center ring-2 ring-white dark:ring-slate-900 animate-pulse">
                                </span>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="notifOpen"
                                 @click.away="notifOpen = false"
                                 x-cloak
                                 class="absolute right-0 mt-2 w-80 sm:w-96 bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 py-3 z-50 animate-in fade-in slide-in-from-top-2">
                                
                                <div class="px-4 py-2 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-xs font-bold font-heading text-slate-900 dark:text-white uppercase tracking-wider">
                                            Notifications
                                        </h3>
                                        <span x-show="unreadCount > 0" x-text="unreadCount + ' new'" class="text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300"></span>
                                    </div>
                                    <button type="button"
                                            x-show="unreadCount > 0"
                                            @click="markAllRead()"
                                            class="text-[11px] font-semibold text-emerald-600 dark:text-emerald-400 hover:underline">
                                        Mark all read
                                    </button>
                                </div>

                                <div class="max-h-80 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-800/60">
                                    <div x-show="loading" class="p-6 text-center text-xs text-slate-400">
                                        Loading notifications...
                                    </div>

                                    <template x-for="item in notifications" :key="item.id">
                                        <div class="p-3 sm:p-3.5 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition flex items-start gap-3"
                                             :class="{ 'bg-emerald-50/40 dark:bg-emerald-950/20': !item.read_at }">
                                            <div class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-base shrink-0"
                                                 x-text="item.data.icon || '🔔'">
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between gap-1">
                                                    <h4 class="text-xs font-bold text-slate-900 dark:text-white truncate" x-text="item.data.title || 'Notification'"></h4>
                                                    <span class="text-[10px] text-slate-400 dark:text-slate-500 whitespace-nowrap" x-text="item.created_at"></span>
                                                </div>
                                                <p class="text-[11px] text-slate-600 dark:text-slate-300 mt-0.5 leading-snug line-clamp-2" x-text="item.data.message"></p>
                                                <template x-if="item.data.action_url">
                                                    <a :href="item.data.action_url" class="inline-block mt-1 text-[11px] font-bold text-emerald-600 dark:text-emerald-400 hover:underline">
                                                        Open &rarr;
                                                    </a>
                                                </template>
                                            </div>
                                        </div>
                                    </template>

                                    <div x-show="!loading && notifications.length === 0" class="py-8 text-center text-xs text-slate-400 dark:text-slate-500">
                                        No notifications yet
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Constant Super Admin / User Badge (No Dropdown, Constant Display) -->
                        <div class="flex items-center gap-2.5 px-3 py-1.5 rounded-2xl bg-slate-100/80 dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-700/80 select-none"
                             title="{{ Auth::user()->name }} ({{ Auth::user()->email }})">
                            <div class="w-8 h-8 rounded-full bg-emerald-600 text-white font-bold flex items-center justify-center text-xs shadow-xs">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="text-left hidden sm:block">
                                <div class="text-xs font-bold text-slate-900 dark:text-white leading-tight">
                                    {{ Auth::user()->name }}
                                </div>
                                <div class="text-[10px] font-semibold text-emerald-600 dark:text-emerald-400 leading-tight flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    {{ Auth::user()->isSuperAdmin() ? 'Super Admin' : (Auth::user()->isModerator() ? 'Staff Admin' : (Auth::user()->isWali() ? 'Guardian (Wali)' : 'Seeker')) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Nikah Milestone Banner if pending -->
                @if(Auth::user()->pendingMarriage())
                    @php $pendingM = Auth::user()->pendingMarriage(); @endphp
                    <div class="bg-gradient-to-r from-amber-500/20 via-emerald-500/15 to-amber-500/20 border-b border-amber-300 dark:border-amber-700/60 px-4 py-3 text-slate-900 dark:text-slate-100 text-xs font-semibold">
                        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <span class="text-base animate-bounce">💍</span>
                                <span>
                                    @if(Auth::id() !== $pendingM->initiated_by_user_id)
                                        <strong>Nikah Declaration Pending:</strong> {{ $pendingM->initiator->name }} declared you have completed your Nikah!
                                    @else
                                        <strong>Nikah Declaration Submitted:</strong> Waiting for spouse confirmation.
                                    @endif
                                </span>
                            </div>
                            <a href="{{ route('marriages.celebration') }}" class="px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold text-xs shadow-xs transition shrink-0">
                                View Milestone &rarr;
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Flash Alerts -->
                <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    @if(session('success'))
                        <div class="bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 px-4 py-3 rounded-2xl text-xs font-semibold flex items-center justify-between shadow-xs mb-4">
                            <div class="flex items-center gap-2">
                                <span class="text-base">✓</span>
                                <span>{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-200 px-4 py-3 rounded-2xl text-xs font-semibold flex items-center justify-between shadow-xs mb-4">
                            <div class="flex items-center gap-2">
                                <span class="text-base">⚠️</span>
                                <span>{{ session('warning') }}</span>
                            </div>
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="bg-sky-50 dark:bg-sky-950/60 border border-sky-200 dark:border-sky-800 text-sky-800 dark:text-sky-200 px-4 py-3 rounded-2xl text-xs font-semibold flex items-center justify-between shadow-xs mb-4">
                            <div class="flex items-center gap-2">
                                <span class="text-base">ℹ️</span>
                                <span>{{ session('info') }}</span>
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 p-4 rounded-2xl text-xs shadow-xs mb-4">
                            <p class="font-bold mb-1">Please correct the following:</p>
                            <ul class="list-disc list-inside space-y-0.5">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <!-- Main Content Area -->
                <main class="flex-1 pb-16">
                    @yield('content')
                </main>

                <!-- Unified Platform Footer -->
                @include('layouts.partials.footer')
            </div>
        </div>

    @else
        {{-- ========================================================= --}}
        {{-- GUEST VISITOR: CLEAN PUBLIC HEADER & FULL-WIDTH CANVAS    --}}
        {{-- ========================================================= --}}
        <div class="min-h-full flex flex-col">
            <!-- Public Header -->
            <header class="sticky top-0 z-40 border-b border-slate-200/80 dark:border-slate-800 glass-header shadow-xs">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16">
                        <!-- Brand Logo -->
                        <div class="flex items-center gap-3">
                            <a href="{{ route('landing') }}" class="flex items-center gap-2.5 group">
                                <div class="w-10 h-10 rounded-xl bg-emerald-gradient flex items-center justify-center text-white shadow-md shadow-emerald-900/20 group-hover:scale-105 transition transform">
                                    <svg class="w-6 h-6 text-amber-300" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="font-heading font-extrabold text-xl tracking-tight text-emerald-950 dark:text-emerald-100 flex items-center gap-1.5">
                                        Global<span class="text-amber-500">Nikkah</span>
                                    </span>
                                    <span class="text-[10px] uppercase font-bold tracking-widest text-emerald-700 dark:text-emerald-400 block -mt-1">Halal Matrimonial</span>
                                </div>
                            </a>
                        </div>

                        <!-- Public Controls -->
                        <div class="flex items-center gap-3">
                            <a href="{{ route('how-it-works') }}"
                               class="hidden sm:inline-flex items-center gap-1.5 text-xs font-bold text-slate-700 dark:text-slate-200 hover:text-emerald-700 dark:hover:text-emerald-400 px-2 py-2 transition {{ request()->routeIs('how-it-works') ? 'text-emerald-700 dark:text-emerald-400' : '' }}">
                                <span class="text-emerald-500">✦</span>
                                How it works
                            </a>
                            <button type="button"
                                    id="theme-toggle"
                                    aria-label="Toggle Dark / Light Mode"
                                    class="p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                                <svg id="theme-toggle-light-icon" class="hidden w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
                                </svg>
                                <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5 text-slate-600 dark:text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                                </svg>
                            </button>

                            <a href="{{ route('login') }}" class="text-xs font-bold text-slate-700 dark:text-slate-200 hover:text-emerald-700 dark:hover:text-emerald-400 px-3 py-2">
                                Log in
                            </a>
                            <a href="{{ route('register') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-md shadow-emerald-900/10 transition">
                                Create Profile
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1">
                @yield('content')
            </main>

            <!-- Unified Platform Footer -->
            @include('layouts.partials.footer')
        </div>
    @endauth

    @auth
        <!-- ========================================================= -->
        <!-- GLOBAL COMMAND PALETTE (CTRL + K / CMD + K)               -->
        <!-- ========================================================= -->
        <div x-show="commandPaletteOpen"
             x-cloak
             x-transition:enter="transition-opacity ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto p-4 sm:p-6 md:p-20 flex items-start justify-center"
             role="dialog"
             aria-modal="true"
             @keydown.escape.window="commandPaletteOpen = false">

            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs transition-opacity"
                 @click="commandPaletteOpen = false"></div>

            <!-- Spotlight Dialog -->
            <div x-show="commandPaletteOpen"
                 x-transition:enter="transition ease-out duration-200 transform"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150 transform"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative w-full max-w-xl bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden z-10"
                 x-data="{
                     search: '',
                     items: [
                         @if(Auth::user()->isAdmin() || Auth::user()->isModerator())
                             { name: 'Dashboard Overview', cat: 'Admin', icon: '📊', url: '{{ route('admin.dashboard') }}' },
                             { name: 'KYC Queue & Identity Verifications', cat: 'Admin', icon: '🪪', url: '{{ route('admin.verifications') }}' },
                             { name: 'User Safety Reports', cat: 'Admin', icon: '🚩', url: '{{ route('admin.reports') }}' },
                             { name: 'Dynamic Packages & Pricing Tiers', cat: 'Billing', icon: '💳', url: '{{ route('admin.packages.index') }}' },
                             { name: 'Promo Codes & VIP Discounts', cat: 'Billing', icon: '🏷️', url: '{{ route('admin.promo-codes.index') }}' },
                             { name: 'Payment Gateways & PayPal/Stripe Setup', cat: 'Billing', icon: '💰', url: '{{ route('admin.payment-settings') }}' },
                             @if(Auth::user()->isSuperAdmin())
                                 { name: 'Staff Management & Role Permissions', cat: 'Governance', icon: '👥', url: '{{ route('admin.staff') }}' },
                             @endif
                             { name: 'Immutable Audit Logs', cat: 'Governance', icon: '📜', url: '{{ route('admin.audit-logs') }}' },
                             { name: 'Platform Policies & Security Rules', cat: 'Settings', icon: '⚙️', url: '{{ route('admin.settings') }}' },
                         @endif

                         @if(Auth::user()->isSeeker())
                             { name: 'Find Matches / Matrimonial Discovery', cat: 'Matches', icon: '✨', url: '{{ route('discovery.index') }}' },
                             { name: 'Interests & Requests', cat: 'Matches', icon: '💌', url: '{{ route('interests.index') }}' },
                             { name: 'Shortlisted Biodatas', cat: 'Matches', icon: '⭐', url: '{{ route('bookmarks.index') }}' },
                             { name: 'Chaperoned Messages & Chat', cat: 'Chat', icon: '💬', url: '{{ route('chat.index') }}' },
                             { name: 'My Profile & Halal Photos', cat: 'Biodata', icon: '👤', url: '{{ route('profile.show') }}' },
                             { name: 'Edit Deen & Partner Preferences', cat: 'Biodata', icon: '📝', url: '{{ route('profile.edit') }}' },
                             { name: 'Printable Biodata PDF', cat: 'Biodata', icon: '🖨️', url: '{{ route('profile.biodata') }}' },
                             { name: 'Subscription Plans & Upgrades', cat: 'Billing', icon: '💎', url: '{{ route('subscription.pricing') }}' },
                         @endif

                         @if(Auth::user()->isWali())
                             { name: 'Guardian Console & Ward Overview', cat: 'Wali', icon: '🤝', url: '{{ route('wali.dashboard') }}' },
                             { name: 'Link Ward by Matrimonial Code', cat: 'Wali', icon: '🔗', url: '{{ route('wali.link') }}' },
                         @endif

                         { name: 'Account Settings & Security', cat: 'Settings', icon: '🔒', url: '{{ route('settings.index') }}' },
                         { name: 'Switch Mood: Daylight (Noor)', cat: 'Mood', icon: '☀️', action: 'light' },
                         { name: 'Switch Mood: Midnight (Layl)', cat: 'Mood', icon: '🌙', action: 'dark' },
                         { name: 'Switch Mood: Royal Emerald (Zumarad)', cat: 'Mood', icon: '💎', action: 'emerald' },
                         { name: 'Switch Mood: Desert Sand (Sahara)', cat: 'Mood', icon: '🏜️', action: 'sand' },
                     ],
                     get filteredItems() {
                         if (!this.search.trim()) return this.items.slice(0, 8);
                         const q = this.search.toLowerCase();
                         return this.items.filter(i => i.name.toLowerCase().includes(q) || i.cat.toLowerCase().includes(q));
                     },
                     executeItem(item) {
                         if (item.action) {
                             if (window.setNikahMood) window.setNikahMood(item.action);
                             commandPaletteOpen = false;
                         } else if (item.url) {
                             window.location.href = item.url;
                         }
                     }
                 }">

                <!-- Search Header -->
                <div class="relative flex items-center p-4 border-b border-slate-100 dark:border-slate-800">
                    <svg class="w-5 h-5 text-emerald-500 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text"
                           x-model="search"
                           x-ref="searchInput"
                           x-init="$watch('commandPaletteOpen', value => { if (value) setTimeout(() => $refs.searchInput.focus(), 100); })"
                           placeholder="Type a command, page name, or mood (e.g. 'kyc', 'emerald', 'plans')..."
                           class="w-full bg-transparent text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none font-medium">
                    <button type="button"
                            @click="commandPaletteOpen = false"
                            class="text-[10px] font-mono font-bold px-2 py-1 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-slate-900 dark:hover:text-white transition cursor-pointer">
                        ESC
                    </button>
                </div>

                <!-- Results List -->
                <div class="max-h-80 overflow-y-auto p-2 space-y-1">
                    <template x-for="item in filteredItems" :key="item.name">
                        <button type="button"
                                @click="executeItem(item)"
                                class="w-full flex items-center justify-between p-2.5 rounded-2xl hover:bg-emerald-50 dark:hover:bg-slate-800/80 transition text-left group cursor-pointer">
                            <div class="flex items-center gap-3 min-w-0">
                                <span x-text="item.icon" class="text-lg shrink-0"></span>
                                <div class="truncate">
                                    <p class="text-xs font-bold text-slate-800 dark:text-slate-100 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition truncate" x-text="item.name"></p>
                                </div>
                            </div>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 uppercase tracking-wider shrink-0" x-text="item.cat"></span>
                        </button>
                    </template>
                    <div x-show="filteredItems.length === 0" class="py-8 text-center text-xs text-slate-400">
                        No results found for "<span x-text="search" class="font-bold"></span>". Try another search term.
                    </div>
                </div>

                <!-- Footer Hint -->
                <div class="px-4 py-2.5 bg-slate-50 dark:bg-slate-950/50 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-[11px] text-slate-400">
                    <span>Quick Navigation & 4 Moods</span>
                    <span class="flex items-center gap-1 font-mono text-[10px]">
                        Use <kbd class="px-1 py-0.5 bg-white dark:bg-slate-800 rounded border border-slate-200 dark:border-slate-700">ESC</kbd> to exit
                    </span>
                </div>
            </div>
        </div>
    @endauth

    <!-- Reverb Real-Time Toast Notification Container -->
    <div id="reverb-toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col gap-2.5 max-w-sm w-full pointer-events-none px-4 sm:px-0"></div>

    <!-- Theme & Real-Time Broadcasting Scripts -->
    <script>
        // Global 4-Mood Switching Helper Function
        window.setNikahMood = function(mood) {
            localStorage.setItem('nikah_theme', mood);
            localStorage.setItem('theme', (mood === 'light' || mood === 'sand') ? 'light' : 'dark');
            document.documentElement.classList.remove('dark', 'theme-emerald', 'theme-sand');
            if (mood === 'dark') {
                document.documentElement.classList.add('dark');
            } else if (mood === 'emerald') {
                document.documentElement.classList.add('dark', 'theme-emerald');
            } else if (mood === 'sand') {
                document.documentElement.classList.add('theme-sand');
            }
            window.dispatchEvent(new CustomEvent('nikah-mood-changed', { detail: { mood } }));
        };

        // Play gentle notification sound via Web Audio API
        function playHalalNotificationSound() {
            try {
                const AudioContext = window.AudioContext || window.webkitAudioContext;
                if (!AudioContext) return;
                const ctx = new AudioContext();
                const now = ctx.currentTime;
                
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(587.33, now);
                osc.frequency.exponentialRampToValueAtTime(880, now + 0.12);
                
                gain.gain.setValueAtTime(0.12, now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.4);
                
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start(now);
                osc.stop(now + 0.4);
            } catch (e) {
                // Audio autoplay might be restricted by browser policy
            }
        }

        // Show toast notification
        function showHalalNotificationToast(data) {
            const container = document.getElementById('reverb-toast-container');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = 'pointer-events-auto p-4 rounded-2xl bg-white dark:bg-slate-900 border border-emerald-400 dark:border-emerald-700 shadow-2xl flex items-start gap-3 transition-all duration-300 transform translate-y-3 opacity-0';
            
            const icon = data.icon || '🔔';
            const title = data.title || 'New Notification';
            const message = data.message || '';
            const actionUrl = data.action_url || '';

            toast.innerHTML = `
                <div class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg shrink-0">
                    ${icon}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-1.5">
                        <h4 class="font-bold text-xs text-slate-900 dark:text-white truncate">${title}</h4>
                        <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                    </div>
                    <p class="text-[11px] text-slate-600 dark:text-slate-300 mt-0.5 leading-snug line-clamp-2">${message}</p>
                    ${actionUrl ? `<a href="${actionUrl}" class="inline-block text-[11px] font-bold text-emerald-600 dark:text-emerald-400 hover:underline mt-1.5">View details &rarr;</a>` : ''}
                </div>
                <button type="button" aria-label="Close" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-xs font-bold p-1 leading-none">&times;</button>
            `;

            const closeBtn = toast.querySelector('button');
            closeBtn.onclick = () => {
                toast.classList.add('opacity-0', 'translate-y-3');
                setTimeout(() => toast.remove(), 300);
            };

            container.appendChild(toast);

            requestAnimationFrame(() => {
                toast.classList.remove('opacity-0', 'translate-y-3');
            });

            setTimeout(() => {
                if (toast.parentElement) {
                    toast.classList.add('opacity-0', 'translate-y-3');
                    setTimeout(() => toast.remove(), 300);
                }
            }, 8000);
        }

        // Initialize Laravel Reverb Echo Listener
        document.addEventListener('DOMContentLoaded', function() {
            @auth
                if (window.Echo) {
                    const userId = {{ Auth::id() }};
                    const processedNotificationIds = new Set();

                    function handleReverbNotification(notification) {
                        const notifId = notification.id || (notification.data && notification.data.id);
                        if (notifId && processedNotificationIds.has(notifId)) {
                            return;
                        }
                        if (notifId) {
                            processedNotificationIds.add(notifId);
                        }

                        playHalalNotificationSound();
                        showHalalNotificationToast(notification);

                        window.dispatchEvent(new CustomEvent('new-notification-received', {
                            detail: {
                                id: notifId || 'notif-' + Date.now(),
                                data: notification,
                                read_at: null,
                                created_at: 'Just now'
                            }
                        }));
                    }

                    window.Echo.private('App.Models.User.' + userId)
                        .notification(handleReverbNotification);

                    window.Echo.private('user.' + userId)
                        .notification(handleReverbNotification);
                }
            @endauth
        });
    </script>
</body>
</html>

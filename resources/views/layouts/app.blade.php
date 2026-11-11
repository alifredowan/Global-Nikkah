<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Global Nikkah - Muslim Matrimonial Platform')</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <link rel="alternate icon" href="{{ asset('favicon.png') }}" type="image/png">
    <meta name="description" content="@yield('meta_description', 'Global Nikkah is an ethical Muslim matrimonial platform for serious marriage with verified profiles, Wali participation, and privacy-first matchmaking.')">
    <meta name="robots" content="@yield('robots', 'noindex, nofollow')">
    @hasSection('canonical_url')
        <link rel="canonical" href="{{ config('seo.url').(request()->getPathInfo() === '/' ? '/' : request()->getPathInfo()) }}">
        <meta property="og:url" content="{{ config('seo.url').(request()->getPathInfo() === '/' ? '/' : request()->getPathInfo()) }}">
        @php
            $seoSiteUrl = rtrim((string) config('seo.url'), '/');
            $seoSchema = [
                '@context' => 'https://schema.org',
                '@graph' => [
                    [
                        '@type' => 'Organization',
                        '@id' => $seoSiteUrl.'/#organization',
                        'name' => 'Global Nikkah',
                        'url' => $seoSiteUrl.'/',
                        'description' => 'An ethical Muslim matrimonial platform for serious marriage with verified profiles, Wali participation, and privacy-first matchmaking.',
                    ],
                    [
                        '@type' => 'WebSite',
                        '@id' => $seoSiteUrl.'/#website',
                        'name' => 'Global Nikkah',
                        'url' => $seoSiteUrl.'/',
                        'publisher' => ['@id' => $seoSiteUrl.'/#organization'],
                    ],
                ],
            ];
        @endphp
        <script type="application/ld+json">{!! json_encode($seoSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endif
    <meta property="og:title" content="@yield('title', 'Global Nikkah - Muslim Matrimonial Platform')">
    <meta property="og:description" content="@yield('meta_description', 'Global Nikkah is an ethical Muslim matrimonial platform for serious marriage with verified profiles, Wali participation, and privacy-first matchmaking.')">
    <meta property="og:type" content="website">

    <!-- Theme Initialization (Prevent FOUC: Light / Dark Mode) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    <!-- Google Fonts: Cormorant Garamond, Source Sans 3, Inter & Amiri -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400&family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,400;1,600&family=Inter:wght@400;500;600;700&family=Source+Sans+3:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: var(--font-sans); }
        h1, h2, h3, h4, .font-heading, .font-serif { font-family: var(--font-serif); }
        .glass-header {
            background: var(--bg-surface);
            border-bottom: 1px solid var(--border-default);
        }
        .photo-blur {
            filter: blur(14px);
            transition: filter var(--duration-normal) var(--ease-standard);
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full bg-[var(--bg-canvas)] text-[var(--text-primary)] antialiased selection:bg-[var(--accent-primary)] selection:text-white transition-colors duration-200"
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

    @include('layouts.partials.cookie-banner')

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
                        <img src="{{ asset('images/logo.png') }}" alt="Global Nikah Logo" class="w-10 h-10 rounded-xl object-cover shadow-md shadow-emerald-950/50">
                        <div>
                            <span class="font-heading font-extrabold text-lg text-white tracking-tight flex items-center gap-1">
                                Global<span class="text-amber-400">Nikah</span>
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

                    <!-- Mobile User Card -->
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
                        <img src="{{ asset('images/logo.png') }}" alt="Global Nikah Logo" class="w-10 h-10 rounded-xl object-cover shadow-lg shadow-emerald-950/50 group-hover:scale-105 transition transform">
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

                <!-- Sidebar Footer User Card -->
                <div class="p-4 border-t border-slate-800/80 bg-slate-950/40">
                    <div class="flex items-center gap-2.5 p-2.5 rounded-2xl bg-slate-800/50 border border-slate-700/60">
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
                </div>
            </aside>

            <!-- Main Canvas (Top Header with ZERO TABS + Content) -->
            <div class="flex-1 flex flex-col min-w-0">

                <!-- Clean Top Navigation Header -->
                <header class="sticky top-0 z-40 h-14 sm:h-16 bg-[var(--bg-surface)]/95 backdrop-blur-md border-b border-[var(--border-default)] flex items-center justify-between px-4 sm:px-6 lg:px-8 transition-colors">
                    <!-- Left: Sidebar Toggle AND NikahConnect Brand Logo -->
                    <div class="flex items-center gap-3 shrink-0">
                        <!-- Sidebar Toggle Button -->
                        <button type="button"
                                @click="toggleSidebar()"
                                class="p-2 rounded-xl text-[var(--text-secondary)] hover:text-[var(--accent-primary)] hover:bg-[var(--bg-subtle)] transition focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--accent-primary)] cursor-pointer flex items-center justify-center shrink-0"
                                aria-label="Toggle Sidebar Menu"
                                title="Open / Close Sidebar (3 Dots)">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="currentColor" viewBox="0 0 24 24">
                                <circle cx="5" cy="12" r="2.25"/>
                                <circle cx="12" cy="12" r="2.25"/>
                                <circle cx="19" cy="12" r="2.25"/>
                            </svg>
                        </button>

                        <!-- Brand Logo & Name -->
                        <a href="{{ route('discovery.index') }}"
                           class="flex items-center gap-2.5 cursor-pointer select-none group"
                           title="Open / Close Sidebar (Global Nikkah Logo)">
                            <img src="{{ asset('images/logo.png') }}" alt="Global Nikah Logo" class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl object-cover shadow-xs border border-[var(--border-subtle)] group-hover:scale-105 transition transform">
                            <span class="font-heading font-extrabold text-base tracking-tight text-[var(--text-primary)] group-hover:text-[var(--accent-primary)] transition">
                                Global<span class="text-[var(--secondary-primary)]">Nikkah</span>
                            </span>
                        </a>
                    </div>

                    <!-- Center or Left-Next-To-Logo: Main Navigation (Desktop) -->
                    <nav class="hidden lg:flex items-center space-x-1 xl:space-x-2 text-sm font-medium text-[var(--text-secondary)] h-16 ml-4"
                         aria-label="Main Navigation">
                        <a href="{{ route('discovery.index') }}"
                           class="relative h-16 flex items-center px-3.5 transition-colors {{ request()->routeIs('discovery.*') ? 'text-[var(--accent-primary)] font-semibold after:absolute after:bottom-0 after:left-3 after:right-3 after:h-0.5 after:bg-[var(--accent-primary)]' : 'hover:text-[var(--text-primary)] hover:bg-[var(--text-primary)]/5' }}">
                            Discover
                        </a>

                        <a href="{{ route('interests.index') }}"
                           class="relative h-16 flex items-center px-3.5 transition-colors {{ request()->routeIs('interests.*') ? 'text-[var(--accent-primary)] font-semibold after:absolute after:bottom-0 after:left-3 after:right-3 after:h-0.5 after:bg-[var(--accent-primary)]' : 'hover:text-[var(--text-primary)] hover:bg-[var(--text-primary)]/5' }}">
                            Interests
                        </a>

                        <a href="{{ route('bookmarks.index') }}"
                           class="relative h-16 flex items-center px-3.5 transition-colors {{ request()->routeIs('bookmarks.*') ? 'text-[var(--accent-primary)] font-semibold after:absolute after:bottom-0 after:left-3 after:right-3 after:h-0.5 after:bg-[var(--accent-primary)]' : 'hover:text-[var(--text-primary)] hover:bg-[var(--text-primary)]/5' }}">
                            Shortlist
                        </a>
                    </nav>

                    <!-- Quick Command Search Trigger -->
                    <div class="hidden xl:flex flex-1 max-w-xs mx-4">
                        <button type="button"
                                @click="commandPaletteOpen = true"
                                class="w-full flex items-center justify-between gap-3 px-3 py-1.5 rounded-xl border border-[var(--border-default)] bg-[var(--bg-subtle)] text-[var(--text-muted)] hover:text-[var(--text-primary)] hover:border-[var(--border-strong)] transition cursor-pointer text-xs group"
                                title="Quick Command Palette & Search (Ctrl + K)">
                            <div class="flex items-center gap-2 min-w-0">
                                <svg class="w-4 h-4 text-[var(--accent-primary)] shrink-0 group-hover:scale-110 transition transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <span class="font-medium truncate">Quick search...</span>
                            </div>
                            <div class="flex items-center gap-1 font-mono text-[10px] bg-[var(--bg-surface)] border border-[var(--border-default)] text-[var(--text-secondary)] rounded-md px-1.5 py-0.5 shadow-xs shrink-0">
                                <kbd>Ctrl</kbd>+<kbd>K</kbd>
                            </div>
                        </button>
                    </div>

                    <!-- Right Controls: Standardized Header Actions (Messages -> Notifications -> Theme Toggle -> Avatar Account Menu) -->
                    <x-header.header-actions />
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
                <main class="flex-1 pb-20 lg:pb-16">
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
            <header class="sticky top-0 z-40 border-b border-[var(--border-default)] bg-[var(--bg-surface)]/95 backdrop-blur-xs transition-colors">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16">
                        <!-- Brand Logo -->
                        <div class="flex items-center gap-3">
                            <a href="{{ route('landing') }}" class="flex items-center gap-2.5 group">
                                <img src="{{ asset('images/logo.png') }}" alt="Global Nikah" class="w-9 h-9 rounded-[var(--radius-sm)] object-cover shadow-xs border border-[var(--border-subtle)] group-hover:border-[var(--secondary-primary)] transition">
                                <div>
                                    <span class="font-serif text-xl font-bold tracking-tight text-[var(--accent-primary)] dark:text-[var(--text-primary)] flex items-center gap-1">
                                        Global<span class="text-[var(--secondary-primary)]">Nikkah</span>
                                    </span>
                                    <span class="text-[10px] uppercase font-semibold tracking-wider text-[var(--text-muted)] block -mt-1">Verified Matrimonial Directory</span>
                                </div>
                            </a>
                        </div>

                        <!-- Public Controls -->
                        <div class="flex items-center gap-2 sm:gap-3">
                            <a href="{{ route('how-it-works') }}"
                               class="hidden sm:inline-flex items-center text-xs font-semibold text-[var(--text-secondary)] hover:text-[var(--accent-primary)] px-2.5 py-1.5 rounded-[var(--radius-sm)] transition {{ request()->routeIs('how-it-works') ? 'text-[var(--accent-primary)] font-bold' : '' }}">
                                How It Works
                            </a>

                            <!-- Clean Light / Dark Toggle -->
                            <button type="button"
                                    onclick="
                                        const next = document.documentElement.classList.contains('dark') ? 'light' : 'dark';
                                        if (next === 'dark') {
                                            document.documentElement.classList.add('dark');
                                        } else {
                                            document.documentElement.classList.remove('dark');
                                        }
                                        localStorage.setItem('theme', next);
                                    "
                                    aria-label="Toggle dark / light mode"
                                    class="p-2 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] hover:bg-[var(--bg-subtle)] text-[var(--text-secondary)] transition cursor-pointer">
                                <svg class="w-4 h-4 hidden dark:block text-[var(--secondary-primary)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                                    <circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/>
                                </svg>
                                <svg class="w-4 h-4 block dark:hidden text-[var(--text-secondary)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                                    <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/>
                                </svg>
                            </button>

                            <a href="{{ route('login') }}" class="text-xs font-semibold text-[var(--text-secondary)] hover:text-[var(--accent-primary)] px-3 py-2 transition">
                                Sign In
                            </a>
                            <a href="{{ route('register') }}" class="btn-primary text-xs font-bold h-10 px-4 inline-flex items-center justify-center">
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
                                 { name: 'Compliance & Cookie Consent Governance', cat: 'Governance', icon: '🛡️', url: '{{ route('admin.compliance') }}' },
                             @endif
                             { name: 'Immutable Audit Logs', cat: 'Governance', icon: '📜', url: '{{ route('admin.audit-logs') }}' },
                             { name: 'Platform Policies & Security Rules', cat: 'Settings', icon: '⚙️', url: '{{ route('admin.settings') }}' },
                         @endif

                         @if(Auth::user()->isSeeker() && !Auth::user()->isWali())
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

        // Show toast notification with strict duplicate guard
        const recentToastSignatures = new Set();
        function showHalalNotificationToast(data) {
            const container = document.getElementById('reverb-toast-container');
            if (!container || !data) return;

            // Strict deduplication signature: ID + Title + Message
            const toastKey = (data.id ? String(data.id) : '') + '::' + (data.title || '') + '::' + (data.message || '');
            if (recentToastSignatures.has(toastKey)) {
                return;
            }
            recentToastSignatures.add(toastKey);
            setTimeout(() => recentToastSignatures.delete(toastKey), 4000);

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
                const currentUserId = {{ Auth::id() }};
                window.processedNotificationIds = new Set();

                window.updateSidebarMessageBadge = function(count) {
                    document.querySelectorAll('.sidebar-unread-messages-badge').forEach(badge => {
                        const num = parseInt(count) || 0;
                        if (num > 0) {
                            badge.textContent = num > 99 ? '99+' : num;
                            badge.classList.remove('hidden');
                        } else {
                            badge.classList.add('hidden');
                        }
                    });
                };

                window.handleReverbNotification = function(notification) {
                    const data = notification.data || notification;
                    const notifId = notification.id || data.id || (data.message_id ? 'msg-' + data.message_id : null);

                    if (notifId && window.processedNotificationIds.has(notifId)) {
                        return;
                    }
                    if (notifId) {
                        window.processedNotificationIds.add(notifId);
                    }

                    playHalalNotificationSound();
                    showHalalNotificationToast({
                        id: notifId,
                        title: data.title || 'New Notification',
                        message: data.message || '',
                        icon: data.icon || '🔔',
                        action_url: data.action_url || ''
                    });

                    // If incoming notification is a chat message, dynamically update sidebar message badge
                    if (data.type === 'chat_message' || data.type === 'wali_chaperoned_message' || data.conversation_id) {
                        document.querySelectorAll('.sidebar-unread-messages-badge').forEach(badge => {
                            let cur = parseInt(badge.textContent) || 0;
                            badge.textContent = (cur + 1) > 99 ? '99+' : (cur + 1);
                            badge.classList.remove('hidden');
                        });
                    }

                    window.dispatchEvent(new CustomEvent('new-notification-received', {
                        detail: {
                            id: notifId || 'notif-' + Date.now(),
                            data: data,
                            read_at: null,
                            created_at: 'Just now'
                        }
                    }));
                };

                let echoSubscribed = false;
                function setupEchoListeners() {
                    if (echoSubscribed || !window.Echo) return;
                    echoSubscribed = true;

                    try {
                        // Subscribe exclusively to the user's private notification channel
                        window.Echo.private('App.Models.User.' + currentUserId)
                            .notification(window.handleReverbNotification);
                    } catch (err) {
                        console.warn('Echo subscription setup error:', err);
                    }
                }

                if (window.Echo) {
                    setupEchoListeners();
                } else {
                    window.addEventListener('echo:ready', setupEchoListeners);
                    let checkCount = 0;
                    const echoTimer = setInterval(() => {
                        checkCount++;
                        if (window.Echo) {
                            clearInterval(echoTimer);
                            setupEchoListeners();
                        } else if (checkCount > 50) {
                            clearInterval(echoTimer);
                        }
                    }, 200);
                }
            @endauth
        });
    </script>

    {{-- Global Top Toast for Sign-Out & Status --}}
    @if(session('status'))
        <div x-data="{ show: true }"
             x-show="show"
             x-cloak
             x-init="setTimeout(() => show = false, 5000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="fixed top-4 left-1/2 -translate-x-1/2 z-50 max-w-md w-full px-4 pointer-events-none"
             role="status"
             aria-live="polite">
            <div class="pointer-events-auto flex items-center justify-between gap-3 px-4 py-3 rounded-xl bg-[var(--bg-surface)] text-[var(--text-primary)] border border-[var(--border-default)] shadow-xl">
                <div class="flex items-center gap-2.5">
                    <span class="w-2 h-2 rounded-full bg-[var(--accent-primary)]"></span>
                    <span class="text-xs font-semibold">{{ session('status') }}</span>
                </div>
                <button type="button" @click="show = false" class="text-[var(--text-muted)] hover:text-[var(--text-primary)] text-sm font-bold leading-none p-1 focus:outline-none cursor-pointer">&times;</button>
            </div>
        </div>
    @endif

    {{-- Authenticated Mobile Bottom Tab Bar (5 items max: Discover, Interests, Messages, Shortlist, Profile) --}}
    @auth
        <x-header.mobile-tab-bar />
    @endauth
</body>
</html>

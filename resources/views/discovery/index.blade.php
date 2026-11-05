@extends('layouts.app')

@section('title', 'Candidate Directory - Global Nikkah')

@section('content')
<div class="py-8 bg-[var(--bg-canvas)] min-h-screen" x-data="{ viewMode: 'grid', mobileFilterOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Top Header & Directory Controls -->
        <div class="card-ledger p-5 sm:p-6 bg-[var(--bg-surface)] flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-bold text-[var(--text-primary)]">Candidate Directory</h1>
                    <span class="badge-status text-[var(--accent-text)] bg-[var(--accent-subtle)] border border-[var(--accent-border)]">
                        {{ $profiles->total() }} Available
                    </span>
                </div>
                <p class="text-xs text-[var(--text-secondary)] mt-1">Verified practicing Muslim candidates seeking righteous marriage.</p>
            </div>

            <div class="flex flex-wrap items-center gap-2 sm:gap-3 w-full md:w-auto justify-between md:justify-end">
                <!-- Mobile Filter Sheet Button (44px touch target) -->
                <button type="button" @click="mobileFilterOpen = true"
                        class="lg:hidden btn-secondary text-xs font-semibold">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" x2="20" y1="21" y2="21"/><line x1="4" x2="20" y1="14" y2="14"/><line x1="4" x2="20" y1="7" y2="7"/><circle cx="14" cy="21" r="2"/><circle cx="8" cy="14" r="2"/><circle cx="16" cy="7" r="2"/></svg>
                    <span>Filter</span>
                </button>

                <!-- Grid / List View Switcher -->
                <div class="inline-flex rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-subtle)] p-0.5">
                    <button type="button" @click="viewMode = 'grid'"
                            :class="viewMode === 'grid' ? 'bg-[var(--bg-surface)] text-[var(--accent-text)] shadow-xs' : 'text-[var(--text-muted)] hover:text-[var(--text-primary)]'"
                            class="p-1.5 rounded-[var(--radius-xs)] transition cursor-pointer" title="Grid View" aria-label="Grid View">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
                    </button>
                    <button type="button" @click="viewMode = 'list'"
                            :class="viewMode === 'list' ? 'bg-[var(--bg-surface)] text-[var(--accent-text)] shadow-xs' : 'text-[var(--text-muted)] hover:text-[var(--text-primary)]'"
                            class="p-1.5 rounded-[var(--radius-xs)] transition cursor-pointer" title="List View" aria-label="List View">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" x2="21" y1="6" y2="6"/><line x1="8" x2="21" y1="12" y2="12"/><line x1="8" x2="21" y1="18" y2="18"/><line x1="3" x2="3.01" y1="6" y2="6"/><line x1="3" x2="3.01" y1="12" y2="12"/><line x1="3" x2="3.01" y1="18" y2="18"/></svg>
                    </button>
                </div>

                <!-- Sort Order Selector -->
                <form action="{{ route('discovery.index') }}" method="GET" class="inline">
                    @foreach(request()->except(['sort', 'page']) as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach
                    <select name="sort" onchange="this.form.submit()" aria-label="Sort candidates"
                            class="h-9 px-2.5 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs font-medium focus:ring-2 focus:ring-[var(--accent-primary)] outline-none">
                        <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest first</option>
                        <option value="verified" {{ $sort === 'verified' ? 'selected' : '' }}>Verified first</option>
                        <option value="complete" {{ $sort === 'complete' ? 'selected' : '' }}>Most complete</option>
                    </select>
                </form>

                <!-- Quota / Visitor Shortcuts -->
                <div class="hidden sm:flex items-center gap-2 text-xs">
                    <a href="{{ route('bookmarks.index') }}" class="btn-secondary text-xs font-medium h-9 py-0">
                        <svg class="w-3.5 h-3.5 text-[var(--secondary-primary)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
                        <span>Shortlist</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Active Filter Chips & Reset Bar -->
        @php
            $activeFilters = collect(request()->except(['page', 'sort']))->filter(fn($val) => filled($val));
        @endphp
        @if($activeFilters->isNotEmpty())
            <div class="card-ledger p-3 bg-[var(--bg-subtle)] flex items-center justify-between gap-3 flex-wrap text-xs">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-[var(--text-muted)] font-semibold uppercase text-[10px]">Active Filters:</span>
                    @foreach($activeFilters as $key => $val)
                        @php
                            $chipUrl = route('discovery.index', request()->except([$key, 'page']));
                            $chipLabel = match($key) {
                                'age_min' => 'Min age: ' . $val,
                                'age_max' => 'Max age: ' . $val,
                                'country' => 'Location: ' . $val,
                                'city' => 'City: ' . $val,
                                'marital_status' => 'Status: ' . str_replace('_', ' ', $val),
                                'sect' => 'Sect: ' . $val,
                                'prayer_frequency' => 'Prayer: ' . str_replace('_', ' ', $val),
                                'wali_required' => 'Wali required',
                                default => ucfirst($key) . ': ' . $val
                            };
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-[var(--radius-sm)] bg-[var(--bg-surface)] border border-[var(--border-default)] text-[var(--text-primary)] text-xs">
                            <span>{{ $chipLabel }}</span>
                            <a href="{{ $chipUrl }}" class="text-[var(--text-muted)] hover:text-[var(--color-danger)]" title="Remove filter">&times;</a>
                        </span>
                    @endforeach
                </div>
                <a href="{{ route('discovery.index') }}" class="text-xs font-semibold text-[var(--accent-text)] hover:underline">
                    Reset all filters
                </a>
            </div>
        @endif

        <!-- Main Layout: Left Filter Sidebar + Right Candidate Feed -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- ========================================================= -->
            <!-- DESKTOP LEFT FILTER SIDEBAR (STICKY)                      -->
            <!-- ========================================================= -->
            <aside class="hidden lg:block lg:col-span-3 sticky top-20 card-ledger p-5 bg-[var(--bg-surface)] space-y-5"
                   x-data="{ basicOpen: true, religiousOpen: true, lifestyleOpen: false }">
                <div class="flex items-center justify-between border-b border-[var(--border-subtle)] pb-3">
                    <h2 class="text-xs font-bold text-[var(--text-primary)] uppercase tracking-wider">
                        Search Filters
                    </h2>
                    @if($activeFilters->isNotEmpty())
                        <a href="{{ route('discovery.index') }}" class="text-[11px] font-semibold text-[var(--accent-text)] hover:underline">Reset</a>
                    @endif
                </div>

                <form action="{{ route('discovery.index') }}" method="GET" class="space-y-4">
                    <input type="hidden" name="sort" value="{{ $sort }}">

                    <!-- Group 1: Basic Criteria -->
                    <div class="space-y-3">
                        <button type="button" @click="basicOpen = !basicOpen" class="w-full flex items-center justify-between text-xs font-bold text-[var(--text-primary)] uppercase tracking-wider">
                            <span>Basic Details</span>
                            <span x-text="basicOpen ? '−' : '+'" class="text-sm"></span>
                        </button>
                        <div x-show="basicOpen" class="space-y-3 pt-1">
                            <div>
                                <label class="block text-[11px] font-semibold text-[var(--text-secondary)] mb-1">Age Range</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="number" name="age_min" value="{{ request('age_min') }}" placeholder="Min" min="18" max="75"
                                           class="w-full h-9 px-2.5 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-xs text-[var(--text-primary)] outline-none focus:ring-2 focus:ring-[var(--accent-primary)]">
                                    <input type="number" name="age_max" value="{{ request('age_max') }}" placeholder="Max" min="18" max="75"
                                           class="w-full h-9 px-2.5 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-xs text-[var(--text-primary)] outline-none focus:ring-2 focus:ring-[var(--accent-primary)]">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-semibold text-[var(--text-secondary)] mb-1">Country / District</label>
                                <input type="text" name="country" value="{{ request('country') }}" placeholder="e.g. Bangladesh, UK, USA"
                                       class="w-full h-9 px-2.5 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-xs text-[var(--text-primary)] outline-none focus:ring-2 focus:ring-[var(--accent-primary)]">
                            </div>

                            <div>
                                <label class="block text-[11px] font-semibold text-[var(--text-secondary)] mb-1">Marital Status</label>
                                <select name="marital_status" class="w-full h-9 px-2 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-xs text-[var(--text-primary)] outline-none focus:ring-2 focus:ring-[var(--accent-primary)]">
                                    <option value="">Any</option>
                                    <option value="never_married" {{ request('marital_status') === 'never_married' ? 'selected' : '' }}>Never married</option>
                                    <option value="divorced" {{ request('marital_status') === 'divorced' ? 'selected' : '' }}>Divorced</option>
                                    <option value="widowed" {{ request('marital_status') === 'widowed' ? 'selected' : '' }}>Widowed</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Group 2: Religious Practice -->
                    <div class="border-t border-[var(--border-subtle)] pt-3 space-y-3">
                        <button type="button" @click="religiousOpen = !religiousOpen" class="w-full flex items-center justify-between text-xs font-bold text-[var(--text-primary)] uppercase tracking-wider">
                            <span>Religious Practice</span>
                            <span x-text="religiousOpen ? '−' : '+'" class="text-sm"></span>
                        </button>
                        <div x-show="religiousOpen" class="space-y-3 pt-1">
                            <div>
                                <label class="block text-[11px] font-semibold text-[var(--text-secondary)] mb-1">Sect / Madhhab</label>
                                <select name="sect" class="w-full h-9 px-2 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-xs text-[var(--text-primary)] outline-none focus:ring-2 focus:ring-[var(--accent-primary)]">
                                    <option value="">Any Madhhab</option>
                                    <option value="Sunni - Hanafi" {{ request('sect') === 'Sunni - Hanafi' ? 'selected' : '' }}>Sunni - Hanafi</option>
                                    <option value="Sunni - Shafi'i" {{ request('sect') === 'Sunni - Shafi\'i' ? 'selected' : '' }}>Sunni - Shafi'i</option>
                                    <option value="Sunni - Maliki" {{ request('sect') === 'Sunni - Maliki' ? 'selected' : '' }}>Sunni - Maliki</option>
                                    <option value="Sunni - Hanbali" {{ request('sect') === 'Sunni - Hanbali' ? 'selected' : '' }}>Sunni - Hanbali</option>
                                    <option value="Shia - Jafari" {{ request('sect') === 'Shia - Jafari' ? 'selected' : '' }}>Shia - Jafari</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[11px] font-semibold text-[var(--text-secondary)] mb-1">Prayer Frequency</label>
                                <select name="prayer_frequency" class="w-full h-9 px-2 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-xs text-[var(--text-primary)] outline-none focus:ring-2 focus:ring-[var(--accent-primary)]">
                                    <option value="">Any</option>
                                    <option value="5x_daily" {{ request('prayer_frequency') === '5x_daily' ? 'selected' : '' }}>Always 5x Daily</option>
                                    <option value="occasional" {{ request('prayer_frequency') === 'occasional' ? 'selected' : '' }}>Regular / Striving</option>
                                </select>
                            </div>

                            <div>
                                <label class="flex items-center gap-2 text-xs text-[var(--text-primary)] cursor-pointer">
                                    <input type="checkbox" name="wali_required" value="1" {{ request('wali_required') ? 'checked' : '' }}
                                           class="rounded-[var(--radius-xs)] text-[var(--accent-primary)] focus:ring-[var(--accent-primary)]">
                                    <span>Guardian (Wali) Linked Only</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="btn-primary w-full text-xs font-bold">
                            <span>Apply Filters</span>
                        </button>
                    </div>
                </form>
            </aside>

            <!-- ========================================================= -->
            <!-- MOBILE BOTTOM SHEET FILTER DRAWER                         -->
            <!-- ========================================================= -->
            <div x-show="mobileFilterOpen" x-cloak class="fixed inset-0 z-50 lg:hidden" role="dialog" aria-modal="true">
                <div class="fixed inset-0 bg-black/50 transition-opacity" @click="mobileFilterOpen = false"></div>
                <div class="fixed inset-x-0 bottom-0 max-h-[85vh] overflow-y-auto bg-[var(--bg-surface)] rounded-t-[var(--radius-lg)] p-5 border-t border-[var(--border-default)] shadow-2xl">
                    <div class="flex items-center justify-between border-b border-[var(--border-subtle)] pb-3 mb-4">
                        <h2 class="text-sm font-bold text-[var(--text-primary)]">Filter Candidates</h2>
                        <button type="button" @click="mobileFilterOpen = false" class="p-2 text-[var(--text-muted)] hover:text-[var(--text-primary)] text-lg">&times;</button>
                    </div>

                    <form action="{{ route('discovery.index') }}" method="GET" class="space-y-4">
                        <input type="hidden" name="sort" value="{{ $sort }}">

                        <div>
                            <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">Age Range</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="number" name="age_min" value="{{ request('age_min') }}" placeholder="Min age"
                                       class="w-full h-10 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] text-xs">
                                <input type="number" name="age_max" value="{{ request('age_max') }}" placeholder="Max age"
                                       class="w-full h-10 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] text-xs">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">District / Country</label>
                            <input type="text" name="country" value="{{ request('country') }}" placeholder="e.g. Dhaka, London"
                                   class="w-full h-10 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] text-xs">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">Sect / Madhhab</label>
                            <select name="sect" class="w-full h-10 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] text-xs">
                                <option value="">Any</option>
                                <option value="Sunni - Hanafi" {{ request('sect') === 'Sunni - Hanafi' ? 'selected' : '' }}>Sunni - Hanafi</option>
                                <option value="Sunni - Shafi'i" {{ request('sect') === 'Sunni - Shafi\'i' ? 'selected' : '' }}>Sunni - Shafi'i</option>
                            </select>
                        </div>

                        <div class="pt-3 flex gap-2">
                            <a href="{{ route('discovery.index') }}" class="btn-secondary flex-1 text-xs">Reset</a>
                            <button type="submit" class="btn-primary flex-1 text-xs font-bold">Apply</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- RIGHT COLUMN: CANDIDATE CARDS (GRID OR LIST)              -->
            <!-- ========================================================= -->
            <div class="lg:col-span-9 space-y-6">

                <!-- GRID VIEW -->
                <div x-show="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                    @forelse($profiles as $p)
                        <article class="card-ledger flex flex-col justify-between overflow-hidden">
                            <div>
                                <!-- Photo Container with Modesty Shield -->
                                <div class="relative h-48 bg-[var(--neutral-900)] overflow-hidden">
                                    @if($p->primaryPhoto)
                                        <img src="{{ $p->primaryPhoto->displayUrl() }}" alt="{{ $p->user->name }}"
                                             class="w-full h-full object-cover photo-blur" loading="lazy" decoding="async">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-[var(--neutral-800)] text-[var(--neutral-300)] font-semibold text-2xl">
                                            {{ substr($p->user->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

                                    <!-- Top Badges -->
                                    <div class="absolute top-2.5 inset-x-2.5 flex items-center justify-between">
                                        <div class="flex items-center gap-1.5">
                                            @if($p->user->is_verified)
                                                <span class="badge-verified">
                                                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                                                    <span>Verified</span>
                                                </span>
                                            @endif
                                            @if($p->user->isPro())
                                                <span class="px-2 py-0.5 rounded-[var(--radius-sm)] text-[10px] font-bold bg-[var(--secondary-subtle)] text-[var(--secondary-text)] border border-[var(--secondary-border)]">
                                                    Pro
                                                </span>
                                            @endif
                                        </div>

                                        @if(isset($p->compatibility_score))
                                            <span class="px-2 py-0.5 rounded-[var(--radius-sm)] text-[10px] font-bold bg-black/70 text-white border border-white/10">
                                                {{ $p->compatibility_score }}% Match
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Overlay: Name, Age, Profession -->
                                    <div class="absolute bottom-2.5 inset-x-3 text-white">
                                        <div class="flex items-center justify-between gap-1">
                                            <h3 class="text-sm font-bold truncate">{{ $p->user->name }}</h3>
                                            <span class="text-[10px] px-2 py-0.5 rounded-[var(--radius-xs)] bg-white/20 text-white font-medium capitalize">
                                                {{ str_replace('_', ' ', $p->marital_status ?? 'Single') }}
                                            </span>
                                        </div>
                                        <p class="text-[11px] text-[var(--neutral-300)] truncate mt-0.5">
                                            {{ $p->user->age }} yrs • {{ $p->height ? round($p->height / 30.48, 1) . ' ft • ' : '' }}{{ $p->occupation ?: 'Professional' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Core Metadata Grid -->
                                <div class="p-3.5 space-y-2 border-t border-[var(--border-subtle)]">
                                    <div class="grid grid-cols-2 gap-2 text-[11px]">
                                        <div>
                                            <span class="text-[var(--text-muted)] block text-[10px] uppercase font-semibold">Location</span>
                                            <span class="text-[var(--text-primary)] font-medium truncate block">{{ $p->city ?: 'District flexible' }}, {{ $p->country ?: '' }}</span>
                                        </div>
                                        <div>
                                            <span class="text-[var(--text-muted)] block text-[10px] uppercase font-semibold">Education</span>
                                            <span class="text-[var(--text-primary)] font-medium truncate block">{{ $p->education_level ?: 'Graduate' }}</span>
                                        </div>
                                        <div>
                                            <span class="text-[var(--text-muted)] block text-[10px] uppercase font-semibold">Sect / Madhhab</span>
                                            <span class="text-[var(--text-primary)] font-medium truncate block">{{ $p->sect_madhhab ?: 'Muslim' }}</span>
                                        </div>
                                        <div>
                                            <span class="text-[var(--text-muted)] block text-[10px] uppercase font-semibold">Guardian</span>
                                            <span class="text-[var(--text-primary)] font-medium truncate block">{{ $p->wali_required ? 'Wali Linked' : 'Self-managed' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Actions: Primary "Send interest", Secondary "Shortlist" -->
                            <div class="p-3.5 pt-0 border-t border-[var(--border-subtle)] mt-2 flex items-center gap-2">
                                <a href="{{ route('discovery.show', $p->user_id) }}" class="btn-primary flex-1 text-xs font-semibold py-2">
                                    <span>Send interest</span>
                                </a>

                                @php $isBookmarkedGrid = in_array($p->user_id, $bookmarkedUserIds ?? [], true); @endphp
                                <form action="{{ route('bookmarks.toggle', $p->user_id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            title="{{ $isBookmarkedGrid ? 'Remove from shortlist' : 'Shortlist candidate' }}"
                                            class="h-9 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] {{ $isBookmarkedGrid ? 'bg-[var(--secondary-subtle)] text-[var(--secondary-text)] border-[var(--secondary-border)]' : 'bg-[var(--bg-surface)] text-[var(--text-muted)] hover:text-[var(--text-primary)] hover:bg-[var(--bg-subtle)]' }} transition cursor-pointer flex items-center justify-center">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="{{ $isBookmarkedGrid ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
                                    </button>
                                </form>
                            </div>
                        </article>
                    @empty
                        <div class="col-span-full py-16 text-center card-ledger p-8 bg-[var(--bg-surface)]">
                            <svg class="w-10 h-10 text-[var(--text-muted)] mx-auto mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                            <h3 class="text-sm font-bold text-[var(--text-primary)]">No candidates found</h3>
                            <p class="text-xs text-[var(--text-secondary)] mt-1 max-w-sm mx-auto">No candidate profiles currently match your filter criteria. Try expanding your age range or resetting filters.</p>
                            <div class="pt-4">
                                <a href="{{ route('discovery.index') }}" class="btn-secondary text-xs font-semibold">Reset All Filters</a>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- LIST VIEW (EDITORIAL TABLET/ROW LAYOUT) -->
                <div x-show="viewMode === 'list'" class="space-y-3">
                    @forelse($profiles as $p)
                        <article class="card-ledger p-4 bg-[var(--bg-surface)] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-4 min-w-0">
                                <!-- Avatar -->
                                <div class="w-16 h-16 rounded-[var(--radius-sm)] bg-[var(--neutral-900)] overflow-hidden shrink-0 relative">
                                    @if($p->primaryPhoto)
                                        <img src="{{ $p->primaryPhoto->displayUrl() }}" alt="{{ $p->user->name }}" class="w-full h-full object-cover photo-blur" loading="lazy">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-[var(--neutral-800)] text-white font-bold text-lg">
                                            {{ substr($p->user->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Metadata Summary -->
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h3 class="text-sm font-bold text-[var(--text-primary)] truncate">{{ $p->user->name }}</h3>
                                        @if($p->user->is_verified)
                                            <span class="badge-verified">Verified</span>
                                        @endif
                                        <span class="text-[11px] text-[var(--text-muted)]">
                                            {{ $p->user->age }} yrs • {{ $p->height ? round($p->height / 30.48, 1) . ' ft • ' : '' }}{{ $p->occupation ?: 'Professional' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-3 mt-1 text-xs text-[var(--text-secondary)] flex-wrap">
                                        <span>{{ $p->city ?: 'District flexible' }}, {{ $p->country ?: '' }}</span>
                                        <span>&bull;</span>
                                        <span>{{ $p->sect_madhhab ?: 'Muslim' }}</span>
                                        <span>&bull;</span>
                                        <span>{{ $p->education_level ?: 'Graduate' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-2 w-full sm:w-auto shrink-0 justify-end pt-2 sm:pt-0 border-t sm:border-t-0 border-[var(--border-subtle)]">
                                <a href="{{ route('discovery.show', $p->user_id) }}" class="btn-primary text-xs font-semibold py-2 px-3">
                                    <span>Send interest</span>
                                </a>

                                @php $isBookmarkedList = in_array($p->user_id, $bookmarkedUserIds ?? [], true); @endphp
                                <form action="{{ route('bookmarks.toggle', $p->user_id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            title="{{ $isBookmarkedList ? 'Remove from shortlist' : 'Shortlist' }}"
                                            class="h-9 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] {{ $isBookmarkedList ? 'bg-[var(--secondary-subtle)] text-[var(--secondary-text)]' : 'bg-[var(--bg-surface)] text-[var(--text-muted)] hover:text-[var(--text-primary)]' }} transition flex items-center justify-center">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="{{ $isBookmarkedList ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
                                    </button>
                                </form>
                            </div>
                        </article>
                    @empty
                        <div class="card-ledger p-12 text-center bg-[var(--bg-surface)]">
                            <p class="text-sm font-semibold text-[var(--text-secondary)]">No candidate profiles found.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="pt-4">
                    {{ $profiles->links() }}
                </div>
            </div>

        </div>

    </div>
</div>
@endsection

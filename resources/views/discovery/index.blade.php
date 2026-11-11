@extends('layouts.app')

@section('title', 'Candidate Directory • Global Nikkah')

@section('content')
<div class="py-8 sm:py-12 bg-[var(--bg-canvas)] min-h-screen" x-data="{ viewMode: 'grid', mobileFilterOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Top Header & Directory Controls -->
        <div class="card-ledger pattern-header p-6 sm:p-7 bg-[var(--bg-surface)] flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-[var(--secondary-text)] block">Candidate Directory</span>
                <div class="flex items-center gap-3 mt-0.5">
                    <h1 class="text-2xl sm:text-3xl font-serif font-bold text-[var(--text-primary)]">Verified Candidates</h1>
                    <span class="badge-status bg-[var(--accent-subtle)] text-[var(--accent-text)] border border-[var(--accent-border)] font-semibold text-xs">
                        {{ $profiles->total() }} Profiles
                    </span>
                </div>
                <p class="text-xs text-[var(--text-secondary)] mt-1">Practicing Muslim candidates seeking marriage with family involvement.</p>
            </div>

            <div class="flex flex-wrap items-center gap-2 sm:gap-3 w-full md:w-auto justify-between md:justify-end">
                <!-- Mobile Filter Trigger (44px touch target) -->
                <button type="button" @click="mobileFilterOpen = true"
                        class="lg:hidden btn-secondary text-xs font-semibold h-10 py-0">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><line x1="4" x2="20" y1="21" y2="21"/><line x1="4" x2="20" y1="14" y2="14"/><line x1="4" x2="20" y1="7" y2="7"/><circle cx="14" cy="21" r="2"/><circle cx="8" cy="14" r="2"/><circle cx="16" cy="7" r="2"/></svg>
                    <span>Filters</span>
                </button>

                <!-- Grid / List Switcher -->
                <div class="inline-flex rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-subtle)] p-0.5" role="group" aria-label="View Mode">
                    <button type="button" @click="viewMode = 'grid'"
                            :class="viewMode === 'grid' ? 'bg-[var(--bg-surface)] text-[var(--accent-text)] shadow-xs' : 'text-[var(--text-muted)] hover:text-[var(--text-primary)]'"
                            class="p-2 rounded-[var(--radius-xs)] transition cursor-pointer" title="Grid View" aria-label="Grid View">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
                    </button>
                    <button type="button" @click="viewMode = 'list'"
                            :class="viewMode === 'list' ? 'bg-[var(--bg-surface)] text-[var(--accent-text)] shadow-xs' : 'text-[var(--text-muted)] hover:text-[var(--text-primary)]'"
                            class="p-2 rounded-[var(--radius-xs)] transition cursor-pointer" title="List View" aria-label="List View">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><line x1="8" x2="21" y1="6" y2="6"/><line x1="8" x2="21" y1="12" y2="12"/><line x1="8" x2="21" y1="18" y2="18"/><line x1="3" x2="3.01" y1="6" y2="6"/><line x1="3" x2="3.01" y1="12" y2="12"/><line x1="3" x2="3.01" y1="18" y2="18"/></svg>
                    </button>
                </div>

                <!-- Sort Order Selector -->
                <form action="{{ route('discovery.index') }}" method="GET" class="inline">
                    @foreach(request()->except(['sort', 'page']) as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach
                    <select name="sort" onchange="this.form.submit()" aria-label="Sort candidates"
                            class="h-10 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs font-medium focus:ring-2 focus:ring-[var(--accent-primary)] outline-none">
                        <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest profiles first</option>
                        <option value="verified" {{ $sort === 'verified' ? 'selected' : '' }}>Verified identities first</option>
                        <option value="complete" {{ $sort === 'complete' ? 'selected' : '' }}>Most complete profiles</option>
                    </select>
                </form>

                <!-- Shortlist Shortcut -->
                <a href="{{ route('bookmarks.index') }}" class="btn-secondary text-xs font-semibold h-10 py-0 hidden sm:inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-[var(--secondary-primary)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
                    <span>Shortlisted</span>
                </a>
            </div>
        </div>

        <!-- Active Filter Chips -->
        @php
            $activeFilters = collect(request()->except(['page', 'sort']))->filter(fn($val) => filled($val));
        @endphp
        @if($activeFilters->isNotEmpty())
            <div class="card-ledger p-3.5 bg-[var(--bg-subtle)] flex items-center justify-between gap-3 flex-wrap text-xs">
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

        <!-- Main Layout: Sidebar + Candidate Feed -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- Desktop Sticky Sidebar -->
            <aside class="hidden lg:block lg:col-span-3 sticky top-20 card-ledger p-5 bg-[var(--bg-surface)] space-y-5"
                   x-data="{ basicOpen: true, religiousOpen: true }">
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
                            <span>Age &amp; Location</span>
                            <span x-text="basicOpen ? '−' : '+'" class="text-sm"></span>
                        </button>
                        <div x-show="basicOpen" class="space-y-3 pt-1">
                            <div>
                                <label class="block text-[11px] font-semibold text-[var(--text-secondary)] mb-1">Age Range</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="number" name="age_min" value="{{ request('age_min') }}" placeholder="Min" min="18" max="75"
                                           class="w-full h-9 px-2.5 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs focus:ring-2 focus:ring-[var(--accent-primary)] outline-none">
                                    <input type="number" name="age_max" value="{{ request('age_max') }}" placeholder="Max" min="18" max="75"
                                           class="w-full h-9 px-2.5 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs focus:ring-2 focus:ring-[var(--accent-primary)] outline-none">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-semibold text-[var(--text-secondary)] mb-1">City or District</label>
                                <input type="text" name="city" value="{{ request('city') }}" placeholder="e.g. London, Toronto, Dubai"
                                       class="w-full h-9 px-2.5 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs focus:ring-2 focus:ring-[var(--accent-primary)] outline-none">
                            </div>

                            <div>
                                <label class="block text-[11px] font-semibold text-[var(--text-secondary)] mb-1">Country</label>
                                <input type="text" name="country" value="{{ request('country') }}" placeholder="e.g. United Kingdom, UAE, USA"
                                       class="w-full h-9 px-2.5 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs focus:ring-2 focus:ring-[var(--accent-primary)] outline-none">
                            </div>

                            <div>
                                <label class="block text-[11px] font-semibold text-[var(--text-secondary)] mb-1">Marital Status</label>
                                <select name="marital_status" class="w-full h-9 px-2 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs focus:ring-2 focus:ring-[var(--accent-primary)] outline-none">
                                    <option value="">Any status</option>
                                    <option value="never_married" {{ request('marital_status') === 'never_married' ? 'selected' : '' }}>Never married</option>
                                    <option value="divorced" {{ request('marital_status') === 'divorced' ? 'selected' : '' }}>Divorced</option>
                                    <option value="widowed" {{ request('marital_status') === 'widowed' ? 'selected' : '' }}>Widowed</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Group 2: Religious Practice -->
                    <div class="space-y-3 pt-3 border-t border-[var(--border-subtle)]">
                        <button type="button" @click="religiousOpen = !religiousOpen" class="w-full flex items-center justify-between text-xs font-bold text-[var(--text-primary)] uppercase tracking-wider">
                            <span>Religious Practice</span>
                            <span x-text="religiousOpen ? '−' : '+'" class="text-sm"></span>
                        </button>
                        <div x-show="religiousOpen" class="space-y-3 pt-1">
                            <div>
                                <label class="block text-[11px] font-semibold text-[var(--text-secondary)] mb-1">Sect / Madhhab</label>
                                <select name="sect" class="w-full h-9 px-2 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs focus:ring-2 focus:ring-[var(--accent-primary)] outline-none">
                                    <option value="">Any</option>
                                    <option value="Sunni - Hanafi" {{ request('sect') === 'Sunni - Hanafi' ? 'selected' : '' }}>Sunni &bull; Hanafi</option>
                                    <option value="Sunni - Shafi'i" {{ request('sect') === 'Sunni - Shafi\'i' ? 'selected' : '' }}>Sunni &bull; Shafi'i</option>
                                    <option value="Sunni - Maliki" {{ request('sect') === 'Sunni - Maliki' ? 'selected' : '' }}>Sunni &bull; Maliki</option>
                                    <option value="Sunni - Hanbali" {{ request('sect') === 'Sunni - Hanbali' ? 'selected' : '' }}>Sunni &bull; Hanbali</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[11px] font-semibold text-[var(--text-secondary)] mb-1">Salah (Prayer)</label>
                                <select name="prayer_frequency" class="w-full h-9 px-2 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs focus:ring-2 focus:ring-[var(--accent-primary)] outline-none">
                                    <option value="">Any</option>
                                    <option value="5x_daily" {{ request('prayer_frequency') === '5x_daily' ? 'selected' : '' }}>5x daily prayer</option>
                                    <option value="occasional" {{ request('prayer_frequency') === 'occasional' ? 'selected' : '' }}>Most prayers / Striving</option>
                                </select>
                            </div>

                            <label class="flex items-center gap-2 text-xs text-[var(--text-secondary)] cursor-pointer pt-1">
                                <input type="checkbox" name="wali_required" value="1" {{ request('wali_required') ? 'checked' : '' }}
                                       class="rounded-[var(--radius-xs)] text-[var(--accent-primary)] focus:ring-[var(--accent-primary)]">
                                <span>Wali required profiles only</span>
                            </label>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="btn-primary w-full text-xs font-bold py-2.5">
                            Apply Filters
                        </button>
                    </div>
                </form>
            </aside>

            <!-- Mobile Drawer Filter -->
            <div x-show="mobileFilterOpen" x-cloak class="fixed inset-0 z-50 lg:hidden" role="dialog" aria-modal="true">
                <div class="fixed inset-0 bg-black/50 transition-opacity" @click="mobileFilterOpen = false"></div>
                <div class="fixed inset-x-0 bottom-0 max-h-[85vh] overflow-y-auto bg-[var(--bg-surface)] rounded-t-[var(--radius-lg)] p-6 border-t border-[var(--border-default)] shadow-2xl">
                    <div class="flex items-center justify-between border-b border-[var(--border-subtle)] pb-3 mb-4">
                        <h2 class="text-base font-serif font-bold text-[var(--text-primary)]">Filter Candidates</h2>
                        <button type="button" @click="mobileFilterOpen = false" class="p-2 text-[var(--text-muted)] hover:text-[var(--text-primary)] text-lg">&times;</button>
                    </div>

                    <form action="{{ route('discovery.index') }}" method="GET" class="space-y-4">
                        <input type="hidden" name="sort" value="{{ $sort }}">

                        <div>
                            <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">Age Range</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="number" name="age_min" value="{{ request('age_min') }}" placeholder="Min age"
                                       class="w-full h-11 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] text-xs">
                                <input type="number" name="age_max" value="{{ request('age_max') }}" placeholder="Max age"
                                       class="w-full h-11 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] text-xs">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">Location</label>
                            <input type="text" name="country" value="{{ request('country') }}" placeholder="e.g. London, Dubai, Toronto"
                                   class="w-full h-11 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] text-xs">
                        </div>

                        <div class="pt-2 flex gap-3">
                            <a href="{{ route('discovery.index') }}" class="btn-secondary flex-1 text-xs py-3 text-center">Reset</a>
                            <button type="submit" class="btn-primary flex-1 text-xs font-bold py-3">Apply Filters</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Candidate Feed (Grid / List) -->
            <div class="lg:col-span-9 space-y-6">

                <!-- GRID VIEW -->
                <div x-show="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @forelse($profiles as $p)
                        <article class="card-ledger flex flex-col justify-between overflow-hidden bg-[var(--bg-surface)] hover-lift">
                            <div>
                                <!-- Mihrab Arch Profile Photo -->
                                <div class="p-3 pb-0">
                                    <div class="mihrab-arch relative h-60 sm:h-56 bg-[var(--neutral-900)] overflow-hidden border border-[var(--border-subtle)]">
                                        @if($p->primaryPhoto)
                                            <img src="{{ $p->primaryPhoto->displayUrl() }}" alt="{{ $p->user->name }}"
                                                 class="w-full h-full object-cover photo-blur" loading="lazy" decoding="async">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-[var(--neutral-800)] text-[var(--neutral-300)] font-serif text-3xl">
                                                {{ substr($p->user->name, 0, 1) }}
                                            </div>
                                        @endif

                                        <!-- Modesty Shield & Verification Badges -->
                                        <div class="absolute inset-x-0 bottom-0 p-3 bg-gradient-to-t from-black/85 via-black/45 to-transparent text-white">
                                            <div class="flex items-center justify-between text-[11px] font-medium">
                                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-black/60 text-white/95 backdrop-blur-xs">
                                                    <svg class="w-3 h-3 text-[var(--secondary-primary)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                                    <span>Photo blurred</span>
                                                </span>
                                                @if($p->user->is_verified)
                                                    <span class="badge-verified bg-emerald-950/85 text-emerald-200 border-emerald-700/60 font-semibold px-2 py-0.5">
                                                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                                                        <span>Verified</span>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Candidate Metadata Hierarchy -->
                                <div class="p-4 space-y-2">
                                    <div>
                                        <div class="flex items-baseline justify-between gap-2">
                                            <h3 class="text-base font-serif font-bold text-[var(--text-primary)] truncate">{{ $p->user->name }}</h3>
                                            <span class="text-[11px] text-[var(--text-muted)] font-medium capitalize shrink-0">
                                                {{ str_replace('_', ' ', $p->marital_status ?? 'Single') }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-[var(--text-secondary)] font-medium mt-0.5">
                                            {{ $p->user->age }} yrs &bull; {{ $p->city ?: 'Open to relocation' }}, {{ $p->country ?: '' }}
                                        </p>
                                        @if($p->profession)
                                            <p class="text-xs text-[var(--text-muted)] truncate mt-0.5">
                                                {{ $p->profession }}
                                            </p>
                                        @endif
                                        @if($p->bio)
                                            <p class="text-xs text-[var(--text-secondary)] line-clamp-2 mt-1.5 leading-relaxed italic">
                                                "{{ Str::limit($p->bio, 95) }}"
                                            </p>
                                        @endif
                                    </div>

                                    <div class="pt-2 border-t border-[var(--border-subtle)] grid grid-cols-2 gap-2 text-xs">
                                        <div>
                                            <span class="text-[10px] uppercase font-bold text-[var(--text-muted)] block">Prayer</span>
                                            <span class="text-[var(--text-primary)] font-semibold truncate block">
                                                {{ str_replace('_', ' ', $p->prayer_frequency ?: '5x Daily') }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="text-[10px] uppercase font-bold text-[var(--text-muted)] block">Guardian</span>
                                            <span class="text-[var(--text-primary)] font-semibold truncate block">
                                                {{ $p->wali_required ? 'Wali involved' : 'Self-guided' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="p-4 pt-0 border-t border-[var(--border-subtle)] mt-2 flex items-center gap-2">
                                <a href="{{ route('discovery.show', $p->user_id) }}" class="btn-primary flex-1 text-xs font-bold h-11 py-0 flex items-center justify-center">
                                    <span>Review biodata</span>
                                </a>

                                @php $isBookmarkedGrid = in_array($p->user_id, $bookmarkedUserIds ?? [], true); @endphp
                                <form action="{{ route('bookmarks.toggle', $p->user_id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            title="{{ $isBookmarkedGrid ? 'Remove from shortlist' : 'Shortlist candidate' }}"
                                            class="h-11 w-11 rounded-[var(--radius-sm)] border border-[var(--border-default)] {{ $isBookmarkedGrid ? 'bg-[var(--secondary-subtle)] text-[var(--secondary-text)] border-[var(--secondary-border)]' : 'bg-[var(--bg-surface)] text-[var(--text-muted)] hover:text-[var(--text-primary)] hover:bg-[var(--bg-subtle)]' }} transition cursor-pointer flex items-center justify-center">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="{{ $isBookmarkedGrid ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.75"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
                                    </button>
                                </form>
                            </div>
                        </article>
                    @empty
                        <div class="col-span-full py-16 text-center card-ledger p-8 bg-[var(--bg-surface)] space-y-3">
                            <svg class="w-10 h-10 text-[var(--text-muted)] mx-auto" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                            <h3 class="text-base font-serif font-bold text-[var(--text-primary)]">No matching candidates found</h3>
                            <p class="text-xs text-[var(--text-secondary)] max-w-sm mx-auto leading-relaxed">
                                No candidate profiles currently match your filter selections. Try broadening your age range or resetting filters to see all available profiles.
                            </p>
                            <div class="pt-2">
                                <a href="{{ route('discovery.index') }}" class="btn-secondary text-xs font-semibold px-4 py-2">Reset all filters</a>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- LIST VIEW -->
                <div x-show="viewMode === 'list'" class="space-y-4">
                    @forelse($profiles as $p)
                        <article class="card-ledger p-4 sm:p-5 bg-[var(--bg-surface)] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 hover-lift">
                            <div class="flex items-center gap-4 min-w-0">
                                <!-- Mihrab Thumbnail -->
                                <div class="mihrab-arch w-16 h-20 bg-[var(--neutral-900)] overflow-hidden shrink-0 border border-[var(--border-subtle)] relative">
                                    @if($p->primaryPhoto)
                                        <img src="{{ $p->primaryPhoto->displayUrl() }}" alt="{{ $p->user->name }}" class="w-full h-full object-cover photo-blur" loading="lazy">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-[var(--neutral-800)] text-white font-serif text-xl">
                                            {{ substr($p->user->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>

                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h3 class="text-base font-serif font-bold text-[var(--text-primary)] truncate">{{ $p->user->name }}</h3>
                                        @if($p->user->is_verified)
                                            <span class="badge-verified">Verified</span>
                                        @endif
                                        <span class="text-xs text-[var(--text-muted)]">
                                            &bull; {{ $p->user->age }} yrs &bull; {{ $p->city ?: 'District flexible' }}, {{ $p->country ?: '' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2 mt-1 text-xs text-[var(--text-secondary)] flex-wrap">
                                        <span>{{ $p->profession ?: 'Professional' }}</span>
                                        <span>&bull;</span>
                                        <span>{{ $p->sect_madhhab ?: 'Practicing Muslim' }}</span>
                                        <span>&bull;</span>
                                        <span>{{ $p->wali_required ? 'Wali Linked' : 'Self-guided' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 w-full sm:w-auto shrink-0 justify-end pt-2 sm:pt-0 border-t sm:border-t-0 border-[var(--border-subtle)]">
                                <a href="{{ route('discovery.show', $p->user_id) }}" class="btn-primary text-xs font-semibold py-2 px-4">
                                    <span>Review biodata</span>
                                </a>

                                @php $isBookmarkedList = in_array($p->user_id, $bookmarkedUserIds ?? [], true); @endphp
                                <form action="{{ route('bookmarks.toggle', $p->user_id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            title="{{ $isBookmarkedList ? 'Remove from shortlist' : 'Shortlist candidate' }}"
                                            class="h-9 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] {{ $isBookmarkedList ? 'bg-[var(--secondary-subtle)] text-[var(--secondary-text)]' : 'bg-[var(--bg-surface)] text-[var(--text-muted)] hover:text-[var(--text-primary)]' }} transition flex items-center justify-center">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="{{ $isBookmarkedList ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.75"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
                                    </button>
                                </form>
                            </div>
                        </article>
                    @empty
                        <div class="card-ledger p-12 text-center bg-[var(--bg-surface)]">
                            <p class="text-sm font-serif text-[var(--text-secondary)]">No candidate profiles found.</p>
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

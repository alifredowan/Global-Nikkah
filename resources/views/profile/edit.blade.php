@extends('layouts.app')

@section('title', 'Edit Matrimonial Profile & Preferences • Global Nikkah')

@section('content')
<div class="py-8 sm:py-12 bg-[var(--bg-canvas)] min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Top Header Card -->
        <div class="card-ledger pattern-header p-6 sm:p-7 bg-[var(--bg-surface)] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-[var(--secondary-text)] block">Candidate Biodata</span>
                <h1 class="text-2xl sm:text-3xl font-serif font-bold text-[var(--text-primary)] mt-0.5">
                    Profile &amp; Preferences
                </h1>
                <p class="text-xs text-[var(--text-secondary)] mt-1 max-w-xl leading-relaxed">
                    Honest faith and family details help candidates evaluate compatibility with sincerity.
                </p>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-[var(--bg-subtle)] border border-[var(--border-default)]">
                    <span class="text-xs font-bold text-[var(--accent-text)]">{{ $profile->completeness_percentage ?? 80 }}%</span>
                    <span class="text-[11px] text-[var(--text-muted)]">Complete</span>
                </div>
                <a href="{{ route('profile.show') }}" class="btn-secondary text-xs h-10 py-0">
                    <span>View profile</span>
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-[var(--radius-sm)] bg-[var(--color-success-bg)] border border-[var(--color-success-border)] text-xs text-[var(--color-success)] flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
            @csrf

            <!-- 1. Religious Practice & Character -->
            <div class="card-ledger p-6 sm:p-7 bg-[var(--bg-surface)] space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-[var(--border-subtle)]">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[var(--secondary-text)] block">Section 1</span>
                        <h2 class="text-lg font-serif font-bold text-[var(--text-primary)]">Religious Practice &amp; Deen</h2>
                    </div>
                    <span class="text-[11px] text-[var(--text-muted)]">Core compatibility</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="sect_madhhab" class="block text-xs font-semibold text-[var(--text-secondary)] mb-1.5">Sect / Madhhab</label>
                        <select id="sect_madhhab" name="sect_madhhab" class="w-full h-11 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs focus:ring-2 focus:ring-[var(--accent-primary)] outline-none">
                            <option value="">Select Madhhab / Practice</option>
                            <option value="Sunni - Hanafi" {{ old('sect_madhhab', $profile->sect_madhhab) === 'Sunni - Hanafi' ? 'selected' : '' }}>Sunni &bull; Hanafi</option>
                            <option value="Sunni - Shafi'i" {{ old('sect_madhhab', $profile->sect_madhhab) === 'Sunni - Shafi\'i' ? 'selected' : '' }}>Sunni &bull; Shafi'i</option>
                            <option value="Sunni - Maliki" {{ old('sect_madhhab', $profile->sect_madhhab) === 'Sunni - Maliki' ? 'selected' : '' }}>Sunni &bull; Maliki</option>
                            <option value="Sunni - Hanbali" {{ old('sect_madhhab', $profile->sect_madhhab) === 'Sunni - Hanbali' ? 'selected' : '' }}>Sunni &bull; Hanbali</option>
                            <option value="Shia - Jafari" {{ old('sect_madhhab', $profile->sect_madhhab) === 'Shia - Jafari' ? 'selected' : '' }}>Shia &bull; Ja'fari</option>
                            <option value="Muslim (Unspecified)" {{ old('sect_madhhab', $profile->sect_madhhab) === 'Muslim (Unspecified)' ? 'selected' : '' }}>Muslim &bull; Striving on Qur'an &amp; Sunnah</option>
                        </select>
                    </div>

                    <div>
                        <label for="prayer_frequency" class="block text-xs font-semibold text-[var(--text-secondary)] mb-1.5">Daily Salah (Prayer)</label>
                        <select id="prayer_frequency" name="prayer_frequency" class="w-full h-11 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs focus:ring-2 focus:ring-[var(--accent-primary)] outline-none">
                            <option value="">Select Prayer Frequency</option>
                            <option value="5x_daily" {{ old('prayer_frequency', $profile->prayer_frequency) === '5x_daily' ? 'selected' : '' }}>Always 5x daily</option>
                            <option value="occasional" {{ old('prayer_frequency', $profile->prayer_frequency) === 'occasional' ? 'selected' : '' }}>Most prayers / Striving to improve</option>
                            <option value="rarely" {{ old('prayer_frequency', $profile->prayer_frequency) === 'rarely' ? 'selected' : '' }}>Jummah &amp; Eid only</option>
                        </select>
                    </div>

                    @if($user->gender === 'female')
                        <div>
                            <label for="hijab_niqab_practice" class="block text-xs font-semibold text-[var(--text-secondary)] mb-1.5">Hijab / Modesty Practice</label>
                            <select id="hijab_niqab_practice" name="hijab_niqab_practice" class="w-full h-11 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs focus:ring-2 focus:ring-[var(--accent-primary)] outline-none">
                                <option value="">Select Practice</option>
                                <option value="hijab" {{ old('hijab_niqab_practice', $profile->hijab_niqab_practice) === 'hijab' ? 'selected' : '' }}>Observes Hijab</option>
                                <option value="niqab" {{ old('hijab_niqab_practice', $profile->hijab_niqab_practice) === 'niqab' ? 'selected' : '' }}>Observes Niqab</option>
                                <option value="modest_without_hijab" {{ old('hijab_niqab_practice', $profile->hijab_niqab_practice) === 'modest_without_hijab' ? 'selected' : '' }}>Modest clothing without Hijab</option>
                                <option value="planning_to_wear" {{ old('hijab_niqab_practice', $profile->hijab_niqab_practice) === 'planning_to_wear' ? 'selected' : '' }}>Planning to wear in future</option>
                            </select>
                        </div>
                    @else
                        <div>
                            <label for="beard_practice" class="block text-xs font-semibold text-[var(--text-secondary)] mb-1.5">Sunnah Beard Practice</label>
                            <select id="beard_practice" name="beard_practice" class="w-full h-11 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs focus:ring-2 focus:ring-[var(--accent-primary)] outline-none">
                                <option value="">Select Practice</option>
                                <option value="full_sunnah_beard" {{ old('beard_practice', $profile->beard_practice) === 'full_sunnah_beard' ? 'selected' : '' }}>Full Sunnah beard</option>
                                <option value="trimmed_beard" {{ old('beard_practice', $profile->beard_practice) === 'trimmed_beard' ? 'selected' : '' }}>Trimmed / Neat beard</option>
                                <option value="clean_shaven" {{ old('beard_practice', $profile->beard_practice) === 'clean_shaven' ? 'selected' : '' }}>Clean shaven</option>
                            </select>
                        </div>
                    @endif

                    <div>
                        <label for="quran_knowledge" class="block text-xs font-semibold text-[var(--text-secondary)] mb-1.5">Qur'an Knowledge</label>
                        <select id="quran_knowledge" name="quran_knowledge" class="w-full h-11 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs focus:ring-2 focus:ring-[var(--accent-primary)] outline-none">
                            <option value="">Select Knowledge</option>
                            <option value="hafiz" {{ old('quran_knowledge', $profile->quran_knowledge) === 'hafiz' ? 'selected' : '' }}>Hafiz / Hafiza (Complete memorization)</option>
                            <option value="fluent_reciter" {{ old('quran_knowledge', $profile->quran_knowledge) === 'fluent_reciter' ? 'selected' : '' }}>Fluent reciter with Tajweed</option>
                            <option value="basic_reader" {{ old('quran_knowledge', $profile->quran_knowledge) === 'basic_reader' ? 'selected' : '' }}>Basic Arabic reader</option>
                            <option value="learning" {{ old('quran_knowledge', $profile->quran_knowledge) === 'learning' ? 'selected' : '' }}>Currently learning</option>
                        </select>
                    </div>

                    <div>
                        <label for="halal_dietary_adherence" class="block text-xs font-semibold text-[var(--text-secondary)] mb-1.5">Halal Dietary Standard</label>
                        <select id="halal_dietary_adherence" name="halal_dietary_adherence" class="w-full h-11 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs focus:ring-2 focus:ring-[var(--accent-primary)] outline-none">
                            <option value="strictly_halal" {{ old('halal_dietary_adherence', $profile->halal_dietary_adherence) === 'strictly_halal' ? 'selected' : '' }}>Strictly Halal (Hand-slaughtered / Zabiha only)</option>
                            <option value="halal_meat_only" {{ old('halal_dietary_adherence', $profile->halal_dietary_adherence) === 'halal_meat_only' ? 'selected' : '' }}>Halal meat &amp; Seafood</option>
                            <option value="flexible" {{ old('halal_dietary_adherence', $profile->halal_dietary_adherence) === 'flexible' ? 'selected' : '' }}>Flexible</option>
                        </select>
                    </div>

                    <div>
                        <label for="mosque_attendance" class="block text-xs font-semibold text-[var(--text-secondary)] mb-1.5">Mosque Attendance</label>
                        <select id="mosque_attendance" name="mosque_attendance" class="w-full h-11 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs focus:ring-2 focus:ring-[var(--accent-primary)] outline-none">
                            <option value="daily" {{ old('mosque_attendance', $profile->mosque_attendance) === 'daily' ? 'selected' : '' }}>Daily prayers at the mosque</option>
                            <option value="weekly_jummah" {{ old('mosque_attendance', $profile->mosque_attendance) === 'weekly_jummah' ? 'selected' : '' }}>Weekly Jummah prayer</option>
                            <option value="occasional" {{ old('mosque_attendance', $profile->mosque_attendance) === 'occasional' ? 'selected' : '' }}>Occasional / Eid</option>
                        </select>
                    </div>
                </div>

                <!-- Guardian Involvement Toggle -->
                <div class="pt-4 border-t border-[var(--border-subtle)]">
                    <label class="flex items-start gap-3 text-xs text-[var(--text-primary)] cursor-pointer">
                        <input type="checkbox" name="wali_required" value="1" {{ old('wali_required', $profile->wali_required) ? 'checked' : '' }} class="mt-0.5 rounded-[var(--radius-xs)] text-[var(--accent-primary)] focus:ring-[var(--accent-primary)]">
                        <div>
                            <strong class="text-[var(--text-primary)] block font-bold">Require Guardian (Wali) approval for communications</strong>
                            <span class="text-[var(--text-secondary)] leading-relaxed">
                                Suitors cannot send direct messages until your linked guardian has reviewed and accepted their inquiry.
                            </span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- 2. Personal, Education & Profession -->
            <div class="card-ledger p-6 sm:p-7 bg-[var(--bg-surface)] space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-[var(--border-subtle)]">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[var(--secondary-text)] block">Section 2</span>
                        <h2 class="text-lg font-serif font-bold text-[var(--text-primary)]">Education, Career &amp; Location</h2>
                    </div>
                    <span class="text-[11px] text-[var(--text-muted)]">Background</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="height_cm" class="block text-xs font-semibold text-[var(--text-secondary)] mb-1.5">Height (cm)</label>
                        <input type="number" id="height_cm" name="height_cm" value="{{ old('height_cm', $profile->height_cm) }}" min="120" max="230" placeholder="e.g. 175"
                               class="w-full h-11 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs focus:ring-2 focus:ring-[var(--accent-primary)] outline-none">
                    </div>
                    <div>
                        <label for="city" class="block text-xs font-semibold text-[var(--text-secondary)] mb-1.5">City / District</label>
                        <input type="text" id="city" name="city" value="{{ old('city', $profile->city) }}" placeholder="e.g. London, Toronto, Dubai"
                               class="w-full h-11 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs focus:ring-2 focus:ring-[var(--accent-primary)] outline-none">
                    </div>
                    <div>
                        <label for="country" class="block text-xs font-semibold text-[var(--text-secondary)] mb-1.5">Country of Residence</label>
                        <input type="text" id="country" name="country" value="{{ old('country', $profile->country) }}" placeholder="e.g. United Kingdom, Canada, UAE"
                               class="w-full h-11 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs focus:ring-2 focus:ring-[var(--accent-primary)] outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="education_level" class="block text-xs font-semibold text-[var(--text-secondary)] mb-1.5">Education</label>
                        <input type="text" id="education_level" name="education_level" value="{{ old('education_level', $profile->education_level) }}" placeholder="e.g. B.Sc in Engineering, BBA, Islamic Studies"
                               class="w-full h-11 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs focus:ring-2 focus:ring-[var(--accent-primary)] outline-none">
                    </div>
                    <div>
                        <label for="profession" class="block text-xs font-semibold text-[var(--text-secondary)] mb-1.5">Profession / Occupation</label>
                        <input type="text" id="profession" name="profession" value="{{ old('profession', $profile->profession) }}" placeholder="e.g. Software Engineer, Teacher, Physician, Business"
                               class="w-full h-11 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs focus:ring-2 focus:ring-[var(--accent-primary)] outline-none">
                    </div>
                </div>

                <div>
                    <label for="bio" class="block text-xs font-semibold text-[var(--text-secondary)] mb-1.5">About You &amp; What You Value in Marriage</label>
                    <textarea id="bio" name="bio" rows="4"
                              class="w-full p-3.5 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs focus:ring-2 focus:ring-[var(--accent-primary)] outline-none leading-relaxed"
                              placeholder="Write a few sincere sentences about your character, daily habits, and what kind of household you hope to build together...">{{ old('bio', $profile->bio) }}</textarea>
                    <p class="text-[11px] text-[var(--text-muted)] mt-1">
                        Concrete, honest details help far more than generic statements.
                    </p>
                </div>
            </div>

            <!-- 3. Family Background & Living Plans -->
            <div class="card-ledger p-6 sm:p-7 bg-[var(--bg-surface)] space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-[var(--border-subtle)]">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[var(--secondary-text)] block">Section 3</span>
                        <h2 class="text-lg font-serif font-bold text-[var(--text-primary)]">Family Context &amp; Household Vision</h2>
                    </div>
                    <span class="text-[11px] text-[var(--text-muted)]">Family</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="family_religiosity" class="block text-xs font-semibold text-[var(--text-secondary)] mb-1.5">Family Practice</label>
                        <select id="family_religiosity" name="family_religiosity" class="w-full h-11 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs focus:ring-2 focus:ring-[var(--accent-primary)] outline-none">
                            <option value="very_practicing" {{ old('family_religiosity', $profile->family_religiosity) === 'very_practicing' ? 'selected' : '' }}>Very practicing</option>
                            <option value="moderate" {{ old('family_religiosity', $profile->family_religiosity) === 'moderate' ? 'selected' : '' }}>Moderate</option>
                            <option value="traditional" {{ old('family_religiosity', $profile->family_religiosity) === 'traditional' ? 'selected' : '' }}>Traditional</option>
                        </select>
                    </div>

                    <div>
                        <label for="desired_family_structure" class="block text-xs font-semibold text-[var(--text-secondary)] mb-1.5">Living Arrangement After Marriage</label>
                        <select id="desired_family_structure" name="desired_family_structure" class="w-full h-11 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs focus:ring-2 focus:ring-[var(--accent-primary)] outline-none">
                            <option value="independent" {{ old('desired_family_structure', $profile->desired_family_structure) === 'independent' ? 'selected' : '' }}>Independent household</option>
                            <option value="joint_family" {{ old('desired_family_structure', $profile->desired_family_structure) === 'joint_family' ? 'selected' : '' }}>Joint family arrangement</option>
                        </select>
                    </div>

                    <div>
                        <label for="siblings_count" class="block text-xs font-semibold text-[var(--text-secondary)] mb-1.5">Number of Siblings</label>
                        <input type="number" id="siblings_count" name="siblings_count" value="{{ old('siblings_count', $profile->siblings_count ?? 0) }}" min="0" max="20"
                               class="w-full h-11 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs focus:ring-2 focus:ring-[var(--accent-primary)] outline-none">
                    </div>
                </div>
            </div>

            <!-- 4. Partner Preferences -->
            @php $prefs = $profile->partner_preferences ?? []; @endphp
            <div class="card-ledger p-6 sm:p-7 bg-[var(--bg-surface)] space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-[var(--border-subtle)]">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[var(--secondary-text)] block">Section 4</span>
                        <h2 class="text-lg font-serif font-bold text-[var(--text-primary)]">What You Look for in a Spouse</h2>
                    </div>
                    <span class="text-[11px] text-[var(--text-muted)]">Preferences</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1.5">Preferred Age Range</label>
                        <div class="flex items-center gap-2">
                            <input type="number" name="pref_age_min" value="{{ old('pref_age_min', $prefs['age_min'] ?? 20) }}" min="18" max="80" placeholder="From"
                                   class="w-full h-11 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs focus:ring-2 focus:ring-[var(--accent-primary)] outline-none">
                            <span class="text-[var(--text-muted)] text-xs">to</span>
                            <input type="number" name="pref_age_max" value="{{ old('pref_age_max', $prefs['age_max'] ?? 35) }}" min="18" max="80" placeholder="To"
                                   class="w-full h-11 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs focus:ring-2 focus:ring-[var(--accent-primary)] outline-none">
                        </div>
                    </div>

                    <div>
                        <label for="pref_location" class="block text-xs font-semibold text-[var(--text-secondary)] mb-1.5">Preferred Location</label>
                        <input type="text" id="pref_location" name="pref_location" value="{{ old('pref_location', $prefs['preferred_location'] ?? '') }}" placeholder="e.g. UK, Canada, Gulf, Open to relocation"
                               class="w-full h-11 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs focus:ring-2 focus:ring-[var(--accent-primary)] outline-none">
                    </div>
                </div>
            </div>

            <!-- Submit Bar -->
            <div class="flex items-center justify-between pt-2">
                <a href="{{ route('profile.show') }}" class="btn-secondary text-xs h-12 inline-flex items-center">
                    Cancel without saving
                </a>
                <button type="submit" class="btn-primary text-sm font-bold px-8 h-12">
                    Save Profile Changes
                </button>
            </div>
        </form>

    </div>
</div>
@endsection

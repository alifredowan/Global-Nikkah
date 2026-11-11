@extends('layouts.app')

@section('title', 'Account Settings — Global Nikkah')
@section('page_title', 'Account Settings')

@section('content')
<div class="py-8 bg-[var(--bg-canvas)] min-h-screen">
    <div class="{{ (Auth::user()->isAdmin() || Auth::user()->isModerator()) ? 'px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto' : 'max-w-5xl mx-auto px-4 sm:px-6 lg:px-8' }} space-y-8">

        <!-- Header Ledger Card -->
        <div class="card-ledger pattern-header p-6 sm:p-8 bg-[var(--bg-surface)]">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-16 mihrab-arch bg-[var(--bg-subtle)] border border-[var(--border-default)] flex items-center justify-center text-[var(--brand-primary)] text-2xl font-serif font-bold shadow-2xs shrink-0">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="space-y-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h1 class="font-serif text-2xl sm:text-3xl font-semibold text-[var(--text-primary)]">
                                {{ $user->name }}
                            </h1>
                            <span class="badge-status text-[10px] {{ $user->isSuperAdmin() ? 'text-[var(--accent-text)] bg-[var(--accent-subtle)] border-[var(--accent-border)]' : ($user->isModerator() ? 'text-[var(--color-info)] bg-[var(--color-info-bg)] border-[var(--color-info-border)]' : 'text-[var(--color-success)] bg-[var(--color-success-bg)] border-[var(--color-success-border)]') }}">
                                {{ $user->isSuperAdmin() ? 'Super Admin' : ($user->isModerator() ? 'Moderator' : ($user->isWali() ? 'Guardian (Wali)' : 'Seeker')) }}
                            </span>
                        </div>
                        <p class="text-xs text-[var(--text-secondary)] leading-relaxed">
                            Manage your account credentials, security passphrase, and family privacy preferences.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2 self-start sm:self-center shrink-0">
                    @if($user->isAdmin() || $user->isModerator())
                        <a href="{{ route('admin.dashboard') }}" class="btn-secondary h-10 px-4 text-xs font-semibold inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                            <span>Admin Portal</span>
                        </a>
                    @else
                        <a href="{{ route('profile.show') }}" class="btn-secondary h-10 px-4 text-xs font-semibold inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <span>View Biodata</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-[var(--radius-md)] bg-[var(--color-success-bg)] border border-[var(--color-success-border)] text-xs text-[var(--color-success)] flex items-center gap-2.5">
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-[var(--radius-md)] bg-[var(--color-danger-bg)] border border-[var(--color-danger-border)] text-xs text-[var(--color-danger)] flex items-center gap-2.5">
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: Account & Personal Info -->
            <div class="lg:col-span-2 space-y-8">
                <!-- 1. Personal & Contact Info Form -->
                <div class="card-ledger p-6 sm:p-8 bg-[var(--bg-surface)]">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-[var(--border-subtle)]">
                        <div class="w-8 h-8 rounded-[var(--radius-sm)] bg-[var(--accent-subtle)] text-[var(--accent-text)] flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                        <div>
                            <h2 class="font-serif text-lg font-semibold text-[var(--text-primary)]">Personal & Account Information</h2>
                            <p class="text-xs text-[var(--text-muted)] mt-0.5">Update your legal name, registered email address, and demographic records.</p>
                        </div>
                    </div>

                    <form action="{{ route('settings.profile.update') }}" method="POST" class="space-y-5">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="name" class="block text-xs font-semibold text-[var(--text-primary)] mb-1.5">
                                    Full Name <span class="text-[var(--color-danger)]">*</span>
                                </label>
                                <input type="text"
                                       id="name"
                                       name="name"
                                       value="{{ old('name', $user->name) }}"
                                       required
                                       class="w-full h-11 px-3.5 rounded-[var(--radius-md)] border border-[var(--border-default)] bg-[var(--bg-canvas)] text-sm text-[var(--text-primary)] focus:outline-none focus:border-[var(--brand-primary)] focus:ring-1 focus:ring-[var(--brand-primary)] transition">
                            </div>

                            <div>
                                <label for="email" class="block text-xs font-semibold text-[var(--text-primary)] mb-1.5">
                                    Email Address <span class="text-[var(--color-danger)]">*</span>
                                </label>
                                <input type="email"
                                       id="email"
                                       name="email"
                                       value="{{ old('email', $user->email) }}"
                                       required
                                       class="w-full h-11 px-3.5 rounded-[var(--radius-md)] border border-[var(--border-default)] bg-[var(--bg-canvas)] text-sm text-[var(--text-primary)] focus:outline-none focus:border-[var(--brand-primary)] focus:ring-1 focus:ring-[var(--brand-primary)] transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="phone" class="block text-xs font-semibold text-[var(--text-primary)] mb-1.5">
                                    Phone Number
                                </label>
                                <input type="text"
                                       id="phone"
                                       name="phone"
                                       value="{{ old('phone', $user->phone) }}"
                                       placeholder="+44 7700 900077"
                                       class="w-full h-11 px-3.5 rounded-[var(--radius-md)] border border-[var(--border-default)] bg-[var(--bg-canvas)] text-sm text-[var(--text-primary)] placeholder:text-[var(--text-muted)] focus:outline-none focus:border-[var(--brand-primary)] focus:ring-1 focus:ring-[var(--brand-primary)] transition">
                            </div>

                            <div>
                                <span class="block text-xs font-semibold text-[var(--text-primary)] mb-1.5">
                                    System Role
                                </span>
                                <div class="w-full h-11 px-3.5 rounded-[var(--radius-md)] border border-[var(--border-subtle)] bg-[var(--bg-subtle)] text-[var(--text-secondary)] text-sm flex items-center justify-between">
                                    <span class="capitalize">{{ str_replace('_', ' ', $user->role) }}</span>
                                    <span class="text-[11px] text-[var(--text-muted)]">Permanent</span>
                                </div>
                            </div>
                        </div>

                        @if($user->isSeeker())
                            <div class="pt-5 border-t border-[var(--border-subtle)] space-y-5">
                                <h3 class="font-serif text-base font-semibold text-[var(--text-primary)]">Demographics &amp; Location</h3>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                                    <div>
                                        <label for="gender" class="block text-xs font-semibold text-[var(--text-primary)] mb-1.5">
                                            Gender
                                        </label>
                                        <select id="gender" name="gender" class="w-full h-11 px-3.5 rounded-[var(--radius-md)] border border-[var(--border-default)] bg-[var(--bg-canvas)] text-sm text-[var(--text-primary)] focus:outline-none focus:border-[var(--brand-primary)]">
                                            <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Brother (Groom)</option>
                                            <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Sister (Bride)</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="dob" class="block text-xs font-semibold text-[var(--text-primary)] mb-1.5">
                                            Date of Birth (dd/mm/yyyy)
                                        </label>
                                        <input type="text"
                                               id="dob"
                                               name="dob"
                                               value="{{ old('dob', $user->dob ? $user->dob->format('d/m/Y') : '') }}"
                                               inputmode="numeric"
                                               placeholder="dd/mm/yyyy"
                                               maxlength="10"
                                               pattern="\d{2}/\d{2}/\d{4}"
                                               x-data
                                               x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '').replace(/^(\d{2})(\d)/, '$1/$2').replace(/^(\d{2})\/(\d{2})(\d)/, '$1/$2/$3').slice(0, 10)"
                                               class="w-full h-11 px-3.5 rounded-[var(--radius-md)] border border-[var(--border-default)] bg-[var(--bg-canvas)] text-sm text-[var(--text-primary)] focus:outline-none focus:border-[var(--brand-primary)]">
                                    </div>

                                    <div>
                                        <label for="marital_status" class="block text-xs font-semibold text-[var(--text-primary)] mb-1.5">
                                            Marital Status
                                        </label>
                                        <select id="marital_status" name="marital_status" class="w-full h-11 px-3.5 rounded-[var(--radius-md)] border border-[var(--border-default)] bg-[var(--bg-canvas)] text-sm text-[var(--text-primary)] focus:outline-none focus:border-[var(--brand-primary)]">
                                            <option value="never_married" {{ old('marital_status', $user->marital_status) === 'never_married' ? 'selected' : '' }}>Never Married</option>
                                            <option value="divorced" {{ old('marital_status', $user->marital_status) === 'divorced' ? 'selected' : '' }}>Divorced</option>
                                            <option value="widowed" {{ old('marital_status', $user->marital_status) === 'widowed' ? 'selected' : '' }}>Widowed</option>
                                            <option value="annulled" {{ old('marital_status', $user->marital_status) === 'annulled' ? 'selected' : '' }}>Annulled</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label for="city" class="block text-xs font-semibold text-[var(--text-primary)] mb-1.5">
                                            City
                                        </label>
                                        <input type="text"
                                               id="city"
                                               name="city"
                                               value="{{ old('city', $profile?->city) }}"
                                               placeholder="e.g. London, Toronto, Dubai"
                                               class="w-full h-11 px-3.5 rounded-[var(--radius-md)] border border-[var(--border-default)] bg-[var(--bg-canvas)] text-sm text-[var(--text-primary)] placeholder:text-[var(--text-muted)] focus:outline-none focus:border-[var(--brand-primary)]">
                                    </div>

                                    <div>
                                        <label for="country" class="block text-xs font-semibold text-[var(--text-primary)] mb-1.5">
                                            Country
                                        </label>
                                        <input type="text"
                                               id="country"
                                               name="country"
                                               value="{{ old('country', $profile?->country) }}"
                                               placeholder="e.g. United Kingdom, Canada, UAE"
                                               class="w-full h-11 px-3.5 rounded-[var(--radius-md)] border border-[var(--border-default)] bg-[var(--bg-canvas)] text-sm text-[var(--text-primary)] placeholder:text-[var(--text-muted)] focus:outline-none focus:border-[var(--brand-primary)]">
                                    </div>
                                </div>

                                <div>
                                    <label for="bio" class="block text-xs font-semibold text-[var(--text-primary)] mb-1.5">
                                        Personal Introduction / Short Bio
                                    </label>
                                    <textarea id="bio"
                                              name="bio"
                                              rows="3"
                                              placeholder="A brief introduction to your character, religious practice, and expectations..."
                                              class="w-full p-3.5 rounded-[var(--radius-md)] border border-[var(--border-default)] bg-[var(--bg-canvas)] text-sm text-[var(--text-primary)] placeholder:text-[var(--text-muted)] focus:outline-none focus:border-[var(--brand-primary)]">{{ old('bio', $profile?->bio) }}</textarea>
                                </div>
                            </div>
                        @endif

                        <div class="pt-4 border-t border-[var(--border-subtle)] flex items-center justify-end">
                            <button type="submit" class="btn-primary h-11 px-6 text-xs font-semibold tracking-wide cursor-pointer">
                                Save Account Details
                            </button>
                        </div>
                    </form>

                    <div class="mt-6 border-t border-[var(--border-subtle)] pt-5">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-xs font-semibold text-[var(--text-primary)]">Revoke API Sessions</h3>
                                <p class="mt-0.5 text-[11px] leading-relaxed text-[var(--text-muted)]">Sign out third-party devices or integrations if you suspect credentials were exposed.</p>
                            </div>
                            <form action="{{ route('settings.api-sessions.revoke') }}" method="POST" class="shrink-0" onsubmit="return confirm('Revoke all API sessions?')">
                                @csrf
                                <button type="submit" class="h-9 px-3.5 rounded-[var(--radius-md)] border border-[var(--color-danger-border)] bg-[var(--color-danger-bg)] text-[11px] font-semibold text-[var(--color-danger)] transition hover:opacity-90 cursor-pointer">
                                    Revoke All
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- 2. Password & Security Form -->
                <div class="card-ledger p-6 sm:p-8 bg-[var(--bg-surface)]">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-[var(--border-subtle)]">
                        <div class="w-8 h-8 rounded-[var(--radius-sm)] bg-[var(--accent-subtle)] text-[var(--accent-text)] flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </div>
                        <div>
                            <h2 class="font-serif text-lg font-semibold text-[var(--text-primary)]">Security Passphrase</h2>
                            <p class="text-xs text-[var(--text-muted)] mt-0.5">Update your password regularly to protect your confidential profile records.</p>
                        </div>
                    </div>

                    <form action="{{ route('settings.password.update') }}" method="POST" class="space-y-5">
                        @csrf

                        <div>
                            <label for="current_password" class="block text-xs font-semibold text-[var(--text-primary)] mb-1.5">
                                Current Password <span class="text-[var(--color-danger)]">*</span>
                            </label>
                            <div class="relative" x-data="{ showCurrent: false }">
                                <input :type="showCurrent ? 'text' : 'password'"
                                       id="current_password"
                                       name="current_password"
                                       required
                                       placeholder="Enter current password"
                                       class="w-full h-11 px-3.5 pr-11 rounded-[var(--radius-md)] border border-[var(--border-default)] bg-[var(--bg-canvas)] text-sm text-[var(--text-primary)] focus:outline-none focus:border-[var(--brand-primary)]">
                                <button type="button" @click="showCurrent = !showCurrent"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-[var(--text-muted)] hover:text-[var(--text-primary)] cursor-pointer p-1"
                                        :title="showCurrent ? 'Hide password' : 'View password'"
                                        tabindex="-1">
                                    <svg x-show="!showCurrent" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    <svg x-show="showCurrent" style="display: none;" class="w-4 h-4 text-[var(--brand-primary)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="password" class="block text-xs font-semibold text-[var(--text-primary)] mb-1.5">
                                    New Password <span class="text-[var(--color-danger)]">*</span>
                                </label>
                                <div class="relative" x-data="{ showNew: false }">
                                    <input :type="showNew ? 'text' : 'password'"
                                           id="password"
                                           name="password"
                                           required
                                           minlength="8"
                                           placeholder="At least 8 characters"
                                           class="w-full h-11 px-3.5 pr-11 rounded-[var(--radius-md)] border border-[var(--border-default)] bg-[var(--bg-canvas)] text-sm text-[var(--text-primary)] focus:outline-none focus:border-[var(--brand-primary)]">
                                    <button type="button" @click="showNew = !showNew"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-[var(--text-muted)] hover:text-[var(--text-primary)] cursor-pointer p-1"
                                            :title="showNew ? 'Hide password' : 'View password'"
                                            tabindex="-1">
                                        <svg x-show="!showNew" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        <svg x-show="showNew" style="display: none;" class="w-4 h-4 text-[var(--brand-primary)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-xs font-semibold text-[var(--text-primary)] mb-1.5">
                                    Confirm New Password <span class="text-[var(--color-danger)]">*</span>
                                </label>
                                <div class="relative" x-data="{ showConfirm: false }">
                                    <input :type="showConfirm ? 'text' : 'password'"
                                           id="password_confirmation"
                                           name="password_confirmation"
                                           required
                                           minlength="8"
                                           placeholder="Repeat new password"
                                           class="w-full h-11 px-3.5 pr-11 rounded-[var(--radius-md)] border border-[var(--border-default)] bg-[var(--bg-canvas)] text-sm text-[var(--text-primary)] focus:outline-none focus:border-[var(--brand-primary)]">
                                    <button type="button" @click="showConfirm = !showConfirm"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-[var(--text-muted)] hover:text-[var(--text-primary)] cursor-pointer p-1"
                                            :title="showConfirm ? 'Hide password' : 'View password'"
                                            tabindex="-1">
                                        <svg x-show="!showConfirm" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        <svg x-show="showConfirm" style="display: none;" class="w-4 h-4 text-[var(--brand-primary)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-[var(--border-subtle)] flex items-center justify-end">
                            <button type="submit" class="btn-secondary h-11 px-6 text-xs font-semibold cursor-pointer">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column: Preferences & Fast Links -->
            <div class="space-y-6">
                <!-- Account Status Summary Card -->
                <div class="card-ledger p-6 bg-[var(--bg-surface)] border-l-2 border-l-[var(--accent-gold)] space-y-4">
                    <span class="text-[10px] font-semibold uppercase tracking-wider text-[var(--accent-text)]">Account Status</span>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-[var(--text-muted)]">Membership</p>
                            <p class="text-sm font-semibold text-[var(--brand-primary)] flex items-center gap-1.5 mt-0.5">
                                <span class="w-2 h-2 rounded-full bg-[var(--brand-primary)]"></span>
                                <span>Active &amp; Verified</span>
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-[var(--text-muted)]">Member Since</p>
                            <p class="text-sm font-semibold text-[var(--text-primary)] mt-0.5">{{ $user->created_at->format('M Y') }}</p>
                        </div>
                    </div>
                    <div class="pt-3 border-t border-[var(--border-subtle)] text-[11px] text-[var(--text-muted)]">
                        Account ID: <span class="font-mono text-[var(--text-secondary)] font-medium">#NC-{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </div>
                </div>

                @if($user->isSeeker())
                    <!-- Privacy & Modesty Preferences for Seekers -->
                    <div class="card-ledger p-6 bg-[var(--bg-surface)] space-y-4">
                        <div class="flex items-center gap-2.5 pb-3 border-b border-[var(--border-subtle)]">
                            <svg class="w-4 h-4 text-[var(--brand-primary)] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            <h3 class="font-serif text-base font-semibold text-[var(--text-primary)]">Privacy & Haya Preferences</h3>
                        </div>

                        <form action="{{ route('settings.preferences.update') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="section" value="privacy">

                            <!-- Incognito Browsing -->
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <label for="is_incognito" class="text-xs font-semibold text-[var(--text-primary)] block">
                                        Incognito Profile Browsing
                                    </label>
                                    <span class="text-[11px] text-[var(--text-muted)] block mt-0.5 leading-relaxed">
                                        Browse candidate profiles anonymously without appearing in their &ldquo;Who Viewed You&rdquo; list.
                                    </span>
                                </div>
                                <input type="checkbox"
                                       id="is_incognito"
                                       name="is_incognito"
                                       value="1"
                                       {{ $user->is_incognito ? 'checked' : '' }}
                                       class="mt-1 w-4 h-4 text-[var(--brand-primary)] rounded border-[var(--border-default)] focus:ring-[var(--brand-primary)]">
                            </div>

                            <!-- Wali Requirement -->
                            <div class="flex items-start justify-between gap-3 pt-3 border-t border-[var(--border-subtle)]">
                                <div>
                                    <label for="wali_required" class="text-xs font-semibold text-[var(--text-primary)] block">
                                        Require Guardian (Wali) Clearance
                                    </label>
                                    <span class="text-[11px] text-[var(--text-muted)] block mt-0.5 leading-relaxed">
                                        Require introductions and chats to be cleared by your linked guardian first.
                                    </span>
                                </div>
                                <input type="checkbox"
                                       id="wali_required"
                                       name="wali_required"
                                       value="1"
                                       {{ $profile?->wali_required ? 'checked' : '' }}
                                       class="mt-1 w-4 h-4 text-[var(--brand-primary)] rounded border-[var(--border-default)] focus:ring-[var(--brand-primary)]">
                            </div>

                            <button type="submit" class="btn-secondary w-full h-10 text-xs font-semibold cursor-pointer">
                                Save Privacy Settings
                            </button>
                        </form>
                    </div>
                @endif

                <!-- Cookie & Privacy Preferences (Accessible Preferences Modal Trigger) -->
                <div class="card-ledger p-6 bg-[var(--bg-surface)] space-y-4">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-[var(--border-subtle)]">
                        <svg class="w-4 h-4 text-[var(--brand-primary)] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                            <path d="M12 2a10 10 0 1 0 10 10 4 4 0 0 1-5-5 4 4 0 0 1-5-5"/>
                            <path d="M8.5 8.5v.01"/>
                            <path d="M16 15.5v.01"/>
                            <path d="M12 12v.01"/>
                            <path d="M11 17v.01"/>
                            <path d="M7 13v.01"/>
                        </svg>
                        <div>
                            <h3 class="font-serif text-base font-semibold text-[var(--text-primary)]">Cookie &amp; Tracking Preferences</h3>
                            <p class="text-[11px] text-[var(--text-muted)] mt-0.5">Control how essential, analytics, and marketing cookies are stored on your device.</p>
                        </div>
                    </div>
                    <p class="text-xs text-[var(--text-secondary)] leading-relaxed">
                        Essential cookies maintain your login session and security tokens. You may inspect or update your analytics and marketing cookie consent choices at any time.
                    </p>
                    <div class="pt-1">
                        <button type="button"
                                data-open-cookie-settings
                                class="btn-secondary w-full h-10 text-xs font-semibold flex items-center justify-center gap-2 cursor-pointer">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                                <circle cx="12" cy="12" r="3"/>
                                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                            </svg>
                            <span>Manage Cookie Preferences</span>
                        </button>
                    </div>
                </div>

                <!-- Notification Preferences -->
                <div class="card-ledger p-6 bg-[var(--bg-surface)] space-y-4">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-[var(--border-subtle)]">
                        <svg class="w-4 h-4 text-[var(--brand-primary)] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                        <div>
                            <h3 class="font-serif text-base font-semibold text-[var(--text-primary)]">Notifications</h3>
                            <p class="text-[11px] text-[var(--text-muted)] mt-0.5">Choose which updates reach your inbox.</p>
                        </div>
                    </div>
                    @php($notificationPreferences = $user->notificationPreferences())
                    <form action="{{ route('settings.preferences.update') }}" method="POST" class="space-y-3">
                        @csrf
                        <input type="hidden" name="section" value="notifications">
                        @foreach([
                            'email_notifications' => 'Email notifications for activity',
                            'interest_notifications' => 'Introduction requests and decisions',
                            'message_notifications' => 'New chaperoned messages',
                            'wali_notifications' => 'Guardian (Wali) status updates',
                            'saved_search_notifications' => 'Saved-search match alerts',
                            'marriage_notifications' => 'Nikah milestone updates',
                        ] as $preferenceKey => $preferenceLabel)
                            <label class="flex items-center justify-between gap-3 text-xs text-[var(--text-secondary)] cursor-pointer">
                                <span>{{ $preferenceLabel }}</span>
                                <input type="checkbox" name="{{ $preferenceKey }}" value="1" {{ $notificationPreferences[$preferenceKey] ? 'checked' : '' }} class="h-4 w-4 rounded border-[var(--border-default)] text-[var(--brand-primary)] focus:ring-[var(--brand-primary)]">
                            </label>
                        @endforeach
                        <button type="submit" class="btn-secondary w-full h-10 text-xs font-semibold mt-2 cursor-pointer">
                            Save Notification Preferences
                        </button>
                    </form>
                </div>

                @if($user->isSuperAdmin() || $user->hasPermission('manage_settings'))
                    <!-- Admin Link to Platform Policies -->
                    <div class="card-ledger p-6 bg-[var(--bg-surface)] space-y-3">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[var(--accent-text)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                            <h3 class="font-serif text-base font-semibold text-[var(--text-primary)]">Global Platform Policies</h3>
                        </div>
                        <p class="text-xs text-[var(--text-muted)] leading-relaxed">
                            Configure platform-wide settings such as KYC enforcement, image modesty blurring, and safety filters.
                        </p>
                        <a href="{{ route('admin.settings') }}" class="btn-secondary w-full h-10 text-xs font-semibold flex items-center justify-center gap-1.5">
                            <span>Manage Platform Policies</span>
                            <span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>
                @endif

                <!-- Delete Account Card -->
                <div class="card-ledger p-6 bg-[var(--bg-surface)] border-l-2 border-l-[var(--color-danger)] space-y-3">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[var(--color-danger)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                        <h3 class="font-serif text-base font-semibold text-[var(--color-danger)]">Delete Account Permanently</h3>
                    </div>
                    <p class="text-xs text-[var(--text-muted)] leading-relaxed">
                        This permanently removes your profile, photos, messages, and verification records. This action cannot be reversed.
                    </p>
                    <form action="{{ route('account.delete') }}" method="POST" class="space-y-3 pt-1">
                        @csrf
                        <input type="password" name="password" required autocomplete="current-password" placeholder="Confirm your password" class="w-full h-10 px-3 rounded-[var(--radius-md)] border border-[var(--border-default)] bg-[var(--bg-canvas)] text-xs text-[var(--text-primary)] focus:outline-none focus:border-[var(--color-danger)]">
                        <textarea name="reason" required maxlength="500" rows="2" placeholder="Please tell us why you are leaving (required)" class="w-full p-2.5 rounded-[var(--radius-md)] border border-[var(--border-default)] bg-[var(--bg-canvas)] text-xs text-[var(--text-primary)] placeholder:text-[var(--text-muted)] focus:outline-none focus:border-[var(--color-danger)]"></textarea>
                        <label class="flex items-start gap-2 text-[11px] text-[var(--text-muted)] cursor-pointer">
                            <input type="checkbox" name="confirmation" value="1" required class="mt-0.5 rounded border-[var(--border-default)] text-[var(--color-danger)] focus:ring-[var(--color-danger)]">
                            <span>I understand that this permanent deletion cannot be undone.</span>
                        </label>
                        <button type="submit" class="w-full h-10 rounded-[var(--radius-md)] bg-[var(--color-danger)] text-white text-xs font-semibold hover:opacity-90 transition cursor-pointer" onclick="return confirm('Permanently delete your account and personal data? This cannot be undone.')">
                            Permanently Delete Account
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

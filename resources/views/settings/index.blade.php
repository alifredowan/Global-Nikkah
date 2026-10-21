@extends('layouts.app')

@section('title', 'Account Settings - Global Nikkah')
@section('page_title', 'Account Settings')

@section('content')
<div class="py-8 {{ (Auth::user()->isAdmin() || Auth::user()->isModerator()) ? 'px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto' : 'max-w-5xl mx-auto px-4 sm:px-6 lg:px-8' }} space-y-8">

    <!-- Header Banner -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 sm:p-8 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 transition-colors">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-emerald-gradient flex items-center justify-center text-white text-2xl font-bold shadow-lg shadow-emerald-950/20">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white font-heading">{{ $user->name }}</h1>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider {{ $user->isSuperAdmin() ? 'bg-purple-100 text-purple-700 dark:bg-purple-950/60 dark:text-purple-300' : ($user->isModerator() ? 'bg-blue-100 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300') }}">
                        {{ $user->isSuperAdmin() ? '👑 Super Admin' : ($user->isModerator() ? '🛡️ Moderator' : ($user->isWali() ? '🛡️ Guardian (Wali)' : '🧔 Seeker')) }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Manage your personal credentials, contact details, security password, and privacy preferences.</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            @if($user->isAdmin() || $user->isModerator())
                <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold transition flex items-center gap-1.5">
                    <span>⚡</span> Admin Dashboard
                </a>
            @else
                <a href="{{ route('profile.show') }}" class="px-3.5 py-2 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 hover:bg-emerald-100 dark:hover:bg-emerald-900/60 text-emerald-800 dark:text-emerald-300 text-xs font-bold transition flex items-center gap-1.5">
                    <span>👤</span> View Biodata
                </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left: Account & Personal Info -->
        <div class="lg:col-span-2 space-y-8">
            <!-- 1. Personal & Contact Info Form -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 sm:p-7 shadow-xs">
                <div class="flex items-center gap-2 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                    <span class="text-xl">👤</span>
                    <div>
                        <h2 class="text-base font-bold text-slate-900 dark:text-white font-heading">Personal & Account Information</h2>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">Update your account name, email address, and demographic details.</p>
                    </div>
                </div>

                <form action="{{ route('settings.profile.update') }}" method="POST" class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                Full Name <span class="text-rose-500">*</span>
                            </label>
                            <input type="text"
                                   name="name"
                                   value="{{ old('name', $user->name) }}"
                                   required
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs font-medium focus:ring-2 focus:ring-emerald-500 outline-none transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                Email Address <span class="text-rose-500">*</span>
                            </label>
                            <input type="email"
                                   name="email"
                                   value="{{ old('email', $user->email) }}"
                                   required
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs font-medium focus:ring-2 focus:ring-emerald-500 outline-none transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                Phone Number
                            </label>
                            <input type="text"
                                   name="phone"
                                   value="{{ old('phone', $user->phone) }}"
                                   placeholder="+1 555-0199"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs font-medium focus:ring-2 focus:ring-emerald-500 outline-none transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                System Role
                            </label>
                            <div class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 text-slate-600 dark:text-slate-400 text-xs font-semibold flex items-center justify-between">
                                <span class="capitalize">{{ str_replace('_', ' ', $user->role) }}</span>
                                <span class="text-[10px] text-slate-400">Fixed</span>
                            </div>
                        </div>
                    </div>

                    @if($user->isSeeker())
                        <div class="pt-4 border-t border-slate-100 dark:border-slate-800 space-y-4">
                            <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Demographics & Location</h3>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                        Gender
                                    </label>
                                    <select name="gender" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs font-medium focus:ring-2 focus:ring-emerald-500 outline-none">
                                        <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Brother (Male)</option>
                                        <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Sister (Female)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                        Date of Birth
                                    </label>
                                    <input type="date"
                                           name="dob"
                                           value="{{ old('dob', $user->dob ? $user->dob->format('Y-m-d') : '') }}"
                                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs font-medium focus:ring-2 focus:ring-emerald-500 outline-none">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                        Marital Status
                                    </label>
                                    <select name="marital_status" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs font-medium focus:ring-2 focus:ring-emerald-500 outline-none">
                                        <option value="never_married" {{ old('marital_status', $user->marital_status) === 'never_married' ? 'selected' : '' }}>Never Married</option>
                                        <option value="divorced" {{ old('marital_status', $user->marital_status) === 'divorced' ? 'selected' : '' }}>Divorced</option>
                                        <option value="widowed" {{ old('marital_status', $user->marital_status) === 'widowed' ? 'selected' : '' }}>Widowed</option>
                                        <option value="annulled" {{ old('marital_status', $user->marital_status) === 'annulled' ? 'selected' : '' }}>Annulled</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                        City
                                    </label>
                                    <input type="text"
                                           name="city"
                                           value="{{ old('city', $profile?->city) }}"
                                           placeholder="e.g. London, Dallas, Dhaka"
                                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs font-medium focus:ring-2 focus:ring-emerald-500 outline-none">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                        Country
                                    </label>
                                    <input type="text"
                                           name="country"
                                           value="{{ old('country', $profile?->country) }}"
                                           placeholder="e.g. United Kingdom, USA"
                                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs font-medium focus:ring-2 focus:ring-emerald-500 outline-none">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                    About Me / Short Bio
                                </label>
                                <textarea name="bio"
                                          rows="3"
                                          placeholder="A brief intro about your practice and goals..."
                                          class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs font-medium focus:ring-2 focus:ring-emerald-500 outline-none">{{ old('bio', $profile?->bio) }}</textarea>
                            </div>
                        </div>
                    @endif

                    <div class="pt-2">
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-950/20 transition">
                            Save Account Changes
                        </button>
                    </div>
                </form>

                <div class="mt-6 border-t border-slate-100 dark:border-slate-800 pt-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200">Revoke API sessions</h3>
                            <p class="mt-1 text-[11px] leading-relaxed text-slate-500 dark:text-slate-400">Sign out API-connected devices and apps if you suspect a token has been exposed.</p>
                        </div>
                        <form action="{{ route('settings.api-sessions.revoke') }}" method="POST" class="shrink-0" onsubmit="return confirm('Revoke all API sessions?')">
                            @csrf
                            <button type="submit" class="rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-[11px] font-bold text-rose-700 transition hover:bg-rose-100 dark:border-rose-900/60 dark:bg-rose-950/40 dark:text-rose-300">
                                Revoke all
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- 2. Password & Security Form -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 sm:p-7 shadow-xs">
                <div class="flex items-center gap-2 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                    <span class="text-xl">🔒</span>
                    <div>
                        <h2 class="text-base font-bold text-slate-900 dark:text-white font-heading">Password & Security</h2>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">Change your password to keep your account safe and protected.</p>
                    </div>
                </div>

                <form action="{{ route('settings.password.update') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Current Password <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative" x-data="{ showCurrent: false }">
                            <input :type="showCurrent ? 'text' : 'password'"
                                   name="current_password"
                                   required
                                   placeholder="Enter your current password"
                                   class="w-full px-3.5 py-2.5 pr-11 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs font-medium focus:ring-2 focus:ring-emerald-500 outline-none transition">
                            <button type="button" @click="showCurrent = !showCurrent"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 focus:outline-none cursor-pointer p-1"
                                    :title="showCurrent ? 'Hide password' : 'View password'"
                                    tabindex="-1">
                                <svg x-show="!showCurrent" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showCurrent" style="display: none;" class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                New Password <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative" x-data="{ showNew: false }">
                                <input :type="showNew ? 'text' : 'password'"
                                       name="password"
                                       required
                                       minlength="8"
                                       placeholder="Minimum 8 characters"
                                       class="w-full px-3.5 py-2.5 pr-11 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs font-medium focus:ring-2 focus:ring-emerald-500 outline-none transition">
                                <button type="button" @click="showNew = !showNew"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 focus:outline-none cursor-pointer p-1"
                                        :title="showNew ? 'Hide password' : 'View password'"
                                        tabindex="-1">
                                    <svg x-show="!showNew" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <svg x-show="showNew" style="display: none;" class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                Confirm New Password <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative" x-data="{ showConfirm: false }">
                                <input :type="showConfirm ? 'text' : 'password'"
                                       name="password_confirmation"
                                       required
                                       minlength="8"
                                       placeholder="Repeat new password"
                                       class="w-full px-3.5 py-2.5 pr-11 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs font-medium focus:ring-2 focus:ring-emerald-500 outline-none transition">
                                <button type="button" @click="showConfirm = !showConfirm"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 focus:outline-none cursor-pointer p-1"
                                        :title="showConfirm ? 'Hide password' : 'View password'"
                                        tabindex="-1">
                                    <svg x-show="!showConfirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <svg x-show="showConfirm" style="display: none;" class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-slate-800 dark:bg-slate-700 hover:bg-slate-900 dark:hover:bg-slate-600 text-white font-bold text-xs shadow-xs transition">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column: Preferences & Fast Links -->
        <div class="space-y-6">
            <!-- Account Summary Card -->
            <div class="bg-gradient-to-br from-slate-900 to-slate-950 text-white p-6 rounded-3xl border border-slate-800 shadow-md space-y-4">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-400">Account Status</span>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-slate-400">Status</p>
                        <p class="text-sm font-bold text-emerald-400 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span> Active & Verified
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-400">Member Since</p>
                        <p class="text-sm font-bold text-slate-200">{{ $user->created_at->format('M Y') }}</p>
                    </div>
                </div>
                <div class="pt-3 border-t border-slate-800/80 text-[11px] text-slate-400">
                    Account ID: <span class="font-mono text-slate-300">#NC-{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>
            </div>

            <div class="rounded-3xl border border-rose-200 bg-rose-50 p-6 shadow-xs dark:border-rose-950/80 dark:bg-rose-950/20">
                <div class="flex items-center gap-2">
                    <span class="text-lg">⚠️</span>
                    <h3 class="font-heading text-sm font-bold text-rose-900 dark:text-rose-200">Delete account permanently</h3>
                </div>
                <p class="mt-3 text-xs leading-5 text-rose-800/80 dark:text-rose-300/80">
                    This permanently removes your profile, photos, messages, matches, and verification files. This action cannot be undone.
                </p>
                <form action="{{ route('account.delete') }}" method="POST" class="mt-5 space-y-3">
                    @csrf
                    <input type="password" name="password" required autocomplete="current-password" placeholder="Confirm your password" class="w-full rounded-xl border border-rose-200 bg-white px-3 py-2.5 text-xs text-slate-900 outline-none focus:ring-2 focus:ring-rose-500 dark:border-rose-900 dark:bg-slate-900 dark:text-white">
                    <textarea name="reason" required maxlength="500" rows="2" placeholder="Why are you leaving? (required)" class="w-full rounded-xl border border-rose-200 bg-white px-3 py-2.5 text-xs text-slate-900 outline-none focus:ring-2 focus:ring-rose-500 dark:border-rose-900 dark:bg-slate-900 dark:text-white"></textarea>
                    <label class="flex items-start gap-2 text-[11px] font-semibold leading-4 text-rose-900 dark:text-rose-200">
                        <input type="checkbox" name="confirmation" value="1" required class="mt-0.5 rounded border-rose-300 text-rose-600 focus:ring-rose-500">
                        <span>I understand that permanent deletion cannot be reversed.</span>
                    </label>
                    <button type="submit" class="w-full rounded-xl bg-rose-600 px-4 py-2.5 text-xs font-bold text-white transition hover:bg-rose-700" onclick="return confirm('Permanently delete your account and personal data? This cannot be undone.')">
                        Permanently delete account
                    </button>
                </form>
            </div>

            @if($user->isSuperAdmin() || $user->hasPermission('manage_settings'))
                <!-- Admin Fast Link to Platform Policies -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 shadow-xs space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">⚙️</span>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white font-heading">Global Platform Policies</h3>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                        As an Administrator, you can configure platform-wide settings like KYC enforcement, image auto-blurring, and rate limits.
                    </p>
                    <a href="{{ route('admin.settings') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-purple-50 dark:bg-purple-950/60 hover:bg-purple-100 dark:hover:bg-purple-900/60 text-purple-700 dark:text-purple-300 text-xs font-bold transition">
                        <span>⚙️</span> Manage Platform Policies &rarr;
                    </a>
                </div>
            @endif

            @if($user->isSeeker())
                <!-- Privacy & Modesty Preferences for Seekers -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 shadow-xs space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-slate-800">
                        <span class="text-lg">🛡️</span>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white font-heading">Privacy & Haya Preferences</h3>
                    </div>

                    <form action="{{ route('settings.preferences.update') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="section" value="privacy">

                        <!-- Incognito Browsing -->
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <label for="is_incognito" class="text-xs font-bold text-slate-800 dark:text-slate-200 block">
                                    Incognito Profile Browsing
                                </label>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400 block mt-0.5">
                                    Visit candidate profiles anonymously without appearing in their "Who Viewed You" list.
                                </span>
                            </div>
                            <input type="checkbox"
                                   id="is_incognito"
                                   name="is_incognito"
                                   value="1"
                                   {{ $user->is_incognito ? 'checked' : '' }}
                                   class="mt-1 w-4 h-4 text-emerald-600 rounded-sm border-slate-300 dark:border-slate-700 focus:ring-emerald-500">
                        </div>

                        <!-- Wali Requirement -->
                        <div class="flex items-start justify-between gap-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                            <div>
                                <label for="wali_required" class="text-xs font-bold text-slate-800 dark:text-slate-200 block">
                                    Enforce Guardian (Wali) Clearance
                                </label>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400 block mt-0.5">
                                    Proposals and communications must be cleared by your linked Wali first.
                                </span>
                            </div>
                            <input type="checkbox"
                                   id="wali_required"
                                   name="wali_required"
                                   value="1"
                                   {{ $profile?->wali_required ? 'checked' : '' }}
                                   class="mt-1 w-4 h-4 text-emerald-600 rounded-sm border-slate-300 dark:border-slate-700 focus:ring-emerald-500">
                        </div>

                        <button type="submit" class="w-full py-2 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 hover:bg-emerald-100 dark:hover:bg-emerald-900/60 text-emerald-800 dark:text-emerald-300 font-bold text-xs transition border border-emerald-300 dark:border-emerald-800">
                            Save Privacy Preferences
                        </button>
                    </form>
                </div>
            @endif

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xs dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center gap-2 border-b border-slate-100 pb-3 dark:border-slate-800">
                    <span class="text-lg">🔔</span>
                    <div>
                        <h3 class="font-heading text-sm font-bold text-slate-900 dark:text-white">Notification preferences</h3>
                        <p class="mt-0.5 text-[11px] text-slate-500 dark:text-slate-400">Choose which updates reach you through the platform.</p>
                    </div>
                </div>
                @php($notificationPreferences = $user->notificationPreferences())
                <form action="{{ route('settings.preferences.update') }}" method="POST" class="mt-4 space-y-3">
                    @csrf
                    <input type="hidden" name="section" value="notifications">
                    @foreach([
                        'email_notifications' => 'Email notifications',
                        'interest_notifications' => 'Interest requests and responses',
                        'message_notifications' => 'New chaperoned messages',
                        'wali_notifications' => 'Wali and guardian updates',
                        'saved_search_notifications' => 'Saved-search matches',
                        'marriage_notifications' => 'Nikah milestone updates',
                    ] as $preferenceKey => $preferenceLabel)
                        <label class="flex items-center justify-between gap-3 text-xs font-semibold text-slate-700 dark:text-slate-300">
                            <span>{{ $preferenceLabel }}</span>
                            <input type="checkbox" name="{{ $preferenceKey }}" value="1" {{ $notificationPreferences[$preferenceKey] ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 dark:border-slate-700">
                        </label>
                    @endforeach
                    <button type="submit" class="mt-2 w-full rounded-xl border border-emerald-300 bg-emerald-50 py-2 font-bold text-xs text-emerald-800 transition hover:bg-emerald-100 dark:border-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300">Save notification preferences</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

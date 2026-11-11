@extends('layouts.app')

@section('title', 'Compliance & Cookie Consent - Super Admin')
@section('page_title', 'Compliance & Consent')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800 mb-2">
                <svg class="w-3.5 h-3.5 text-emerald-700 dark:text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                <span>Super Administrator Governance</span>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white font-heading">
                Compliance &amp; Cookie Consent Configuration
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 max-w-2xl leading-relaxed">
                Configure platform-wide cookie consent banner copy, category definitions, policy links, and consent lifecycle. Changes update the public banner dynamically without requiring code redeployments.
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs font-semibold flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0 text-emerald-600 dark:text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Form Area (2 cols) -->
        <div class="lg:col-span-2 space-y-6">
            <form action="{{ route('admin.compliance.update') }}" method="POST" class="space-y-6">
                @csrf

                <!-- 1. Banner Copy -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 shadow-xs space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-slate-800">
                        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                            1. Banner Notice &amp; Headline
                        </h2>
                    </div>

                    <div>
                        <label for="compliance_banner_title" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Banner Heading Title
                        </label>
                        <input type="text"
                               id="compliance_banner_title"
                               name="compliance_banner_title"
                               value="{{ old('compliance_banner_title', $complianceSettings['banner_title']) }}"
                               required
                               maxlength="120"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
                        <span class="text-[11px] text-slate-400 block mt-1">Displayed in Cormorant Garamond font on first visitor load.</span>
                    </div>

                    <div>
                        <label for="compliance_banner_description" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Banner Plain-Language Explanation
                        </label>
                        <textarea id="compliance_banner_description"
                                  name="compliance_banner_description"
                                  rows="3"
                                  required
                                  maxlength="500"
                                  class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs focus:ring-2 focus:ring-emerald-500 outline-none leading-relaxed">{{ old('compliance_banner_description', $complianceSettings['banner_description']) }}</textarea>
                        <span class="text-[11px] text-slate-400 block mt-1">Warm, respectful, human copy. Avoid aggressive legal jargon.</span>
                    </div>
                </div>

                <!-- 2. Cookie Category Definitions -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 shadow-xs space-y-5">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-slate-800">
                        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a10 10 0 1 0 10 10 4 4 0 0 1-5-5 4 4 0 0 1-5-5"/></svg>
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                            2. Consent Category Definitions
                        </h2>
                    </div>

                    <!-- Essential -->
                    <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/80 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Category 1: Strictly Essential (Always Active)</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300">LOCKED</span>
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400 mb-1">Label</label>
                            <input type="text" name="compliance_essential_label" value="{{ old('compliance_essential_label', $complianceSettings['essential_label']) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400 mb-1">Description</label>
                            <input type="text" name="compliance_essential_description" value="{{ old('compliance_essential_description', $complianceSettings['essential_description']) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs">
                        </div>
                    </div>

                    <!-- Analytics -->
                    <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/80 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Category 2: Analytics &amp; Performance</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300">OPT-IN (OFF BY DEFAULT)</span>
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400 mb-1">Label</label>
                            <input type="text" name="compliance_analytics_label" value="{{ old('compliance_analytics_label', $complianceSettings['analytics_label']) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400 mb-1">Description</label>
                            <input type="text" name="compliance_analytics_description" value="{{ old('compliance_analytics_description', $complianceSettings['analytics_description']) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs">
                        </div>
                    </div>

                    <!-- Marketing -->
                    <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/80 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Category 3: Marketing &amp; Referrals</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300">OPT-IN (OFF BY DEFAULT)</span>
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400 mb-1">Label</label>
                            <input type="text" name="compliance_marketing_label" value="{{ old('compliance_marketing_label', $complianceSettings['marketing_label']) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400 mb-1">Description</label>
                            <input type="text" name="compliance_marketing_description" value="{{ old('compliance_marketing_description', $complianceSettings['marketing_description']) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs">
                        </div>
                    </div>
                </div>

                <!-- 3. Policy URLs & Expiry Duration -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 shadow-xs space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-slate-800">
                        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                            3. Legal Links &amp; Expiry Lifecycle
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="compliance_cookie_policy_url" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                                Cookie Policy URL
                            </label>
                            <input type="text"
                                   id="compliance_cookie_policy_url"
                                   name="compliance_cookie_policy_url"
                                   value="{{ old('compliance_cookie_policy_url', $complianceSettings['cookie_policy_url']) }}"
                                   required
                                   class="w-full px-3 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs font-mono">
                        </div>
                        <div>
                            <label for="compliance_privacy_policy_url" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                                Privacy Policy URL
                            </label>
                            <input type="text"
                                   id="compliance_privacy_policy_url"
                                   name="compliance_privacy_policy_url"
                                   value="{{ old('compliance_privacy_policy_url', $complianceSettings['privacy_policy_url']) }}"
                                   required
                                   class="w-full px-3 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs font-mono">
                        </div>
                    </div>

                    <div class="pt-2">
                        <label for="compliance_consent_duration_months" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Consent Validity Period (Months)
                        </label>
                        <input type="number"
                               id="compliance_consent_duration_months"
                               name="compliance_consent_duration_months"
                               value="{{ old('compliance_consent_duration_months', $complianceSettings['consent_duration_months']) }}"
                               required
                               min="1"
                               max="36"
                               class="w-full max-w-xs px-3 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs">
                        <span class="text-[11px] text-slate-400 block mt-1">Default 12 months (GDPR &amp; UK ePrivacy standard). Once saved, visitors will not be prompted again until this period expires.</span>
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-950/20 transition cursor-pointer flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    <span>Save Compliance Parameters</span>
                </button>
            </form>
        </div>

        <!-- Sidebar Summary & Live Preview (1 col) -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 shadow-xs space-y-4">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                        Compliance Checklist
                    </h3>
                </div>
                <div class="space-y-2.5 text-xs text-slate-600 dark:text-slate-400">
                    <div class="flex items-start gap-2">
                        <span class="text-emerald-500 font-bold">✓</span>
                        <span>No dark patterns: Reject button has identical size &amp; prominence to Accept all.</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="text-emerald-500 font-bold">✓</span>
                        <span>Opt-in by default: Non-essential scripts blocked until affirmative consent.</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="text-emerald-500 font-bold">✓</span>
                        <span>Role isolation: Only authenticated Super Administrators can edit this page.</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="text-emerald-500 font-bold">✓</span>
                        <span>Persistent choice: 12-month first-party cookie/storage lifecycle.</span>
                    </div>
                </div>
            </div>

            <div class="bg-slate-50 dark:bg-slate-800/40 rounded-3xl border border-dashed border-slate-300 dark:border-slate-700 p-6 text-center space-y-3">
                <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">
                    Banner Preview
                </h4>
                <div class="card-ledger p-4 bg-[var(--bg-surface)] text-left rounded-xl space-y-2 shadow-xs">
                    <h5 class="font-serif text-sm font-semibold text-[var(--text-primary)]">
                        {{ $complianceSettings['banner_title'] }}
                    </h5>
                    <p class="text-[11px] text-[var(--text-secondary)] line-clamp-3">
                        {{ $complianceSettings['banner_description'] }}
                    </p>
                </div>
                <button type="button"
                        data-open-cookie-settings
                        class="btn-secondary w-full h-9 text-xs font-semibold flex items-center justify-center gap-1.5 cursor-pointer">
                    <span>Test Banner Modal</span>
                </button>
            </div>
        </div>
    </div>

</div>
@endsection


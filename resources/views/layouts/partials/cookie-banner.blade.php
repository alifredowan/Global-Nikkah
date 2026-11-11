@php
    $complianceTitle = \App\Models\PlatformSetting::get('compliance_banner_title', 'Your Privacy Choices');
    $complianceDesc = \App\Models\PlatformSetting::get('compliance_banner_description', 'We use a few cookies to keep you signed in and to understand how the site is used. You choose what we can use.');
    $essentialLabel = \App\Models\PlatformSetting::get('compliance_essential_label', 'Essential Cookies');
    $essentialDesc = \App\Models\PlatformSetting::get('compliance_essential_description', 'Strictly necessary for login sessions, CSRF security tokens, language, and theme choices.');
    $analyticsLabel = \App\Models\PlatformSetting::get('compliance_analytics_label', 'Analytics Cookies');
    $analyticsDesc = \App\Models\PlatformSetting::get('compliance_analytics_description', 'Anonymous visit counts and page performance metrics that help us improve the platform.');
    $marketingLabel = \App\Models\PlatformSetting::get('compliance_marketing_label', 'Marketing & Referral Cookies');
    $marketingDesc = \App\Models\PlatformSetting::get('compliance_marketing_description', 'Measures campaign performance and referrals. We never show external ads or sell your data.');
    $cookiePolicyUrl = \App\Models\PlatformSetting::get('compliance_cookie_policy_url', route('cookie-policy'));
    $privacyPolicyUrl = \App\Models\PlatformSetting::get('compliance_privacy_policy_url', route('privacy'));
    $consentDurationMonths = (int) \App\Models\PlatformSetting::get('compliance_consent_duration_months', 12);
    $consentMaxAgeDays = max(30, $consentDurationMonths * 30);
@endphp

<!-- Global Nikkah Accessible Cookie Consent Banner & Preferences Modal -->
<div id="cookie-consent-container"
     class="cookie-consent-banner fixed inset-x-0 bottom-0 z-40 p-3 sm:p-0 sm:inset-x-auto sm:bottom-5 sm:left-5 sm:max-w-[420px] w-full hidden transition-all duration-200 ease-out opacity-0 translate-y-3"
     role="region"
     aria-label="Cookie consent">

    <!-- 1. Compact Banner View -->
    <div id="cookie-banner-main"
         class="card-ledger p-5 bg-[var(--bg-surface)] border border-[var(--border-default)] shadow-xl rounded-t-2xl sm:rounded-2xl space-y-4"
         role="dialog"
         aria-modal="false"
         aria-labelledby="cookie-banner-title"
         aria-describedby="cookie-banner-desc">

        <div class="space-y-1.5">
            <h2 id="cookie-banner-title" class="font-serif text-base font-semibold text-[var(--text-primary)]">
                {{ $complianceTitle }}
            </h2>
            <p id="cookie-banner-desc" class="text-xs text-[var(--text-secondary)] leading-relaxed">
                {{ $complianceDesc }} Read our
                <a href="{{ $cookiePolicyUrl }}" class="text-[var(--brand-primary)] underline underline-offset-2 hover:opacity-80">Cookie Policy</a>
                and
                <a href="{{ $privacyPolicyUrl }}" class="text-[var(--brand-primary)] underline underline-offset-2 hover:opacity-80">Privacy Policy</a>.
            </p>
        </div>

        <!-- Buttons with Equal Visual Prominence (No Dark Patterns) -->
        <div class="space-y-2">
            <div class="grid grid-cols-2 gap-2">
                <button type="button"
                        id="cookie-btn-reject"
                        class="btn-secondary h-10 px-3 text-xs font-semibold w-full text-center flex items-center justify-center cursor-pointer"
                        title="Accept essential only">
                    Reject non-essential<span class="sr-only"> (Essential only)</span>
                </button>
                <button type="button"
                        id="cookie-btn-accept"
                        class="btn-primary h-10 px-3 text-xs font-semibold w-full text-center flex items-center justify-center cursor-pointer">
                    Accept all
                </button>
            </div>
            <button type="button"
                    id="cookie-btn-customize"
                    class="w-full h-9 px-3 text-xs font-semibold text-[var(--text-secondary)] hover:text-[var(--text-primary)] hover:bg-[var(--bg-canvas)] rounded-[var(--radius-md)] border border-[var(--border-subtle)] transition flex items-center justify-center gap-1.5 cursor-pointer">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                <span>Customize</span>
            </button>
        </div>
    </div>

    <!-- 2. Customize Modal Panel (with Keyboard Focus Trap) -->
    <div id="cookie-panel-customize"
         class="card-ledger p-5 bg-[var(--bg-surface)] border border-[var(--border-default)] shadow-2xl rounded-t-2xl sm:rounded-2xl space-y-4 hidden"
         role="dialog"
         aria-modal="true"
         aria-labelledby="cookie-panel-title">

        <div class="flex items-center justify-between border-b border-[var(--border-subtle)] pb-3">
            <div>
                <h3 id="cookie-panel-title" class="font-serif text-base font-semibold text-[var(--text-primary)]">
                    Customize Cookie Preferences
                </h3>
                <p class="text-[11px] text-[var(--text-muted)] mt-0.5">Control which optional cookies we may store.</p>
            </div>
            <button type="button"
                    id="cookie-btn-close-customize"
                    class="p-1 rounded-[var(--radius-sm)] text-[var(--text-muted)] hover:text-[var(--text-primary)] hover:bg-[var(--bg-canvas)] transition cursor-pointer"
                    aria-label="Close customize panel">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <form id="cookie-preferences-form" class="space-y-3.5">
            <!-- Essential (Locked ON) -->
            <div class="p-3 rounded-[var(--radius-md)] bg-[var(--bg-canvas)] border border-[var(--border-subtle)] flex items-start justify-between gap-3">
                <div class="space-y-0.5">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold text-[var(--text-primary)]">{{ $essentialLabel }}</span>
                        <span class="badge-status text-[10px] text-[var(--color-success)] bg-[var(--color-success-bg)] border-[var(--color-success-border)] py-0 px-1.5">Always active</span>
                    </div>
                    <p class="text-[11px] text-[var(--text-muted)] leading-relaxed">
                        {{ $essentialDesc }}
                    </p>
                </div>
                <input type="checkbox" checked disabled class="mt-1 h-4 w-4 rounded border-[var(--border-default)] text-[var(--brand-primary)] opacity-70 cursor-not-allowed" aria-label="Essential cookies (always active)">
            </div>

            <!-- Analytics (Off by default) -->
            <div class="p-3 rounded-[var(--radius-md)] bg-[var(--bg-canvas)] border border-[var(--border-subtle)] flex items-start justify-between gap-3">
                <div class="space-y-0.5">
                    <label for="cookie-toggle-analytics" class="text-xs font-semibold text-[var(--text-primary)] block cursor-pointer">
                        {{ $analyticsLabel }}
                    </label>
                    <p class="text-[11px] text-[var(--text-muted)] leading-relaxed">
                        {{ $analyticsDesc }}
                    </p>
                </div>
                <input type="checkbox" id="cookie-toggle-analytics" name="analytics" class="mt-1 h-4 w-4 rounded border-[var(--border-default)] text-[var(--brand-primary)] focus:ring-[var(--brand-primary)] cursor-pointer">
            </div>

            <!-- Marketing (Off by default) -->
            <div class="p-3 rounded-[var(--radius-md)] bg-[var(--bg-canvas)] border border-[var(--border-subtle)] flex items-start justify-between gap-3">
                <div class="space-y-0.5">
                    <label for="cookie-toggle-marketing" class="text-xs font-semibold text-[var(--text-primary)] block cursor-pointer">
                        {{ $marketingLabel }}
                    </label>
                    <p class="text-[11px] text-[var(--text-muted)] leading-relaxed">
                        {{ $marketingDesc }}
                    </p>
                </div>
                <input type="checkbox" id="cookie-toggle-marketing" name="marketing" class="mt-1 h-4 w-4 rounded border-[var(--border-default)] text-[var(--brand-primary)] focus:ring-[var(--brand-primary)] cursor-pointer">
            </div>

            <div class="pt-2 flex items-center gap-2">
                <button type="button"
                        id="cookie-btn-cancel-customize"
                        class="btn-secondary h-10 px-3 text-xs font-semibold w-1/3 text-center cursor-pointer">
                    Back
                </button>
                <button type="submit"
                        id="cookie-btn-save-customize"
                        class="btn-primary h-10 px-4 text-xs font-semibold flex-1 text-center cursor-pointer">
                    Save choices
                </button>
            </div>
        </form>
    </div>
</div>

<script>
(function() {
    'use strict';

    const CONSENT_COOKIE_KEY = 'global_nikkah_cookie_consent';
    const CONSENT_MAX_AGE_DAYS = {{ $consentMaxAgeDays }};

    const container = document.getElementById('cookie-consent-container');
    const mainBanner = document.getElementById('cookie-banner-main');
    const customizePanel = document.getElementById('cookie-panel-customize');

    const btnAccept = document.getElementById('cookie-btn-accept');
    const btnReject = document.getElementById('cookie-btn-reject');
    const btnCustomize = document.getElementById('cookie-btn-customize');
    const btnCloseCustomize = document.getElementById('cookie-btn-close-customize');
    const btnCancelCustomize = document.getElementById('cookie-btn-cancel-customize');
    const preferencesForm = document.getElementById('cookie-preferences-form');
    const toggleAnalytics = document.getElementById('cookie-toggle-analytics');
    const toggleMarketing = document.getElementById('cookie-toggle-marketing');

    let previousActiveElement = null;

    // Cookie helpers
    function setCookie(name, value, days) {
        const d = new Date();
        d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
        const expires = "expires=" + d.toUTCString();
        document.cookie = name + "=" + encodeURIComponent(value) + ";" + expires + ";path=/;SameSite=Lax";
    }

    function getCookie(name) {
        const cname = name + "=";
        const decoded = decodeURIComponent(document.cookie);
        const parts = decoded.split(';');
        for (let i = 0; i < parts.length; i++) {
            let c = parts[i].trim();
            if (c.indexOf(cname) === 0) {
                return c.substring(cname.length, c.length);
            }
        }
        return null;
    }

    // Read stored consent
    function loadSavedConsent() {
        let raw = null;
        try {
            raw = localStorage.getItem(CONSENT_COOKIE_KEY);
        } catch(e) {}
        if (!raw) {
            raw = getCookie(CONSENT_COOKIE_KEY);
        }
        if (!raw) return null;
        try {
            return JSON.parse(raw);
        } catch(e) {
            // Backward compatibility with legacy string values
            if (raw === 'accepted') {
                return { essential: true, analytics: true, marketing: true, version: 1 };
            }
            return { essential: true, analytics: false, marketing: false, version: 1 };
        }
    }

    // Persist consent
    function saveConsent(analytics, marketing) {
        const payload = {
            essential: true,
            analytics: Boolean(analytics),
            marketing: Boolean(marketing),
            timestamp: new Date().toISOString(),
            version: 1
        };

        const serialized = JSON.stringify(payload);

        try {
            localStorage.setItem(CONSENT_COOKIE_KEY, serialized);
        } catch(e) {}

        setCookie(CONSENT_COOKIE_KEY, serialized, CONSENT_MAX_AGE_DAYS);

        // Notify scripts and platform listeners
        window.dispatchEvent(new CustomEvent('nikah-cookie-consent-updated', { detail: payload }));

        hideBanner();

        // Dynamically apply/block scripts
        applyConsent(payload);
    }

    function applyConsent(consent) {
        window.NikahConsentState = consent;

        if (consent && consent.analytics) {
            // Safe hook for analytics if configured
            if (typeof window.onNikahAnalyticsEnabled === 'function') {
                window.onNikahAnalyticsEnabled();
            }
        }
        if (consent && consent.marketing) {
            if (typeof window.onNikahMarketingEnabled === 'function') {
                window.onNikahMarketingEnabled();
            }
        }
    }

    // Show/hide controls
    function showBanner() {
        if (!container) return;
        container.classList.remove('hidden');
        requestAnimationFrame(() => {
            container.classList.remove('opacity-0', 'translate-y-3');
        });
    }

    function hideBanner() {
        if (!container) return;
        container.classList.add('opacity-0', 'translate-y-3');
        setTimeout(() => {
            container.classList.add('hidden');
            if (mainBanner) mainBanner.classList.remove('hidden');
            if (customizePanel) customizePanel.classList.add('hidden');
        }, 200);
    }

    function openCustomizePanel() {
        previousActiveElement = document.activeElement;
        const current = loadSavedConsent() || { analytics: false, marketing: false };
        if (toggleAnalytics) toggleAnalytics.checked = Boolean(current.analytics);
        if (toggleMarketing) toggleMarketing.checked = Boolean(current.marketing);

        if (mainBanner) mainBanner.classList.add('hidden');
        if (customizePanel) customizePanel.classList.remove('hidden');

        // Focus first toggle
        if (toggleAnalytics) {
            toggleAnalytics.focus();
        }
    }

    function closeCustomizePanel() {
        if (customizePanel) customizePanel.classList.add('hidden');
        if (mainBanner) mainBanner.classList.remove('hidden');
        if (previousActiveElement && typeof previousActiveElement.focus === 'function') {
            previousActiveElement.focus();
        }
    }

    // Keyboard focus trap inside customize panel
    if (customizePanel) {
        customizePanel.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                e.preventDefault();
                closeCustomizePanel();
                return;
            }
            if (e.key === 'Tab') {
                const focusable = customizePanel.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                const first = focusable[0];
                const last = focusable[focusable.length - 1];

                if (e.shiftKey) {
                    if (document.activeElement === first) {
                        e.preventDefault();
                        last.focus();
                    }
                } else {
                    if (document.activeElement === last) {
                        e.preventDefault();
                        first.focus();
                    }
                }
            }
        });
    }

    // Event listeners
    if (btnAccept) {
        btnAccept.addEventListener('click', function() {
            saveConsent(true, true);
        });
    }

    if (btnReject) {
        btnReject.addEventListener('click', function() {
            saveConsent(false, false);
        });
    }

    if (btnCustomize) {
        btnCustomize.addEventListener('click', function() {
            openCustomizePanel();
        });
    }

    if (btnCloseCustomize) {
        btnCloseCustomize.addEventListener('click', closeCustomizePanel);
    }

    if (btnCancelCustomize) {
        btnCancelCustomize.addEventListener('click', closeCustomizePanel);
    }

    if (preferencesForm) {
        preferencesForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const analytics = toggleAnalytics ? toggleAnalytics.checked : false;
            const marketing = toggleMarketing ? toggleMarketing.checked : false;
            saveConsent(analytics, marketing);
        });
    }

    // Global Public API
    window.NikahConsent = {
        getConsent: loadSavedConsent,
        hasConsent: function(category) {
            const consent = loadSavedConsent();
            if (!consent) return false;
            if (category === 'essential') return true;
            return Boolean(consent[category]);
        },
        openSettings: function() {
            showBanner();
            openCustomizePanel();
        },
        hide: hideBanner
    };

    // Check on startup
    document.addEventListener('DOMContentLoaded', function() {
        const saved = loadSavedConsent();
        if (!saved) {
            // First visit - display banner
            showBanner();
        } else {
            applyConsent(saved);
        }

        // Connect any "Cookie settings" footer links
        document.querySelectorAll('[data-open-cookie-settings], #footer-cookie-settings').forEach(el => {
            el.addEventListener('click', function(e) {
                e.preventDefault();
                window.NikahConsent.openSettings();
            });
        });
    });
})();
</script>


<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\PlatformSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ComplianceController extends Controller
{
    /**
     * Display the compliance and cookie consent management console.
     */
    public function index(): View
    {
        $complianceSettings = [
            'banner_title' => PlatformSetting::get('compliance_banner_title', 'Your Privacy Choices'),
            'banner_description' => PlatformSetting::get('compliance_banner_description', 'We use a few cookies to keep you signed in and to understand how the site is used. You choose what we can use.'),
            'essential_label' => PlatformSetting::get('compliance_essential_label', 'Essential Cookies'),
            'essential_description' => PlatformSetting::get('compliance_essential_description', 'Strictly necessary for login sessions, CSRF security tokens, language, and theme choices.'),
            'analytics_label' => PlatformSetting::get('compliance_analytics_label', 'Analytics Cookies'),
            'analytics_description' => PlatformSetting::get('compliance_analytics_description', 'Anonymous visit counts and page performance metrics that help us improve the platform.'),
            'marketing_label' => PlatformSetting::get('compliance_marketing_label', 'Marketing & Referral Cookies'),
            'marketing_description' => PlatformSetting::get('compliance_marketing_description', 'Measures campaign performance and referrals. We never show external ads or sell your data.'),
            'cookie_policy_url' => PlatformSetting::get('compliance_cookie_policy_url', route('cookie-policy')),
            'privacy_policy_url' => PlatformSetting::get('compliance_privacy_policy_url', route('privacy')),
            'consent_duration_months' => (int) PlatformSetting::get('compliance_consent_duration_months', 12),
        ];

        return view('admin.compliance', compact('complianceSettings'));
    }

    /**
     * Update the platform compliance and cookie consent configuration.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'compliance_banner_title' => ['required', 'string', 'max:120'],
            'compliance_banner_description' => ['required', 'string', 'max:500'],
            'compliance_essential_label' => ['required', 'string', 'max:80'],
            'compliance_essential_description' => ['required', 'string', 'max:300'],
            'compliance_analytics_label' => ['required', 'string', 'max:80'],
            'compliance_analytics_description' => ['required', 'string', 'max:300'],
            'compliance_marketing_label' => ['required', 'string', 'max:80'],
            'compliance_marketing_description' => ['required', 'string', 'max:300'],
            'compliance_cookie_policy_url' => ['required', 'string', 'max:255'],
            'compliance_privacy_policy_url' => ['required', 'string', 'max:255'],
            'compliance_consent_duration_months' => ['required', 'integer', 'min:1', 'max:36'],
        ]);

        foreach ($validated as $key => $value) {
            $type = ($key === 'compliance_consent_duration_months') ? 'integer' : 'string';
            PlatformSetting::set($key, $value, $type, 'compliance', 'Cookie and privacy compliance parameter');
        }

        AuditLog::record(Auth::id(), 'compliance_settings_updated');

        return back()->with('success', 'Compliance banner and cookie consent parameters updated successfully.');
    }
}


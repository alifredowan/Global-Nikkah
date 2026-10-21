<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Conversation;
use App\Models\DiscountCode;
use App\Models\PlatformSetting;
use App\Models\Report;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\VerificationRequest;
use App\Services\Payment\PayPalPaymentService;
use App\Services\Payment\StripePaymentService;
use App\Services\TurnstileService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $metrics = [
            'total_users' => User::count(),
            'seekers' => User::where('role', 'seeker')->count(),
            'walis' => User::where('role', 'wali')->count(),
            'verified_users' => User::where('is_verified', true)->count(),
            'pending_verifications' => VerificationRequest::where('status', 'pending')->count(),
            'active_conversations' => Conversation::where('status', 'active')->count(),
            'pending_reports' => Report::where('status', 'pending')->count(),
            'total_revenue' => Subscription::where('status', 'active')->sum('amount_paid'),
            'paying_subscribers' => Subscription::where('status', 'active')->where('plan', '!=', 'free')->count(),
            'active_packages' => SubscriptionPlan::where('is_active', true)->count(),
            'active_promo_codes' => DiscountCode::where('is_active', true)->count(),
        ];

        $recentVerifications = VerificationRequest::with('user.profile')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $recentReports = Report::with(['reporter', 'reported'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $recentAuditLogs = AuditLog::with('user')->latest('created_at')->take(8)->get();

        return view('admin.dashboard', compact('metrics', 'recentVerifications', 'recentReports', 'recentAuditLogs'));
    }

    public function verifications(): View
    {
        $verifications = VerificationRequest::with(['user.profile', 'reviewer'])
            ->latest()
            ->paginate(15);

        return view('admin.verifications', compact('verifications'));
    }

    public function downloadVerificationDocument(int $id, string $type): BinaryFileResponse
    {
        if (! in_array($type, ['document', 'selfie'], true)) {
            abort(404);
        }

        $verification = VerificationRequest::findOrFail($id);
        $path = $type === 'document' ? $verification->document_path : $verification->selfie_path;

        if (! $path || ! Storage::disk('local')->exists($path)) {
            abort(404);
        }

        AuditLog::record(Auth::id(), 'verification_document_downloaded', 'VerificationRequest', $id, [
            'document_type' => $type,
        ]);

        $extension = pathinfo($path, PATHINFO_EXTENSION) ?: 'bin';

        $response = response()->download(
            Storage::disk('local')->path($path),
            "verification-{$id}-{$type}.{$extension}",
        );
        $response->headers->set('Cache-Control', 'private, no-store');

        return $response;
    }

    public function approveVerification(int $id): RedirectResponse
    {
        $admin = Auth::user();
        $request = VerificationRequest::whereKey($id)
            ->where('status', 'pending')
            ->firstOrFail();

        $request->update([
            'status' => 'approved',
            'reviewer_id' => $admin->id,
            'reviewed_at' => now(),
        ]);

        $request->user->update(['is_verified' => true]);

        AuditLog::record($admin->id, 'verification_approved', 'User', $request->user_id);

        return back()->with('success', 'Verification approved! Candidate now has the official "Verified" badge (FR-1.4).');
    }

    public function rejectVerification(Request $request, int $id): RedirectResponse
    {
        $request->validate(['rejection_reason' => 'required|string|max:500']);

        $admin = Auth::user();
        $verification = VerificationRequest::whereKey($id)
            ->where('status', 'pending')
            ->firstOrFail();

        $verification->update([
            'status' => 'rejected',
            'reviewer_id' => $admin->id,
            'rejection_reason' => $request->rejection_reason,
            'reviewed_at' => now(),
        ]);

        $verification->user->update(['is_verified' => false]);

        AuditLog::record($admin->id, 'verification_rejected', 'User', $verification->user_id, [
            'reason' => $request->rejection_reason,
        ]);

        return back()->with('info', 'Verification rejected with reason recorded.');
    }

    public function reports(): View
    {
        $reports = Report::with(['reporter', 'reported', 'conversation', 'moderator'])
            ->latest()
            ->paginate(15);

        return view('admin.reports', compact('reports'));
    }

    public function actionReport(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'action' => ['required', 'in:warn,suspend,ban,dismiss'],
            'resolution_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $admin = Auth::user();
        $report = Report::findOrFail($id);

        $action = $request->action;
        $reportedUser = $report->reported;

        if ($action === 'suspend' || $action === 'ban') {
            $reportedUser->update(['is_active' => false]);
        }

        $report->update([
            'status' => $action === 'dismiss' ? 'dismissed' : 'actioned',
            'action_taken' => $action,
            'resolution_notes' => $request->resolution_notes,
            'moderator_id' => $admin->id,
            'resolved_at' => now(),
        ]);

        AuditLog::record($admin->id, "report_{$action}", 'Report', $report->id, [
            'reported_user_id' => $reportedUser->id,
            'action' => $action,
        ]);

        return back()->with('success', "Report has been successfully processed with action: {$action}.");
    }

    public function packages(): View
    {
        $packages = SubscriptionPlan::orderBy('sort_order')->orderBy('monthly_price')->get();

        return view('admin.packages', compact('packages'));
    }

    public function storePackage(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:50', 'alpha_dash', 'unique:subscription_plans,slug'],
            'description' => ['nullable', 'string', 'max:500'],
            'monthly_price' => ['required', 'numeric', 'min:0'],
            'annual_price' => ['nullable', 'numeric', 'min:0'],
            'daily_profile_views' => ['required', 'integer'],
            'daily_interests' => ['required', 'integer'],
            'advanced_filters' => ['nullable', 'boolean'],
            'profile_boost' => ['nullable', 'boolean'],
            'see_who_viewed' => ['nullable', 'boolean'],
            'dedicated_advisor' => ['nullable', 'boolean'],
            'badge_text' => ['nullable', 'string', 'max:50'],
            'features' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'is_popular' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $slug = ! empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);
        $originalSlug = $slug;
        $counter = 1;
        while (SubscriptionPlan::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        $features = [];
        if (! empty($validated['features'])) {
            $features = array_values(array_filter(array_map('trim', preg_split('/[\r\n]+/', $validated['features']))));
        }

        $price = $validated['monthly_price'];
        $annualPrice = isset($validated['annual_price']) && $validated['annual_price'] !== '' ? $validated['annual_price'] : $price;

        $package = SubscriptionPlan::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'monthly_price' => $price,
            'annual_price' => $annualPrice,
            'daily_profile_views' => $validated['daily_profile_views'],
            'daily_interests' => $validated['daily_interests'],
            'advanced_filters' => $request->boolean('advanced_filters'),
            'profile_boost' => $request->boolean('profile_boost'),
            'see_who_viewed' => $request->boolean('see_who_viewed'),
            'dedicated_advisor' => $request->boolean('dedicated_advisor'),
            'badge_text' => $validated['badge_text'] ?? null,
            'features' => $features,
            'is_active' => $request->boolean('is_active', true),
            'is_popular' => $request->boolean('is_popular'),
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
        ]);

        AuditLog::record(Auth::id(), 'subscription_package_created', 'SubscriptionPlan', $package->id, [
            'name' => $package->name,
            'slug' => $package->slug,
            'monthly_price' => $package->monthly_price,
        ]);

        return back()->with('success', "Subscription package '{$package->name}' created successfully!");
    }

    public function updatePackage(Request $request, int $id): RedirectResponse
    {
        $package = SubscriptionPlan::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('subscription_plans', 'slug')->ignore($package->id)],
            'description' => ['nullable', 'string', 'max:500'],
            'monthly_price' => ['required', 'numeric', 'min:0'],
            'annual_price' => ['nullable', 'numeric', 'min:0'],
            'daily_profile_views' => ['required', 'integer'],
            'daily_interests' => ['required', 'integer'],
            'advanced_filters' => ['nullable', 'boolean'],
            'profile_boost' => ['nullable', 'boolean'],
            'see_who_viewed' => ['nullable', 'boolean'],
            'dedicated_advisor' => ['nullable', 'boolean'],
            'badge_text' => ['nullable', 'string', 'max:50'],
            'features' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'is_popular' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $features = [];
        if (! empty($validated['features'])) {
            $features = array_values(array_filter(array_map('trim', preg_split('/[\r\n]+/', $validated['features']))));
        }

        $price = $validated['monthly_price'];
        $annualPrice = isset($validated['annual_price']) && $validated['annual_price'] !== '' ? $validated['annual_price'] : $price;

        $package->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['slug']),
            'description' => $validated['description'] ?? null,
            'monthly_price' => $price,
            'annual_price' => $annualPrice,
            'daily_profile_views' => $validated['daily_profile_views'],
            'daily_interests' => $validated['daily_interests'],
            'advanced_filters' => $request->boolean('advanced_filters'),
            'profile_boost' => $request->boolean('profile_boost'),
            'see_who_viewed' => $request->boolean('see_who_viewed'),
            'dedicated_advisor' => $request->boolean('dedicated_advisor'),
            'badge_text' => $validated['badge_text'] ?? null,
            'features' => $features,
            'is_active' => $request->boolean('is_active'),
            'is_popular' => $request->boolean('is_popular'),
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
        ]);

        AuditLog::record(Auth::id(), 'subscription_package_updated', 'SubscriptionPlan', $package->id, [
            'name' => $package->name,
            'monthly_price' => $package->monthly_price,
        ]);

        return back()->with('success', "Subscription package '{$package->name}' updated successfully!");
    }

    public function togglePackageStatus(int $id): RedirectResponse
    {
        $package = SubscriptionPlan::findOrFail($id);
        $package->update(['is_active' => ! $package->is_active]);

        $status = $package->is_active ? 'activated' : 'deactivated';
        AuditLog::record(Auth::id(), "subscription_package_{$status}", 'SubscriptionPlan', $package->id);

        return back()->with('success', "Package '{$package->name}' {$status} successfully.");
    }

    public function destroyPackage(int $id): RedirectResponse
    {
        $package = SubscriptionPlan::findOrFail($id);

        if ($package->slug === 'free') {
            return back()->with('error', 'The default Free tier cannot be deleted.');
        }

        $activeSubscribersCount = Subscription::where('plan', $package->slug)->where('status', 'active')->count();
        if ($activeSubscribersCount > 0) {
            return back()->with('error', "Cannot delete package '{$package->name}' because it has {$activeSubscribersCount} active subscribers. Deactivate it instead.");
        }

        $name = $package->name;
        $package->delete();

        AuditLog::record(Auth::id(), 'subscription_package_deleted', 'SubscriptionPlan', $id, ['name' => $name]);

        return back()->with('success', "Package '{$name}' deleted successfully.");
    }

    public function promoCodes(): View
    {
        $discountCodes = DiscountCode::with('plan')->latest('created_at')->get();
        $packages = SubscriptionPlan::where('monthly_price', '>', 0)->orderBy('name')->get();
        $allUsers = User::where('role', 'seeker')->orderBy('name')->select('id', 'name', 'email')->get();

        return view('admin.promo-codes', compact('discountCodes', 'packages', 'allUsers'));
    }

    public function storePromoCode(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'max:30', 'unique:discount_codes,code'],
            'discount_percentage' => ['required', 'integer', 'min:1', 'max:100'],
            'plan_slug' => ['nullable', 'string'],
            'allowed_emails' => ['nullable', 'string'],
            'description' => ['nullable', 'string', 'max:255'],
            'max_uses' => ['required', 'integer', 'min:1'],
            'expires_at' => ['nullable', 'date'],
        ]);

        $planSlug = $request->plan_slug === 'all' || empty($request->plan_slug) ? null : $request->plan_slug;

        $promo = DiscountCode::create([
            'code' => strtoupper(trim($request->code)),
            'discount_percentage' => (int) $request->discount_percentage,
            'plan_slug' => $planSlug,
            'allowed_emails' => $request->allowed_emails ? trim($request->allowed_emails) : null,
            'description' => $request->description,
            'max_uses' => (int) $request->max_uses,
            'times_used' => 0,
            'is_active' => true,
            'expires_at' => $request->expires_at ? Carbon::parse($request->expires_at) : null,
        ]);

        AuditLog::record(Auth::id(), 'promo_code_created', 'DiscountCode', $promo->id, [
            'code' => $promo->code,
            'discount_percentage' => $promo->discount_percentage,
            'plan_slug' => $promo->plan_slug,
            'restricted' => $promo->isRestrictedToUsers(),
        ]);

        return back()->with('success', "Promo code '{$promo->code}' created successfully!");
    }

    public function togglePromoCodeStatus(int $id): RedirectResponse
    {
        $code = DiscountCode::findOrFail($id);
        $code->update(['is_active' => ! $code->is_active]);

        $status = $code->is_active ? 'activated' : 'deactivated';
        AuditLog::record(Auth::id(), "promo_code_{$status}", 'DiscountCode', $code->id);

        return back()->with('success', "Promo code '{$code->code}' {$status} successfully.");
    }

    public function destroyPromoCode(int $id): RedirectResponse
    {
        $code = DiscountCode::findOrFail($id);
        $name = $code->code;
        $code->delete();

        AuditLog::record(Auth::id(), 'promo_code_deleted', 'DiscountCode', $id, ['code' => $name]);

        return back()->with('success', "Promo code '{$name}' deleted successfully.");
    }

    public function settings(TurnstileService $turnstileService): View
    {
        // Payment and security (CAPTCHA) settings have their own dedicated, validated
        // forms below — never list them here, or they'd be editable/overwritable
        // as plain text through the generic policy-parameters form.
        $settings = PlatformSetting::whereNotIn('group', ['payment', 'security'])->get();
        $discountCodes = DiscountCode::with('plan')->latest('created_at')->get();

        $captchaSettings = [
            'enabled' => $turnstileService->isEnabled(),
            'site_key' => PlatformSetting::get('turnstile_site_key', config('services.turnstile.site_key', '')),
            'secret_key' => PlatformSetting::get('turnstile_secret_key', config('services.turnstile.secret_key', '')),
            'is_configured' => $turnstileService->isConfigured(),
        ];

        return view('admin.settings', compact('settings', 'discountCodes', 'captchaSettings'));
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        // Only pre-existing, non-payment/non-security settings may be edited here.
        // This whitelist is derived server-side so a crafted field name (e.g.
        // stripe_secret, turnstile_secret_key) can never be written through this form.
        $editableSettings = PlatformSetting::whereNotIn('group', ['payment', 'security'])->get();

        foreach ($editableSettings as $setting) {
            if (! $request->has($setting->key)) {
                continue;
            }

            $value = match ($setting->type) {
                'boolean' => $request->boolean($setting->key),
                'integer' => (int) $request->input($setting->key),
                'json' => $request->input($setting->key),
                default => (string) $request->input($setting->key),
            };

            PlatformSetting::set($setting->key, $value, $setting->type, $setting->group, $setting->description);
        }

        AuditLog::record(Auth::id(), 'platform_settings_updated');

        return back()->with('success', 'Platform policy parameters updated successfully (FR-7.3).');
    }

    public function updateCaptchaSettings(Request $request): RedirectResponse
    {
        $request->validate([
            'turnstile_enabled' => ['nullable', 'boolean'],
            'turnstile_site_key' => ['nullable', 'string', 'max:255'],
            'turnstile_secret_key' => ['nullable', 'string', 'max:255'],
        ]);

        PlatformSetting::set('turnstile_enabled', $request->boolean('turnstile_enabled'), 'boolean', 'security', 'Activate Cloudflare Turnstile CAPTCHA on registration and login');
        PlatformSetting::set('turnstile_site_key', $request->input('turnstile_site_key', ''), 'string', 'security', 'Cloudflare Turnstile Site Key (public)');
        PlatformSetting::set('turnstile_secret_key', $request->input('turnstile_secret_key', ''), 'string', 'security', 'Cloudflare Turnstile Secret Key (private)');

        AuditLog::record(Auth::id(), 'captcha_settings_updated');

        return back()->with('success', 'Cloudflare Turnstile CAPTCHA settings updated successfully.');
    }

    public function paymentSettings(
        StripePaymentService $stripeService,
        PayPalPaymentService $paypalService
    ): View {
        $stripeSettings = [
            'enabled' => (bool) PlatformSetting::get('stripe_enabled', true),
            'key' => PlatformSetting::get('stripe_key', config('services.stripe.key', '')),
            'secret' => PlatformSetting::get('stripe_secret', config('services.stripe.secret', '')),
            'webhook_secret' => PlatformSetting::get('stripe_webhook_secret', config('services.stripe.webhook_secret', '')),
            'currency' => PlatformSetting::get('stripe_currency', 'USD'),
            'is_configured' => $stripeService->isConfigured(),
        ];

        $paypalSettings = [
            'enabled' => (bool) PlatformSetting::get('paypal_enabled', true),
            'client_id' => PlatformSetting::get('paypal_client_id', config('services.paypal.client_id', '')),
            'client_secret' => PlatformSetting::get('paypal_client_secret', config('services.paypal.client_secret', '')),
            'mode' => PlatformSetting::get('paypal_mode', config('services.paypal.mode', 'sandbox')),
            'currency' => PlatformSetting::get('paypal_currency', 'USD'),
            'webhook_id' => PlatformSetting::get('paypal_webhook_id', config('services.paypal.webhook_id', '')),
            'is_configured' => $paypalService->isConfigured(),
        ];

        $simulatedEnabled = (bool) PlatformSetting::get('simulated_payment_enabled', true);

        return view('admin.payment-settings', compact('stripeSettings', 'paypalSettings', 'simulatedEnabled'));
    }

    public function updatePaymentSettings(Request $request): RedirectResponse
    {
        $request->validate([
            'stripe_enabled' => ['nullable', 'boolean'],
            'stripe_key' => ['nullable', 'string', 'max:255'],
            'stripe_secret' => ['nullable', 'string', 'max:255'],
            'stripe_webhook_secret' => ['nullable', 'string', 'max:255'],
            'stripe_currency' => ['required', 'string', 'max:5'],
            'paypal_enabled' => ['nullable', 'boolean'],
            'paypal_client_id' => ['nullable', 'string', 'max:255'],
            'paypal_client_secret' => ['nullable', 'string', 'max:255'],
            'paypal_mode' => ['required', 'string', 'in:sandbox,live'],
            'paypal_currency' => ['required', 'string', 'max:5'],
            'paypal_webhook_id' => ['nullable', 'string', 'max:255'],
            'simulated_payment_enabled' => ['nullable', 'boolean'],
        ]);

        // Stripe
        PlatformSetting::set('stripe_enabled', $request->boolean('stripe_enabled'), 'boolean', 'payment', 'Activate or deactivate Stripe payment method on checkout');
        if ($request->has('stripe_key')) {
            PlatformSetting::set('stripe_key', $request->input('stripe_key', ''), 'string', 'payment', 'Stripe Publishable API Key');
        }
        if ($request->has('stripe_secret')) {
            PlatformSetting::set('stripe_secret', $request->input('stripe_secret', ''), 'string', 'payment', 'Stripe Secret API Key');
        }
        if ($request->has('stripe_webhook_secret')) {
            PlatformSetting::set('stripe_webhook_secret', $request->input('stripe_webhook_secret', ''), 'string', 'payment', 'Stripe Webhook Signing Secret');
        }
        PlatformSetting::set('stripe_currency', strtoupper($request->input('stripe_currency', 'USD')), 'string', 'payment', 'Stripe processing currency code');

        // PayPal
        PlatformSetting::set('paypal_enabled', $request->boolean('paypal_enabled'), 'boolean', 'payment', 'Activate or deactivate PayPal payment method on checkout');
        if ($request->has('paypal_client_id')) {
            PlatformSetting::set('paypal_client_id', $request->input('paypal_client_id', ''), 'string', 'payment', 'PayPal REST API Client ID');
        }
        if ($request->has('paypal_client_secret')) {
            PlatformSetting::set('paypal_client_secret', $request->input('paypal_client_secret', ''), 'string', 'payment', 'PayPal REST API Client Secret');
        }
        PlatformSetting::set('paypal_mode', $request->input('paypal_mode', 'sandbox'), 'string', 'payment', 'PayPal environment mode (sandbox or live)');
        PlatformSetting::set('paypal_currency', strtoupper($request->input('paypal_currency', 'USD')), 'string', 'payment', 'PayPal transaction currency code');
        if ($request->has('paypal_webhook_id')) {
            PlatformSetting::set('paypal_webhook_id', $request->input('paypal_webhook_id', ''), 'string', 'payment', 'PayPal Webhook ID');
        }

        // Demo / Simulated
        PlatformSetting::set('simulated_payment_enabled', $request->boolean('simulated_payment_enabled'), 'boolean', 'payment', 'Allow Developer / Demo Mode payment option on checkout');

        AuditLog::record(Auth::id(), 'payment_settings_updated');

        return back()->with('success', 'Payment gateway configurations (PayPal & Stripe) updated successfully.');
    }

    public function togglePaymentGateway(Request $request): RedirectResponse
    {
        $request->validate([
            'gateway' => ['required', 'string', 'in:stripe,paypal,simulated'],
        ]);

        $gateway = $request->input('gateway');
        $key = "{$gateway}_enabled";
        if ($gateway === 'simulated') {
            $key = 'simulated_payment_enabled';
        }

        $current = (bool) PlatformSetting::get($key, true);
        PlatformSetting::set($key, ! $current, 'boolean', 'payment', "Toggle {$gateway} payment status");

        AuditLog::record(Auth::id(), 'payment_gateway_toggled');

        $status = ! $current ? 'activated' : 'deactivated';

        return back()->with('success', ucfirst($gateway).' payment method successfully '.$status.'.');
    }

    public function testPaymentGateway(Request $request): RedirectResponse
    {
        $request->validate([
            'gateway' => ['required', 'string', 'in:stripe,paypal'],
        ]);

        $gateway = $request->input('gateway');

        if ($gateway === 'stripe') {
            $secret = PlatformSetting::get('stripe_secret') ?: config('services.stripe.secret');
            if (empty($secret)) {
                return back()->with('error', 'Stripe Secret Key is empty. Please configure your secret key first.');
            }

            try {
                $response = Http::withToken($secret)->get('https://api.stripe.com/v1/balance');
                if ($response->successful()) {
                    return back()->with('success', '✓ Stripe API authentication successful! Your Stripe keys are active and connected.');
                }

                $err = $response->json('error.message') ?? $response->body();

                return back()->with('error', "Stripe authentication failed: {$err}");
            } catch (\Throwable $e) {
                return back()->with('error', 'Could not reach Stripe server: '.$e->getMessage());
            }
        }

        if ($gateway === 'paypal') {
            $clientId = PlatformSetting::get('paypal_client_id') ?: config('services.paypal.client_id');
            $clientSecret = PlatformSetting::get('paypal_client_secret') ?: config('services.paypal.client_secret');
            $mode = PlatformSetting::get('paypal_mode') ?: config('services.paypal.mode', 'sandbox');

            if (empty($clientId) || empty($clientSecret)) {
                return back()->with('error', 'PayPal Client ID or Secret Key is empty. Please configure them first.');
            }

            $baseUrl = $mode === 'live' ? 'https://api-m.paypal.com' : 'https://api-m.sandbox.paypal.com';

            try {
                $response = Http::asForm()
                    ->withBasicAuth($clientId, $clientSecret)
                    ->post("{$baseUrl}/v1/oauth2/token", [
                        'grant_type' => 'client_credentials',
                    ]);

                if ($response->successful()) {
                    return back()->with('success', "✓ PayPal authentication successful! Connected in [{$mode}] mode.");
                }

                $err = $response->json('error_description') ?? $response->json('error') ?? $response->body();

                return back()->with('error', "PayPal Authentication Failed in [{$mode}] mode: {$err}. Please verify your Client ID & Secret in PayPal Developer Dashboard and ensure environment mode (Sandbox vs Live) matches.");
            } catch (\Throwable $e) {
                return back()->with('error', 'Could not reach PayPal server: '.$e->getMessage());
            }
        }

        return back();
    }

    public function createDiscount(Request $request): RedirectResponse
    {
        return $this->storePromoCode($request);
    }

    public function auditLogs(): View
    {
        $logs = AuditLog::with('user')->latest('created_at')->paginate(25);

        return view('admin.audit-logs', compact('logs'));
    }

    public function staff(): View
    {
        $staffUsers = User::whereIn('role', ['super_admin', 'admin', 'moderator'])
            ->orderBy('created_at', 'desc')
            ->get();

        $availablePermissions = [
            'manage_verifications' => 'KYC Profile & ID Verifications',
            'manage_reports' => 'User Abuse Reports & Sanctions (Warn, Suspend, Ban)',
            'view_audit_logs' => 'Audit Logs & Governance Tracking',
            'manage_settings' => 'Platform Settings & Policies',
        ];

        return view('admin.staff', compact('staffUsers', 'availablePermissions'));
    }

    public function storeStaff(Request $request): RedirectResponse
    {
        $normalizedEmail = strtolower(trim((string) $request->input('email')));
        $request->merge(['email' => $normalizedEmail]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->where(function ($query) use ($normalizedEmail) {
                    return $query->whereRaw('LOWER(email) = ?', [$normalizedEmail]);
                }),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'in:super_admin,moderator'],
            'password' => ['required', 'string', 'min:8'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'in:manage_verifications,manage_reports,view_audit_logs,manage_settings'],
        ], [
            'email.unique' => 'A user or staff member with this email address already exists.',
        ]);

        $permissions = $validated['role'] === 'super_admin'
            ? array_keys([
                'manage_verifications' => true,
                'manage_reports' => true,
                'view_audit_logs' => true,
                'manage_settings' => true,
            ])
            : ($validated['permissions'] ?? []);

        $staff = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => $validated['role'],
            'permissions' => $permissions,
            'password' => Hash::make($validated['password']),
            'gender' => 'male',
            'dob' => '1990-01-01',
            'marital_status' => 'never_married',
            'is_verified' => true,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        AuditLog::record(Auth::id(), 'staff_member_created', 'User', $staff->id, [
            'role' => $staff->role,
            'permissions' => $staff->permissions,
        ]);

        return back()->with('success', "New staff member ({$staff->name} as ".ucwords(str_replace('_', ' ', $staff->role)).') created successfully!');
    }

    public function updateStaffPermissions(Request $request, int $id): RedirectResponse
    {
        $staff = User::findOrFail($id);

        if ($staff->id === Auth::id()) {
            return back()->with('error', 'You cannot modify your own role or permissions.');
        }

        if ($staff->isSuperAdmin() && ! Auth::user()->isSuperAdmin()) {
            abort(403, 'Only a Super Admin can modify other Super Admins.');
        }

        $validated = $request->validate([
            'role' => ['nullable', 'in:super_admin,moderator'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'in:manage_verifications,manage_reports,view_audit_logs,manage_settings'],
        ]);

        $updates = [
            'permissions' => $validated['permissions'] ?? [],
        ];

        if (isset($validated['role'])) {
            $updates['role'] = $validated['role'];
            if ($validated['role'] === 'super_admin') {
                $updates['permissions'] = [
                    'manage_verifications',
                    'manage_reports',
                    'view_audit_logs',
                    'manage_settings',
                ];
            }
        }

        $staff->update($updates);

        AuditLog::record(Auth::id(), 'staff_permissions_updated', 'User', $staff->id, [
            'permissions' => $staff->permissions,
            'role' => $staff->role,
        ]);

        return back()->with('success', "Permissions updated for {$staff->name}.");
    }

    public function toggleStaffStatus(int $id): RedirectResponse
    {
        $staff = User::findOrFail($id);

        if ($staff->id === Auth::id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $staff->update(['is_active' => ! $staff->is_active]);

        $statusText = $staff->is_active ? 'activated' : 'deactivated';

        AuditLog::record(Auth::id(), "staff_{$statusText}", 'User', $staff->id);

        return back()->with('success', "Staff account {$statusText} successfully.");
    }
}

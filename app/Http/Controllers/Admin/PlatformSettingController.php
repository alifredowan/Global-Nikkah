<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\DiscountCode;
use App\Models\PlatformSetting;
use App\Services\Payment\PayPalPaymentService;
use App\Services\Payment\StripePaymentService;
use App\Services\TurnstileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class PlatformSettingController extends Controller
{
    public function index(TurnstileService $turnstileService): View
    {
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

    public function update(Request $request): RedirectResponse
    {
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

    public function updateCaptcha(Request $request): RedirectResponse
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
}


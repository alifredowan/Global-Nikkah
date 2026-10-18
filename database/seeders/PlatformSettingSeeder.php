<?php

namespace Database\Seeders;

use App\Models\DiscountCode;
use App\Models\PlatformSetting;
use Illuminate\Database\Seeder;

class PlatformSettingSeeder extends Seeder
{
    public function run(): void
    {
        // General platform policy settings (FR-7.3)
        PlatformSetting::set('platform_name', 'Global Nikkah', 'string', 'general', 'Platform working brand name');
        PlatformSetting::set('require_wali_by_default', true, 'boolean', 'moderation', 'Whether Wali guardian linkage is prompted by default for female profiles');
        PlatformSetting::set('max_photos_per_profile', 6, 'integer', 'general', 'Maximum photos a user can upload (FR-2.2)');
        PlatformSetting::set('free_tier_daily_profile_views', 10, 'integer', 'subscription', 'Daily profile views quota for free tier (FR-3.4)');
        PlatformSetting::set('free_tier_daily_interests', 5, 'integer', 'subscription', 'Daily interest requests quota for free tier (FR-5.1)');
        PlatformSetting::set('message_contact_filter_enabled', true, 'boolean', 'moderation', 'Automatically scan and flag external contact numbers/emails in chat (FR-4.4)');

        // Payment Gateway Settings (Stripe & PayPal)
        PlatformSetting::set('stripe_enabled', true, 'boolean', 'payment', 'Activate or deactivate Stripe payment method on checkout');
        PlatformSetting::set('stripe_key', (string) config('services.stripe.key', ''), 'string', 'payment', 'Stripe Publishable API Key');
        PlatformSetting::set('stripe_secret', (string) config('services.stripe.secret', ''), 'string', 'payment', 'Stripe Secret API Key');
        PlatformSetting::set('stripe_webhook_secret', (string) config('services.stripe.webhook_secret', ''), 'string', 'payment', 'Stripe Webhook Signing Secret');
        PlatformSetting::set('stripe_currency', 'USD', 'string', 'payment', 'Stripe processing currency code');

        PlatformSetting::set('paypal_enabled', true, 'boolean', 'payment', 'Activate or deactivate PayPal payment method on checkout');
        PlatformSetting::set('paypal_client_id', (string) config('services.paypal.client_id', ''), 'string', 'payment', 'PayPal REST API Client ID');
        PlatformSetting::set('paypal_client_secret', (string) config('services.paypal.client_secret', ''), 'string', 'payment', 'PayPal REST API Client Secret');
        PlatformSetting::set('paypal_mode', (string) config('services.paypal.mode', 'sandbox'), 'string', 'payment', 'PayPal environment mode (sandbox or live)');
        PlatformSetting::set('paypal_currency', 'USD', 'string', 'payment', 'PayPal transaction currency code');
        PlatformSetting::set('paypal_webhook_id', (string) config('services.paypal.webhook_id', ''), 'string', 'payment', 'PayPal Webhook ID');

        PlatformSetting::set('simulated_payment_enabled', true, 'boolean', 'payment', 'Allow Developer / Demo Mode payment option on checkout');

        // Pre-configure promotional discount codes (Section 3.5)
        DiscountCode::updateOrCreate(['code' => 'HALAL20'], [
            'discount_percentage' => 20,
            'max_uses' => 500,
            'times_used' => 0,
            'is_active' => true,
            'expires_at' => now()->addYear(),
        ]);

        DiscountCode::updateOrCreate(['code' => 'BARAKAH50'], [
            'discount_percentage' => 50,
            'max_uses' => 100,
            'times_used' => 0,
            'is_active' => true,
            'expires_at' => now()->addMonths(6),
        ]);
    }
}

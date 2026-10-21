<?php

namespace Tests\Feature;

use App\Models\PlatformSetting;
use App\Models\Profile;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Database\Seeders\PlatformSettingSeeder;
use Database\Seeders\SubscriptionPlanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AdminPaymentSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PlatformSettingSeeder::class);
        $this->seed(SubscriptionPlanSeeder::class);
    }

    protected function createSuperAdmin(): User
    {
        return User::create([
            'name' => 'Super Admin Tester',
            'email' => 'super_payment_test_'.uniqid().'@example.com',
            'password' => bcrypt('Password123!'),
            'role' => 'super_admin',
            'gender' => 'male',
            'is_active' => true,
            'is_verified' => true,
            'email_verified_at' => now(),
            'permissions' => ['manage_settings'],
        ]);
    }

    protected function createSeeker(): User
    {
        $user = User::create([
            'name' => 'Seeker Payment Tester',
            'email' => 'seeker_payment_test_'.uniqid().'@example.com',
            'password' => bcrypt('Password123!'),
            'role' => 'seeker',
            'gender' => 'male',
            'dob' => '1995-05-15',
            'marital_status' => 'never_married',
            'is_active' => true,
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);

        Profile::create([
            'user_id' => $user->id,
            'city' => 'Dhaka',
            'country' => 'Bangladesh',
            'sect_madhhab' => 'Sunni - Hanafi',
            'prayer_frequency' => '5x_daily',
            'completeness_percentage' => 85,
        ]);

        return $user;
    }

    public function test_admin_can_view_payment_settings_page(): void
    {
        $admin = $this->createSuperAdmin();

        $response = $this->actingAs($admin)->get(route('admin.payment-settings'));

        $response->assertStatus(200);
        $response->assertSee('Payment Gateways & Transactions', false);
        $response->assertSee('Stripe Configuration');
        $response->assertSee('PayPal Configuration');
        $response->assertSee('Simulated Developer / Demo Mode');
        $response->assertSee('Publishable Key');
        $response->assertSee('Client ID');
    }

    public function test_admin_can_update_stripe_and_paypal_dynamic_settings(): void
    {
        $admin = $this->createSuperAdmin();

        $response = $this->actingAs($admin)->post(route('admin.payment-settings.update'), [
            'stripe_enabled' => '1',
            'stripe_key' => 'pk_test_updated_123',
            'stripe_secret' => 'sk_test_updated_456',
            'stripe_webhook_secret' => 'whsec_updated_789',
            'stripe_currency' => 'USD',
            'paypal_enabled' => '1',
            'paypal_client_id' => 'paypal_client_updated_123',
            'paypal_client_secret' => 'paypal_secret_updated_456',
            'paypal_mode' => 'live',
            'paypal_currency' => 'USD',
            'paypal_webhook_id' => 'webhook_id_updated',
            'simulated_payment_enabled' => '1',
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals('pk_test_updated_123', PlatformSetting::get('stripe_key'));
        $this->assertEquals('sk_test_updated_456', PlatformSetting::get('stripe_secret'));
        $this->assertEquals('paypal_client_updated_123', PlatformSetting::get('paypal_client_id'));
        $this->assertEquals('live', PlatformSetting::get('paypal_mode'));
    }

    public function test_admin_can_toggle_payment_gateway_status(): void
    {
        $admin = $this->createSuperAdmin();

        // Toggle stripe off
        $response = $this->actingAs($admin)->post(route('admin.payment-settings.toggle'), [
            'gateway' => 'stripe',
        ]);

        $response->assertSessionHas('success');
        $this->assertFalse(PlatformSetting::get('stripe_enabled'));

        // Toggle stripe back on
        $response2 = $this->actingAs($admin)->post(route('admin.payment-settings.toggle'), [
            'gateway' => 'stripe',
        ]);

        $response2->assertSessionHas('success');
        $this->assertTrue(PlatformSetting::get('stripe_enabled'));
    }

    public function test_checkout_hides_deactivated_payment_gateway(): void
    {
        $seeker = $this->createSeeker();
        $plan = SubscriptionPlan::where('slug', 'premium')->first() ?? SubscriptionPlan::first();

        // Deactivate PayPal
        PlatformSetting::set('paypal_enabled', false, 'boolean', 'payment');
        PlatformSetting::set('stripe_enabled', true, 'boolean', 'payment');

        $response = $this->actingAs($seeker)->get(route('subscription.checkout', $plan->slug));

        $response->assertStatus(200);
        $response->assertSee('Stripe');
        $response->assertDontSee('PayPal Official Gateway');
    }

    public function test_seeker_cannot_process_disabled_payment_method(): void
    {
        $seeker = $this->createSeeker();
        $plan = SubscriptionPlan::where('slug', 'premium')->first() ?? SubscriptionPlan::first();

        // Disable Stripe
        PlatformSetting::set('stripe_enabled', false, 'boolean', 'payment');

        $response = $this->actingAs($seeker)->post(route('subscription.process'), [
            'plan' => $plan->slug,
            'payment_method' => 'stripe',
        ]);

        $response->assertSessionHasErrors('payment');
    }

    public function test_landing_page_does_not_display_monthly_subscription_caption(): void
    {
        $response = $this->get(route('landing'));

        $response->assertStatus(200);
        $response->assertDontSee('/ month');
        $response->assertSee('Pay 1-Time');
        $response->assertSee('Active Until Confirmed Nikah');
    }

    public function test_admin_can_test_stripe_gateway_connection_success(): void
    {
        Http::fake([
            'https://api.stripe.com/v1/balance' => Http::response(['object' => 'balance'], 200),
        ]);

        $superAdmin = $this->createSuperAdmin();
        PlatformSetting::set('stripe_secret', 'sk_test_mock_123', 'string', 'payment');

        $response = $this->actingAs($superAdmin)->post(route('admin.payment-settings.test'), [
            'gateway' => 'stripe',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_admin_can_test_paypal_gateway_connection_success(): void
    {
        Http::fake([
            'https://api-m.sandbox.paypal.com/v1/oauth2/token' => Http::response([
                'access_token' => 'mock_access_token_123',
                'token_type' => 'Bearer',
                'expires_in' => 32400,
            ], 200),
        ]);

        $superAdmin = $this->createSuperAdmin();
        PlatformSetting::set('paypal_client_id', 'mock_paypal_client_id', 'string', 'payment');
        PlatformSetting::set('paypal_client_secret', 'mock_paypal_secret', 'string', 'payment');
        PlatformSetting::set('paypal_mode', 'sandbox', 'string', 'payment');

        $response = $this->actingAs($superAdmin)->post(route('admin.payment-settings.test'), [
            'gateway' => 'paypal',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_admin_gateway_test_connection_handles_failure(): void
    {
        Http::fake([
            'https://api.stripe.com/v1/balance' => Http::response([
                'error' => ['message' => 'Invalid API Key provided'],
            ], 401),
        ]);

        $superAdmin = $this->createSuperAdmin();
        PlatformSetting::set('stripe_secret', 'sk_test_invalid_key', 'string', 'payment');

        $response = $this->actingAs($superAdmin)->post(route('admin.payment-settings.test'), [
            'gateway' => 'stripe',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}

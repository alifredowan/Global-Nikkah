<?php

namespace Tests\Feature;

use App\Models\PlatformSetting;
use App\Rules\Turnstile;
use App\Services\TurnstileService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class TurnstileCaptchaTest extends TestCase
{
    use RefreshDatabase;

    public function test_turnstile_service_reads_site_key_from_config(): void
    {
        config([
            'services.turnstile.enabled' => true,
            'services.turnstile.site_key' => '1x00000000000000000000AA',
            'services.turnstile.secret_key' => '1x0000000000000000000000000000000AA',
        ]);

        $service = new TurnstileService;

        $this->assertEquals('1x00000000000000000000AA', $service->getSiteKey());
        $this->assertTrue($service->isConfigured());
    }

    public function test_turnstile_service_respects_platform_setting_overrides(): void
    {
        config([
            'services.turnstile.enabled' => true,
            'services.turnstile.site_key' => 'original-site-key',
            'services.turnstile.secret_key' => 'original-secret-key',
        ]);

        PlatformSetting::set('turnstile_site_key', 'custom-db-site-key', 'string', 'security');
        PlatformSetting::set('turnstile_secret_key', 'custom-db-secret-key', 'string', 'security');

        $service = new TurnstileService;

        $this->assertEquals('custom-db-site-key', $service->getSiteKey());
    }

    public function test_turnstile_dummy_pass_token_verifies_successfully_in_development(): void
    {
        config([
            'services.turnstile.enabled' => true,
            'services.turnstile.site_key' => '1x00000000000000000000AA',
            'services.turnstile.secret_key' => '1x0000000000000000000000000000000AA',
            'services.turnstile.enabled_in_testing' => true,
        ]);

        $service = new TurnstileService;
        $this->assertTrue($service->verify('cf-turnstile-dummy-pass', '127.0.0.1'));
        $this->assertTrue($service->verify('cf-verified-turnstile-token', '127.0.0.1'));
    }

    public function test_turnstile_rule_fails_when_token_is_empty_and_service_enabled(): void
    {
        config([
            'services.turnstile.enabled' => true,
            'services.turnstile.site_key' => '1x00000000000000000000AA',
            'services.turnstile.secret_key' => '1x0000000000000000000000000000000AA',
            'services.turnstile.enabled_in_testing' => true,
        ]);

        $validator = Validator::make([
            'cf-turnstile-response' => '',
        ], [
            'cf-turnstile-response' => [new Turnstile],
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('cf-turnstile-response', $validator->errors()->messages());
    }

    public function test_turnstile_rule_passes_when_dummy_token_is_provided(): void
    {
        config([
            'services.turnstile.enabled' => true,
            'services.turnstile.site_key' => '1x00000000000000000000AA',
            'services.turnstile.secret_key' => '1x0000000000000000000000000000000AA',
            'services.turnstile.enabled_in_testing' => true,
        ]);

        $validator = Validator::make([
            'cf-turnstile-response' => 'cf-turnstile-dummy-pass',
        ], [
            'cf-turnstile-response' => [new Turnstile],
        ]);

        $this->assertFalse($validator->fails());
    }

    public function test_turnstile_rule_fails_when_field_is_completely_missing(): void
    {
        config([
            'services.turnstile.enabled' => true,
            'services.turnstile.site_key' => '1x00000000000000000000AA',
            'services.turnstile.secret_key' => '1x0000000000000000000000000000000AA',
            'services.turnstile.enabled_in_testing' => true,
        ]);

        $validator = Validator::make([], [
            'cf-turnstile-response' => [new Turnstile],
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('cf-turnstile-response', $validator->errors()->messages());
    }

    public function test_turnstile_rule_passes_silently_when_service_is_disabled(): void
    {
        config([
            'services.turnstile.enabled' => false,
            'services.turnstile.site_key' => '1x00000000000000000000AA',
            'services.turnstile.secret_key' => '1x0000000000000000000000000000000AA',
            'services.turnstile.enabled_in_testing' => false,
        ]);

        $validator = Validator::make([], [
            'cf-turnstile-response' => [new Turnstile],
        ]);

        $this->assertFalse($validator->fails());
    }

    public function test_login_page_renders_turnstile_captcha_and_script(): void
    {
        config([
            'services.turnstile.enabled' => true,
            'services.turnstile.site_key' => '1x00000000000000000000AA',
            'services.turnstile.secret_key' => '1x0000000000000000000000000000000AA',
            'services.turnstile.enabled_in_testing' => true,
        ]);

        $response = $this->get(route('login'));

        $response->assertOk();
        $response->assertSee('cf-turnstile');
        $response->assertSee('data-sitekey="1x00000000000000000000AA"', false);
        $response->assertSee('https://challenges.cloudflare.com/turnstile/v0/api.js', false);
    }

    public function test_register_page_renders_turnstile_captcha_and_script(): void
    {
        config([
            'services.turnstile.enabled' => true,
            'services.turnstile.site_key' => '1x00000000000000000000AA',
            'services.turnstile.secret_key' => '1x0000000000000000000000000000000AA',
            'services.turnstile.enabled_in_testing' => true,
        ]);

        $response = $this->get(route('register'));

        $response->assertOk();
        $response->assertSee('cf-turnstile');
        $response->assertSee('data-sitekey="1x00000000000000000000AA"', false);
        $response->assertSee('https://challenges.cloudflare.com/turnstile/v0/api.js', false);
    }
}

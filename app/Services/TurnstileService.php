<?php

namespace App\Services;

use App\Models\PlatformSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TurnstileService
{
    protected bool $enabled;

    protected ?string $siteKey;

    protected ?string $secretKey;

    public function __construct()
    {
        // Always start from .env / config as the base truth
        $configEnabled = (bool) config('services.turnstile.enabled', true);
        $configSiteKey = config('services.turnstile.site_key');
        $configSecretKey = config('services.turnstile.secret_key');

        // Database platform settings override config when available
        try {
            $dbEnabled = PlatformSetting::get('turnstile_enabled');
            $this->enabled = $dbEnabled !== null ? (bool) $dbEnabled : $configEnabled;

            $dbSiteKey = PlatformSetting::get('turnstile_site_key');
            $this->siteKey = ! empty($dbSiteKey) ? $dbSiteKey : $configSiteKey;

            $dbSecretKey = PlatformSetting::get('turnstile_secret_key');
            $this->secretKey = ! empty($dbSecretKey) ? $dbSecretKey : $configSecretKey;
        } catch (\Throwable) {
            // Database unavailable — fall back entirely to .env / config
            $this->enabled = $configEnabled;
            $this->siteKey = $configSiteKey;
            $this->secretKey = $configSecretKey;
        }
    }

    public function isEnabled(): bool
    {
        if (app()->environment('testing') && ! config('services.turnstile.enabled_in_testing', false)) {
            return false;
        }

        return $this->enabled && $this->isConfigured();
    }

    public function isConfigured(): bool
    {
        return ! empty($this->siteKey) && ! empty($this->secretKey);
    }

    public function getSiteKey(): ?string
    {
        return $this->siteKey;
    }

    public function verify(?string $token, ?string $ip = null): bool
    {
        if (! $this->isEnabled()) {
            return true;
        }

        if (empty($token)) {
            return false;
        }

        // Fast-path for dummy test keys or simulated verification tokens in dev/testing
        if ($this->secretKey === '1x0000000000000000000000000000000AA' && in_array($token, ['cf-turnstile-dummy-pass', 'cf-verified-turnstile-token'], true)) {
            return true;
        }

        try {
            $response = Http::asForm()->timeout(5)->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => $this->secretKey,
                'response' => $token,
                'remoteip' => $ip,
            ]);

            return (bool) ($response->json('success') ?? false);
        } catch (\Throwable $e) {
            Log::warning('Turnstile verification request failed: '.$e->getMessage());

            if ($this->secretKey === '1x0000000000000000000000000000000AA') {
                return true;
            }

            return false;
        }
    }
}

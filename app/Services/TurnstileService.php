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
        $dbEnabled = PlatformSetting::get('turnstile_enabled');
        if ($dbEnabled !== null) {
            $this->enabled = (bool) $dbEnabled;
        } else {
            $this->enabled = (bool) config('services.turnstile.enabled', true);
        }

        $dbSiteKey = PlatformSetting::get('turnstile_site_key');
        $this->siteKey = ! empty($dbSiteKey) ? $dbSiteKey : config('services.turnstile.site_key');

        $dbSecretKey = PlatformSetting::get('turnstile_secret_key');
        $this->secretKey = ! empty($dbSecretKey) ? $dbSecretKey : config('services.turnstile.secret_key');
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

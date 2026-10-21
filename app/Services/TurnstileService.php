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
        $this->enabled = (bool) PlatformSetting::get('turnstile_enabled', false);
        $this->siteKey = PlatformSetting::get('turnstile_site_key') ?: config('services.turnstile.site_key');
        $this->secretKey = PlatformSetting::get('turnstile_secret_key') ?: config('services.turnstile.secret_key');
    }

    public function isEnabled(): bool
    {
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

        try {
            $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => $this->secretKey,
                'response' => $token,
                'remoteip' => $ip,
            ]);

            return (bool) ($response->json('success') ?? false);
        } catch (\Throwable $e) {
            Log::warning('Turnstile verification request failed: '.$e->getMessage());

            return false;
        }
    }
}

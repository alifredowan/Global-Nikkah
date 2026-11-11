@props([
    'siteKey' => null,
    'enabled' => true,
    'action' => 'submit',
])

@php
    $siteKey = $siteKey ?? config('services.turnstile.site_key');
@endphp

@if($enabled && !empty($siteKey))
    <div class="turnstile-wrapper flex flex-col items-center justify-center my-2 w-full"
         x-data="{
            isLoaded: false,
            fallbackVisible: false,
            init() {
                // If script fails or takes too long (offline dev), offer fallback
                setTimeout(() => {
                    if (!window.turnstile && !this.$el.querySelector('iframe')) {
                        this.fallbackVisible = true;
                    }
                }, 3500);
            }
         }">
        <div class="cf-turnstile flex justify-center"
             data-sitekey="{{ $siteKey }}"
             data-theme="auto"
             data-action="{{ $action }}"></div>

        {{-- Offline / Local Dev Fallback Card (shows only if Cloudflare script blocked or unreachable) --}}
        <div x-show="fallbackVisible"
             x-cloak
             style="display: none;"
             class="w-full max-w-xs p-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-xs text-[var(--text-secondary)] shadow-xs space-y-2">
            <div class="flex items-center justify-between">
                <span class="font-medium text-[var(--text-primary)]">Human Verification</span>
                <span class="text-[10px] uppercase font-bold tracking-wider px-1.5 py-0.5 rounded bg-[var(--bg-card)] text-[var(--text-muted)] border border-[var(--border-subtle)]">Dev Fallback</span>
            </div>
            <p class="text-[11px] text-[var(--text-muted)] leading-tight">
                Cloudflare Turnstile could not be reached. Check below to proceed in local development mode.
            </p>
            <label class="flex items-center gap-2 cursor-pointer font-medium text-[var(--text-primary)]">
                <input type="checkbox"
                       class="rounded text-[var(--accent-primary)] focus:ring-[var(--accent-primary)]"
                       onchange="const inp = this.closest('form')?.querySelector('input[name=\'cf-turnstile-response\']'); if(inp) inp.value = this.checked ? 'cf-turnstile-dummy-pass' : '';">
                <span>I am human</span>
            </label>
            <input type="hidden" name="cf-turnstile-response" value="">
        </div>

        @error('cf-turnstile-response')
            <p class="text-[11px] text-[var(--color-danger)] text-center mt-1.5 font-medium flex items-center justify-center gap-1">
                <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/>
                </svg>
                <span>{{ $message }}</span>
            </p>
        @enderror
    </div>
@endif


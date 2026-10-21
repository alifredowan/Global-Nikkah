@extends('layouts.app')

@section('title', 'Platform Policy Parameters - Admin')
@section('page_title', 'Platform Policies')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8">

        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white font-heading">Platform Policy Parameters</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Configure global moderation rules, subscription quotas, and promotional discounts without redeploying code.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Platform Policy Parameters Form -->
            <div class="md:col-span-2 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 shadow-xs">
                <h2 class="text-base font-bold text-slate-900 dark:text-white font-heading mb-4">Core Platform Policies</h2>
                <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-4">
                    @csrf

                    @foreach($settings as $setting)
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1 font-mono">
                                {{ $setting->key }}
                            </label>
                            @if($setting->type === 'boolean')
                                <select name="{{ $setting->key }}" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
                                    <option value="1" {{ $setting->value == '1' ? 'selected' : '' }}>Enabled (True)</option>
                                    <option value="0" {{ $setting->value == '0' ? 'selected' : '' }}>Disabled (False)</option>
                                </select>
                            @else
                                <input type="text" name="{{ $setting->key }}" value="{{ $setting->value }}"
                                       class="w-full px-3 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
                            @endif
                            <span class="text-[10px] text-slate-400 dark:text-slate-500 block mt-0.5">{{ $setting->description }}</span>
                        </div>
                    @endforeach

                    <button type="submit" class="w-full py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition mt-4">
                        Save Policy Parameters
                    </button>
                </form>
            </div>

            <!-- Quick Links & Promo Codes Section -->
            <div class="space-y-6">
                <!-- Cloudflare Turnstile CAPTCHA -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 shadow-xs space-y-4" x-data="{ showSecret: false }">
                    <div class="flex items-center justify-between">
                        <h2 class="text-base font-bold text-slate-900 dark:text-white font-heading">CAPTCHA (Cloudflare Turnstile)</h2>
                        @if($captchaSettings['is_configured'])
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $captchaSettings['enabled'] ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400' }}">
                                {{ $captchaSettings['enabled'] ? 'Active' : 'Configured' }}
                            </span>
                        @else
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300">Not Configured</span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                        Get your Site Key and Secret Key from the <a href="https://dash.cloudflare.com/?to=/:account/turnstile" target="_blank" rel="noopener" class="text-emerald-700 dark:text-emerald-400 underline font-medium">Cloudflare Turnstile dashboard</a>, then paste them below to protect Registration and Login from bots.
                    </p>
                    <form action="{{ route('admin.settings.captcha.update') }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Site Key</label>
                            <input type="text" name="turnstile_site_key" value="{{ $captchaSettings['site_key'] }}" autocomplete="off"
                                   placeholder="0x4AAAAAAA..."
                                   class="w-full px-3 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs font-mono focus:ring-2 focus:ring-emerald-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Secret Key</label>
                            <div class="relative">
                                <input :type="showSecret ? 'text' : 'password'" name="turnstile_secret_key" value="{{ $captchaSettings['secret_key'] }}" autocomplete="off"
                                       placeholder="0x4AAAAAAA..."
                                       class="w-full px-3 py-2.5 pr-11 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs font-mono focus:ring-2 focus:ring-emerald-500 outline-none">
                                <button type="button" @click="showSecret = !showSecret" tabindex="-1"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-[10px] font-bold uppercase">
                                    <span x-show="!showSecret">Show</span>
                                    <span x-show="showSecret" style="display:none;">Hide</span>
                                </button>
                            </div>
                        </div>
                        <label class="flex items-center gap-2 text-xs text-slate-600 dark:text-slate-400 cursor-pointer">
                            <input type="checkbox" name="turnstile_enabled" value="1" {{ $captchaSettings['enabled'] ? 'checked' : '' }}
                                   class="rounded text-emerald-600 focus:ring-emerald-500 dark:bg-slate-800 dark:border-slate-700">
                            Enable CAPTCHA on Registration & Login
                        </label>
                        <button type="submit" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition">
                            Save CAPTCHA Settings
                        </button>
                    </form>
                </div>

                <!-- Direct Management Cards -->
                <div class="bg-gradient-to-br from-indigo-600 to-purple-700 text-white rounded-3xl p-6 shadow-sm space-y-3">
                    <span class="text-xs font-extrabold uppercase tracking-wider text-indigo-200">Payment Infrastructure</span>
                    <h3 class="text-lg font-bold font-heading">Payment Gateways & Keys</h3>
                    <p class="text-xs text-indigo-100 leading-relaxed">Toggle PayPal & Stripe methods on/off, configure API keys, webhooks, and currencies dynamically.</p>
                    <a href="{{ route('admin.payment-settings') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white text-indigo-800 rounded-xl font-bold text-xs hover:bg-indigo-50 transition shadow-xs">
                        💰 Configure Gateways &rarr;
                    </a>
                </div>

                <div class="bg-gradient-to-br from-emerald-600 to-teal-700 text-white rounded-3xl p-6 shadow-sm space-y-3">
                    <span class="text-xs font-extrabold uppercase tracking-wider text-emerald-200">Admin Control</span>
                    <h3 class="text-lg font-bold font-heading">Dynamic Packages & Tiers</h3>
                    <p class="text-xs text-emerald-100 leading-relaxed">Customize package pricing, quotas, features, and active status dynamically.</p>
                    <a href="{{ route('admin.packages.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white text-emerald-800 rounded-xl font-bold text-xs hover:bg-emerald-50 transition shadow-xs">
                        💳 Manage Packages &rarr;
                    </a>
                </div>

                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 shadow-xs space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-base font-bold text-slate-900 dark:text-white font-heading">Quick Promo Generator</h2>
                        <a href="{{ route('admin.promo-codes.index') }}" class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 hover:underline">Full Manager &rarr;</a>
                    </div>
                    <form action="{{ route('admin.promo-codes.store') }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Coupon Code</label>
                            <input type="text" name="code" required placeholder="e.g. BARAKAH30"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500 text-xs uppercase font-mono">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Discount %</label>
                            <input type="number" name="discount_percentage" required min="1" max="100" placeholder="e.g. 30"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Max Redemptions</label>
                            <input type="number" name="max_uses" value="100" required
                                   class="w-full px-3 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs">
                        </div>
                        <button type="submit" class="w-full py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs shadow-xs transition">
                            Create Promo Code
                        </button>
                    </form>
                </div>

                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 shadow-xs">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Active Promo Codes</h3>
                        <a href="{{ route('admin.promo-codes.index') }}" class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 hover:underline">View All &rarr;</a>
                    </div>
                    <div class="space-y-2">
                        @foreach($discountCodes as $code)
                            <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/60 text-xs flex items-center justify-between">
                                <div>
                                    <span class="font-mono font-bold text-emerald-800 dark:text-emerald-400">{{ $code->code }}</span>
                                    @if($code->isRestrictedToUsers())
                                        <span class="text-[9px] px-1.5 py-0.5 rounded-full bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 font-bold ml-1">Special</span>
                                    @endif
                                </div>
                                <span class="text-slate-600 dark:text-slate-300 font-semibold">{{ $code->discount_percentage }}% off ({{ $code->times_used }}/{{ $code->max_uses }})</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@extends('layouts.app')

@section('title', 'Payment Gateways & Transaction Setup - Admin')
@section('page_title', 'Payment Gateways')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8"
     x-data="{
        activeTab: 'all',
        showStripeSecret: false,
        showPayPalSecret: false,
        copyText(text, btnId) {
            navigator.clipboard.writeText(text);
            const el = document.getElementById(btnId);
            if (el) {
                const old = el.innerHTML;
                el.innerHTML = '✓ Copied!';
                setTimeout(() => el.innerHTML = old, 2000);
            }
        }
     }">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-950/80 border border-emerald-300 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs font-bold uppercase tracking-wider mb-2">
                <span>💳</span>
                <span>Financial Infrastructure & Gateways</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white font-heading">Payment Gateways & Transactions</h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                Activate or deactivate payment methods dynamically, configure live and sandbox API credentials, and manage transaction currencies.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.packages.index') }}" class="px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold transition flex items-center gap-1.5 shadow-xs">
                <span>📦</span> Dynamic Packages &rarr;
            </a>
            <a href="{{ route('admin.promo-codes.index') }}" class="px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold transition flex items-center gap-1.5 shadow-xs">
                <span>🏷️</span> Promo Codes &rarr;
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs font-semibold flex items-center gap-2.5 shadow-xs">
            <span class="text-base">✓</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 text-xs font-semibold flex items-center gap-2.5 shadow-xs">
            <span class="text-base">⚠️</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 text-xs space-y-1 shadow-xs">
            <div class="font-bold flex items-center gap-1.5">
                <span>⚠️</span> Please fix the following validation issues:
            </div>
            <ul class="list-disc pl-5 text-[11px] space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Live Status Quick Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <!-- Stripe Summary Card -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl border {{ $stripeSettings['enabled'] ? 'border-emerald-500/60 shadow-md shadow-emerald-950/5' : 'border-slate-200 dark:border-slate-800 shadow-xs' }} p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 font-extrabold flex items-center justify-center text-sm shadow-xs">
                            S
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white font-heading">Stripe Gateway</h3>
                            <span class="text-[10px] text-slate-400 dark:text-slate-500">Credit / Debit Card, Apple Pay</span>
                        </div>
                    </div>

                    @if($stripeSettings['enabled'])
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            ACTIVE
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-100 dark:bg-rose-950 text-rose-800 dark:text-rose-300">
                            DISABLED
                        </span>
                    @endif
                </div>

                <div class="space-y-1.5 text-xs text-slate-600 dark:text-slate-400 py-2 border-y border-slate-100 dark:border-slate-800/80">
                    <div class="flex items-center justify-between">
                        <span>API Configuration:</span>
                        <span class="font-bold {{ $stripeSettings['is_configured'] ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                            {{ $stripeSettings['is_configured'] ? '✓ Configured' : '⚠️ Missing Secret Key' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Default Currency:</span>
                        <span class="font-mono font-bold text-slate-900 dark:text-white uppercase">{{ $stripeSettings['currency'] }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-2 flex items-center justify-between gap-2">
                <form action="{{ route('admin.payment-settings.test') }}" method="POST">
                    @csrf
                    <input type="hidden" name="gateway" value="stripe">
                    <button type="submit"
                            title="Send an API test ping to Stripe servers"
                            class="px-2.5 py-1.5 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition cursor-pointer flex items-center gap-1 shadow-xs">
                        <span>⚡</span> Test API
                    </button>
                </form>
                <form action="{{ route('admin.payment-settings.toggle') }}" method="POST">
                    @csrf
                    <input type="hidden" name="gateway" value="stripe">
                    <button type="submit"
                            class="px-3 py-1.5 rounded-xl text-xs font-bold transition cursor-pointer {{ $stripeSettings['enabled'] ? 'bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800' : 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-xs' }}">
                        {{ $stripeSettings['enabled'] ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>
            </div>
        </div>

        <!-- PayPal Summary Card -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl border {{ $paypalSettings['enabled'] ? 'border-sky-500/60 shadow-md shadow-sky-950/5' : 'border-slate-200 dark:border-slate-800 shadow-xs' }} p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-2xl bg-sky-50 dark:bg-sky-950/60 text-[#003087] dark:text-[#0079C1] font-sans font-black italic flex items-center justify-center text-sm shadow-xs">
                            PP
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white font-heading">PayPal Gateway</h3>
                            <span class="text-[10px] text-slate-400 dark:text-slate-500">PayPal Express & Wallet</span>
                        </div>
                    </div>

                    @if($paypalSettings['enabled'])
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-sky-100 dark:bg-sky-950 text-sky-800 dark:text-sky-300">
                            <span class="w-1.5 h-1.5 rounded-full bg-sky-500 animate-pulse"></span>
                            ACTIVE
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-100 dark:bg-rose-950 text-rose-800 dark:text-rose-300">
                            DISABLED
                        </span>
                    @endif
                </div>

                <div class="space-y-1.5 text-xs text-slate-600 dark:text-slate-400 py-2 border-y border-slate-100 dark:border-slate-800/80">
                    <div class="flex items-center justify-between">
                        <span>Environment Mode:</span>
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase {{ $paypalSettings['mode'] === 'live' ? 'bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300' : 'bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300' }}">
                            {{ $paypalSettings['mode'] }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>API Configuration:</span>
                        <span class="font-bold {{ $paypalSettings['is_configured'] ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                            {{ $paypalSettings['is_configured'] ? '✓ Configured' : '⚠️ Missing Credentials' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-2 flex items-center justify-between gap-2">
                <form action="{{ route('admin.payment-settings.test') }}" method="POST">
                    @csrf
                    <input type="hidden" name="gateway" value="paypal">
                    <button type="submit"
                            title="Send an OAuth token authentication request to PayPal"
                            class="px-2.5 py-1.5 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition cursor-pointer flex items-center gap-1 shadow-xs">
                        <span>⚡</span> Test API
                    </button>
                </form>
                <form action="{{ route('admin.payment-settings.toggle') }}" method="POST">
                    @csrf
                    <input type="hidden" name="gateway" value="paypal">
                    <button type="submit"
                            class="px-3 py-1.5 rounded-xl text-xs font-bold transition cursor-pointer {{ $paypalSettings['enabled'] ? 'bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800' : 'bg-sky-600 hover:bg-sky-700 text-white shadow-xs' }}">
                        {{ $paypalSettings['enabled'] ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Simulated / Demo Mode Summary Card -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-2xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center text-lg shadow-xs">
                            🧪
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white font-heading">Developer / Demo Mode</h3>
                            <span class="text-[10px] text-slate-400 dark:text-slate-500">Test pass without live gateways</span>
                        </div>
                    </div>

                    @if($simulatedEnabled)
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-purple-100 dark:bg-purple-950 text-purple-800 dark:text-purple-300">
                            ENABLED
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                            DISABLED
                        </span>
                    @endif
                </div>

                <div class="space-y-1.5 text-xs text-slate-600 dark:text-slate-400 py-2 border-y border-slate-100 dark:border-slate-800/80">
                    <p class="text-[11px] leading-relaxed">
                        Allows users or moderators to instantly activate any package on checkout without connecting to real bank card or PayPal accounts.
                    </p>
                </div>
            </div>

            <div class="mt-4 pt-2 flex items-center justify-between gap-2">
                <span class="text-[11px] text-slate-400">Demo toggle:</span>
                <form action="{{ route('admin.payment-settings.toggle') }}" method="POST">
                    @csrf
                    <input type="hidden" name="gateway" value="simulated">
                    <button type="submit"
                            class="px-3 py-1.5 rounded-xl text-xs font-bold transition cursor-pointer {{ $simulatedEnabled ? 'bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300' : 'bg-purple-600 hover:bg-purple-700 text-white shadow-xs' }}">
                        {{ $simulatedEnabled ? 'Disable Demo Mode' : 'Enable Demo Mode' }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Configuration Form -->
    <form action="{{ route('admin.payment-settings.update') }}" method="POST" class="space-y-8">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">

            <!-- STRIPE CONFIGURATION SECTION -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 sm:p-8 shadow-xs space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-600 text-white font-extrabold flex items-center justify-center text-sm shadow-md shadow-indigo-900/20">
                            S
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900 dark:text-white font-heading">Stripe Configuration</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Card payments via Stripe Elements & Checkout Sessions</p>
                        </div>
                    </div>

                    <!-- Activate/Deactivate Switch -->
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="stripe_enabled" value="1" class="sr-only peer" {{ $stripeSettings['enabled'] ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        <span class="ml-2.5 text-xs font-bold text-slate-700 dark:text-slate-300">
                            Active
                        </span>
                    </label>
                </div>

                <!-- Stripe Publishable Key -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                        Publishable Key (pk_test_... or pk_live_...) *
                    </label>
                    <input type="text"
                           name="stripe_key"
                           value="{{ old('stripe_key', $stripeSettings['key']) }}"
                           placeholder="pk_test_51..."
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs font-mono outline-none focus:ring-2 focus:ring-emerald-500">
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 block mt-1">Found in your Stripe Developer Dashboard under Developers &gt; API keys.</span>
                </div>

                <!-- Stripe Secret Key -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                            Secret Key (sk_test_... or sk_live_...) *
                        </label>
                        <button type="button" @click="showStripeSecret = !showStripeSecret" class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 hover:underline cursor-pointer">
                            <span x-text="showStripeSecret ? 'Hide Key' : 'Reveal Key'"></span>
                        </button>
                    </div>
                    <div class="relative">
                        <input :type="showStripeSecret ? 'text' : 'password'"
                               name="stripe_secret"
                               value="{{ old('stripe_secret', $stripeSettings['secret']) }}"
                               placeholder="sk_test_51..."
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs font-mono outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 block mt-1">Used to create checkout sessions and authenticate API requests from the server.</span>
                </div>

                <!-- Stripe Webhook Secret -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                        Webhook Signing Secret (whsec_...)
                    </label>
                    <input type="text"
                           name="stripe_webhook_secret"
                           value="{{ old('stripe_webhook_secret', $stripeSettings['webhook_secret']) }}"
                           placeholder="whsec_..."
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs font-mono outline-none focus:ring-2 focus:ring-emerald-500">
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 block mt-1">Required to cryptographically verify incoming Stripe webhook notifications.</span>
                </div>

                <!-- Stripe Currency -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                        Stripe Settlement Currency *
                    </label>
                    <select name="stripe_currency" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs font-bold outline-none focus:ring-2 focus:ring-emerald-500">
                        @foreach(['USD' => 'USD ($) - US Dollar', 'GBP' => 'GBP (£) - British Pound', 'EUR' => 'EUR (€) - Euro', 'CAD' => 'CAD ($) - Canadian Dollar', 'AUD' => 'AUD ($) - Australian Dollar', 'BDT' => 'BDT (৳) - Bangladeshi Taka', 'AED' => 'AED (د.إ) - UAE Dirham', 'SAR' => 'SAR (﷼) - Saudi Riyal'] as $code => $label)
                            <option value="{{ $code }}" {{ strtoupper($stripeSettings['currency']) === $code ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Webhook Endpoint Box -->
                <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 space-y-1.5">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold text-slate-700 dark:text-slate-300">Stripe Webhook URL:</span>
                        <button type="button"
                                id="btn-copy-stripe"
                                @click="copyText('{{ route('webhook.stripe') }}', 'btn-copy-stripe')"
                                class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-200 hover:bg-slate-100 cursor-pointer">
                            Copy URL
                        </button>
                    </div>
                    <code class="text-[11px] font-mono text-emerald-700 dark:text-emerald-400 break-all select-all block">
                        {{ route('webhook.stripe') }}
                    </code>
                </div>
            </div>

            <!-- PAYPAL CONFIGURATION SECTION -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 sm:p-8 shadow-xs space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-[#003087] text-white font-sans font-black italic flex items-center justify-center text-sm shadow-md shadow-sky-900/20">
                            PP
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900 dark:text-white font-heading">PayPal Configuration</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400">PayPal Checkout Orders v2 REST API integration</p>
                        </div>
                    </div>

                    <!-- Activate/Deactivate Switch -->
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="paypal_enabled" value="1" class="sr-only peer" {{ $paypalSettings['enabled'] ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-sky-600"></div>
                        <span class="ml-2.5 text-xs font-bold text-slate-700 dark:text-slate-300">
                            Active
                        </span>
                    </label>
                </div>

                <!-- PayPal Mode (Sandbox vs Live) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                        PayPal Environment Mode *
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-300 dark:border-slate-700 cursor-pointer hover:border-sky-500 transition">
                            <input type="radio" name="paypal_mode" value="sandbox" {{ $paypalSettings['mode'] === 'sandbox' ? 'checked' : '' }} class="text-sky-600 focus:ring-sky-500">
                            <div>
                                <span class="text-xs font-bold text-slate-900 dark:text-white block">🧪 Sandbox Mode</span>
                                <span class="text-[10px] text-slate-400">Testing credentials</span>
                            </div>
                        </label>
                        <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-300 dark:border-slate-700 cursor-pointer hover:border-sky-500 transition">
                            <input type="radio" name="paypal_mode" value="live" {{ $paypalSettings['mode'] === 'live' ? 'checked' : '' }} class="text-sky-600 focus:ring-sky-500">
                            <div>
                                <span class="text-xs font-bold text-slate-900 dark:text-white block">🚀 Production (Live)</span>
                                <span class="text-[10px] text-slate-400">Real card/account payments</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- PayPal Client ID -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                        Client ID *
                    </label>
                    <input type="text"
                           name="paypal_client_id"
                           value="{{ old('paypal_client_id', $paypalSettings['client_id']) }}"
                           placeholder="AX... or client id"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs font-mono outline-none focus:ring-2 focus:ring-sky-500">
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 block mt-1">Obtained from PayPal Developer Portal &gt; My Apps &gt; REST API Apps.</span>
                </div>

                <!-- PayPal Secret Key -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                            Client Secret *
                        </label>
                        <button type="button" @click="showPayPalSecret = !showPayPalSecret" class="text-[11px] font-bold text-sky-600 dark:text-sky-400 hover:underline cursor-pointer">
                            <span x-text="showPayPalSecret ? 'Hide Secret' : 'Reveal Secret'"></span>
                        </button>
                    </div>
                    <div class="relative">
                        <input :type="showPayPalSecret ? 'text' : 'password'"
                               name="paypal_client_secret"
                               value="{{ old('paypal_client_secret', $paypalSettings['client_secret']) }}"
                               placeholder="EL..."
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs font-mono outline-none focus:ring-2 focus:ring-sky-500">
                    </div>
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 block mt-1">Kept confidential. Used for server-to-server OAuth token generation.</span>
                </div>

                <!-- PayPal Webhook ID -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                        PayPal Webhook ID (Optional)
                    </label>
                    <input type="text"
                           name="paypal_webhook_id"
                           value="{{ old('paypal_webhook_id', $paypalSettings['webhook_id']) }}"
                           placeholder="e.g. 5HY44985..."
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs font-mono outline-none focus:ring-2 focus:ring-sky-500">
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 block mt-1">Configured in PayPal webhook settings for asynchronous event verification.</span>
                </div>

                <!-- PayPal Currency -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                        PayPal Transaction Currency *
                    </label>
                    <select name="paypal_currency" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs font-bold outline-none focus:ring-2 focus:ring-sky-500">
                        @foreach(['USD' => 'USD ($) - US Dollar', 'GBP' => 'GBP (£) - British Pound', 'EUR' => 'EUR (€) - Euro', 'CAD' => 'CAD ($) - Canadian Dollar', 'AUD' => 'AUD ($) - Australian Dollar'] as $code => $label)
                            <option value="{{ $code }}" {{ strtoupper($paypalSettings['currency']) === $code ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- PayPal Webhook URL Box -->
                <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 space-y-1.5">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold text-slate-700 dark:text-slate-300">PayPal Webhook URL:</span>
                        <button type="button"
                                id="btn-copy-paypal"
                                @click="copyText('{{ route('webhook.paypal') }}', 'btn-copy-paypal')"
                                class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-200 hover:bg-slate-100 cursor-pointer">
                            Copy URL
                        </button>
                    </div>
                    <code class="text-[11px] font-mono text-sky-700 dark:text-sky-400 break-all select-all block">
                        {{ route('webhook.paypal') }}
                    </code>
                </div>
            </div>

        </div>

        <!-- Testing & Developer Bypass Mode Card -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 sm:p-8 shadow-xs">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-purple-100 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xl shrink-0">
                        🧪
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white font-heading">Simulated Developer / Demo Mode Payment Option</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            Display the "Developer / Demo Mode" option on the seeker checkout screen so tests can complete without live credit cards.
                        </p>
                    </div>
                </div>

                <label class="relative inline-flex items-center cursor-pointer shrink-0">
                    <input type="checkbox" name="simulated_payment_enabled" value="1" class="sr-only peer" {{ $simulatedEnabled ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                    <span class="ml-2.5 text-xs font-bold text-slate-700 dark:text-slate-300">
                        Allow Demo Payments
                    </span>
                </label>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <button type="submit"
                    class="px-8 py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-lg shadow-emerald-950/30 transition cursor-pointer flex items-center gap-2 active:scale-95">
                <span>💾</span>
                <span>Save Payment Gateway Configurations</span>
            </button>
        </div>
    </form>

</div>
@endsection

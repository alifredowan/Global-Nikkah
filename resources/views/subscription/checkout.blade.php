@extends('layouts.app')

@section('title', 'Checkout — ' . $planDetails['name'])

@section('content')
<div class="py-12 bg-[var(--bg-canvas)] min-h-screen">
    <div class="max-w-xl mx-auto px-4 sm:px-6">
        <div class="card-ledger p-7 sm:p-10 bg-[var(--bg-surface)]"
             x-data="{
                cycle: 'one_time_marriage',
                basePrice: {{ (float) ($planDetails['one_time_price'] ?? $planDetails['monthly_price']) }},
                paymentMethod: '{{ old('payment_method', ($stripeEnabled ?? true) ? 'stripe' : (($paypalEnabled ?? false) ? 'paypal' : (($simulatedEnabled ?? false) ? 'simulated' : ''))) }}',
                promoCode: '{{ old('promo_code', '') }}',
                promoApplied: false,
                promoLoading: false,
                discountPercentage: 0,
                promoMessage: '',
                promoError: '{{ $errors->first('promo_code') }}',
                currentBasePrice() {
                    return this.basePrice;
                },
                currentDiscount() {
                    if (!this.promoApplied) return 0;
                    return (this.currentBasePrice() * this.discountPercentage) / 100;
                },
                currentFinalPrice() {
                    return Math.max(0, this.currentBasePrice() - this.currentDiscount());
                },
                async checkPromo() {
                    if (!this.promoCode.trim()) {
                        this.promoError = 'Please enter a coupon or promo code.';
                        return;
                    }
                    this.promoLoading = true;
                    this.promoError = '';
                    this.promoMessage = '';
                    try {
                        let res = await fetch('{{ route('subscription.validate-promo') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                promo_code: this.promoCode,
                                plan: '{{ $plan }}',
                                billing_cycle: this.cycle
                            })
                        });
                        let data = await res.json();
                        if (res.ok && data.valid) {
                            this.promoApplied = true;
                            this.discountPercentage = data.discount_percentage;
                            this.promoMessage = data.message;
                        } else {
                            this.promoApplied = false;
                            this.promoError = data.message || 'Invalid promo code.';
                        }
                    } catch (e) {
                        this.promoError = 'Network error verifying promo code.';
                    } finally {
                        this.promoLoading = false;
                    }
                }
             }">

            <!-- Header -->
            <div class="text-center mb-8 space-y-2">
                <span class="badge-status text-[var(--accent-text)] bg-[var(--accent-subtle)] border border-[var(--accent-border)] text-xs inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><circle cx="12" cy="12" r="8"/><circle cx="12" cy="12" r="3"/></svg>
                    <span>Single Marriage Pass</span>
                </span>
                <h1 class="font-serif text-2xl sm:text-3xl font-semibold text-[var(--text-primary)]">
                    Acquire {{ $planDetails['name'] }}
                </h1>
                <p class="text-xs text-[var(--text-secondary)]">
                    One-time payment. Valid until your confirmed Nikah milestone.
                </p>
            </div>

            @if(request()->query('status') === 'cancelled')
                <div class="mb-5 p-3.5 rounded-[var(--radius-md)] bg-[var(--accent-subtle)] border border-[var(--accent-border)] text-[var(--accent-text)] text-xs flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    <span>Checkout was cancelled. No charges were made. Select your preferred payment method below whenever you are ready.</span>
                </div>
            @endif

            @if($errors->any() && !$errors->has('promo_code'))
                <div class="mb-5 p-3.5 rounded-[var(--radius-md)] bg-[var(--color-danger-bg)] border border-[var(--color-danger-border)] text-[var(--color-danger)] text-xs space-y-1">
                    @foreach($errors->all() as $err)
                        <p>{{ $err }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('subscription.process') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="plan" value="{{ $plan }}">
                <input type="hidden" name="billing_cycle" value="one_time_marriage">

                <!-- Pass Validity & Ethical Note -->
                <div class="p-4 rounded-[var(--radius-md)] bg-[var(--bg-canvas)] border border-[var(--border-default)] space-y-1.5">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-[var(--text-primary)] flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-[var(--brand-primary)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            <span>Single Marriage Pass Terms</span>
                        </span>
                        <span class="badge-status text-[var(--color-success)] bg-[var(--color-success-bg)] border border-[var(--color-success-border)] text-[10px]">
                            Active Until Nikah
                        </span>
                    </div>
                    <p class="text-xs text-[var(--text-secondary)] leading-relaxed">
                        This is a single one-time payment of <strong class="font-semibold text-[var(--text-primary)]">${{ number_format($planDetails['monthly_price'], 2) }}</strong>. You will <strong>never be auto-charged</strong> again. Your pass remains active until you and your spouse record your confirmed Nikah.
                    </p>
                </div>

                <!-- Promo Code -->
                <div>
                    <label for="promo_code" class="block text-xs font-semibold text-[var(--text-primary)] mb-1.5">Promotional Discount Code</label>
                    <div class="flex gap-2">
                        <input type="text" id="promo_code" name="promo_code" x-model="promoCode" placeholder="e.g. SPECIAL30"
                               @keydown.enter.prevent="checkPromo()"
                               class="flex-1 h-11 px-3.5 rounded-[var(--radius-md)] border border-[var(--border-default)] bg-[var(--bg-canvas)] text-sm text-[var(--text-primary)] placeholder:text-[var(--text-muted)] focus:outline-none focus:border-[var(--brand-primary)] uppercase font-mono font-medium">
                        <button type="button" @click="checkPromo()" :disabled="promoLoading"
                                class="btn-secondary h-11 px-4 text-xs font-semibold shrink-0 cursor-pointer disabled:opacity-50">
                            <span x-show="!promoLoading">Apply</span>
                            <span x-show="promoLoading">Checking...</span>
                        </button>
                    </div>

                    <div x-show="promoMessage" x-cloak class="mt-2 p-2.5 rounded-[var(--radius-sm)] bg-[var(--color-success-bg)] border border-[var(--color-success-border)] text-[var(--color-success)] text-xs font-medium flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                        <span x-text="promoMessage"></span>
                    </div>

                    <div x-show="promoError" x-cloak class="mt-2 p-2.5 rounded-[var(--radius-sm)] bg-[var(--color-danger-bg)] border border-[var(--color-danger-border)] text-[var(--color-danger)] text-xs font-medium flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <span x-text="promoError"></span>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="p-4 rounded-[var(--radius-md)] bg-[var(--bg-canvas)] border border-[var(--border-default)] space-y-2 text-xs">
                    <div class="flex items-center justify-between text-[var(--text-secondary)]">
                        <span>One-Time Marriage Pass ({{ $planDetails['name'] }}):</span>
                        <span class="font-semibold text-[var(--text-primary)]" :class="promoApplied ? 'line-through text-[var(--text-muted)]' : ''">
                            $<span x-text="currentBasePrice().toFixed(2)"></span>
                        </span>
                    </div>

                    <div x-show="promoApplied" x-cloak class="flex items-center justify-between text-[var(--color-success)] font-medium">
                        <span>Promotional Discount (<span x-text="discountPercentage"></span>%):</span>
                        <span>-$<span x-text="currentDiscount().toFixed(2)"></span></span>
                    </div>

                    <div class="pt-2 border-t border-[var(--border-subtle)] flex items-center justify-between text-sm font-semibold text-[var(--text-primary)]">
                        <span>Total Due Today:</span>
                        <span class="font-serif text-lg text-[var(--brand-primary)]">
                            $<span x-text="currentFinalPrice().toFixed(2)"></span>
                        </span>
                    </div>
                </div>

                <!-- Payment Method Selector -->
                <div>
                    <label class="block text-xs font-semibold text-[var(--text-primary)] mb-2">
                        Select Payment Method
                    </label>

                    <input type="hidden" name="payment_method" :value="paymentMethod">

                    <div class="space-y-3">
                        @if($stripeEnabled ?? true)
                            <div @click="paymentMethod = 'stripe'"
                                 :class="paymentMethod === 'stripe'
                                    ? 'border-[var(--brand-primary)] bg-[var(--accent-subtle)] ring-1 ring-[var(--brand-primary)]'
                                    : 'border-[var(--border-default)] bg-[var(--bg-canvas)] hover:border-[var(--border-strong)]'"
                                 class="p-4 rounded-[var(--radius-md)] border cursor-pointer transition">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-[var(--radius-sm)] bg-[var(--brand-primary)] text-white flex items-center justify-center shrink-0">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm font-semibold text-[var(--text-primary)]">Credit or Debit Card</span>
                                                <span class="badge-status text-[var(--accent-text)] bg-[var(--bg-surface)] border border-[var(--accent-border)] text-[10px]">Stripe</span>
                                            </div>
                                            <p class="text-xs text-[var(--text-muted)] mt-0.5">Visa, Mastercard, American Express, Apple Pay</p>
                                        </div>
                                    </div>
                                    <span class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition"
                                          :class="paymentMethod === 'stripe' ? 'border-[var(--brand-primary)] bg-[var(--brand-primary)] text-white' : 'border-[var(--border-default)]'">
                                        <span x-show="paymentMethod === 'stripe'" class="w-2 h-2 rounded-full bg-white"></span>
                                    </span>
                                </div>
                            </div>
                        @endif

                        @if($paypalEnabled ?? true)
                            <div @click="paymentMethod = 'paypal'"
                                 :class="paymentMethod === 'paypal'
                                    ? 'border-[var(--brand-primary)] bg-[var(--accent-subtle)] ring-1 ring-[var(--brand-primary)]'
                                    : 'border-[var(--border-default)] bg-[var(--bg-canvas)] hover:border-[var(--border-strong)]'"
                                 class="p-4 rounded-[var(--radius-md)] border cursor-pointer transition">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-[var(--radius-sm)] bg-[#003087] text-white flex items-center justify-center font-bold text-sm shrink-0">
                                            PP
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm font-semibold text-[var(--text-primary)]">PayPal Express</span>
                                                <span class="badge-status text-[var(--accent-text)] bg-[var(--bg-surface)] border border-[var(--accent-border)] text-[10px]">Buyer Protection</span>
                                            </div>
                                            <p class="text-xs text-[var(--text-muted)] mt-0.5">Pay via PayPal balance, bank account, or card</p>
                                        </div>
                                    </div>
                                    <span class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition"
                                          :class="paymentMethod === 'paypal' ? 'border-[var(--brand-primary)] bg-[var(--brand-primary)] text-white' : 'border-[var(--border-default)]'">
                                        <span x-show="paymentMethod === 'paypal'" class="w-2 h-2 rounded-full bg-white"></span>
                                    </span>
                                </div>
                            </div>
                        @endif

                        @if($simulatedEnabled ?? true)
                            <div @click="paymentMethod = 'simulated'"
                                 :class="paymentMethod === 'simulated'
                                    ? 'border-[var(--brand-primary)] bg-[var(--bg-surface)] ring-1 ring-[var(--brand-primary)]'
                                    : 'border-dashed border-[var(--border-default)] bg-[var(--bg-canvas)] hover:border-[var(--border-strong)]'"
                                 class="p-3.5 rounded-[var(--radius-md)] border cursor-pointer transition">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2.5">
                                        <svg class="w-4 h-4 text-[var(--text-muted)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                                        <div>
                                            <span class="text-xs font-semibold text-[var(--text-primary)]">Developer Test Mode</span>
                                            <p class="text-[11px] text-[var(--text-muted)]">Instantly activate pass without payment gateway</p>
                                        </div>
                                    </div>
                                    <span class="w-4 h-4 rounded-full border-2 flex items-center justify-center transition"
                                          :class="paymentMethod === 'simulated' ? 'border-[var(--brand-primary)] bg-[var(--brand-primary)] text-white' : 'border-[var(--border-default)]'">
                                        <span x-show="paymentMethod === 'simulated'" class="w-1.5 h-1.5 rounded-full bg-white"></span>
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-primary w-full h-12 text-sm font-semibold tracking-wide cursor-pointer flex items-center justify-center gap-2">
                    <template x-if="paymentMethod === 'stripe'">
                        <span class="flex items-center gap-2">
                            <span>Proceed to Stripe Checkout</span>
                            <span aria-hidden="true">&rarr;</span>
                        </span>
                    </template>
                    <template x-if="paymentMethod === 'paypal'">
                        <span class="flex items-center gap-2">
                            <span>Pay with PayPal</span>
                            <span aria-hidden="true">&rarr;</span>
                        </span>
                    </template>
                    <template x-if="paymentMethod === 'simulated'">
                        <span class="flex items-center gap-2">
                            <span>Activate Marriage Pass (Demo Mode)</span>
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                        </span>
                    </template>
                </button>

                <!-- Trust signals -->
                <div class="pt-3 border-t border-[var(--border-subtle)] grid grid-cols-2 gap-3 text-center text-[11px] text-[var(--text-muted)]">
                    <div class="flex items-center justify-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-[var(--brand-primary)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                        <span>Instant Activation</span>
                    </div>
                    <div class="flex items-center justify-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-[var(--brand-primary)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        <span>Valid Until Confirmed Nikah</span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

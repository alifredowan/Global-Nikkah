@extends('layouts.app')

@section('title', 'Register - Global Nikkah')

@section('content')
<div class="py-10 bg-[var(--bg-canvas)] min-h-screen"
     x-data="{
        step: 1,
        role: '{{ old('role', 'seeker') }}',
        name: '{{ old('name', '') }}',
        email: '{{ old('email', '') }}',
        phone: '{{ old('phone', '') }}',
        gender: '{{ old('gender', '') }}',
        dob: '{{ old('dob', '') }}',
        password: '',
        password_confirmation: '',
        get isStep1Valid() {
            return this.role === 'seeker' || this.role === 'wali';
        },
        get isStep2Valid() {
            return this.name.trim().length >= 2 && this.email.includes('@') && this.gender !== '' && this.dob.length === 10;
        },
        saveDraft() {
            try {
                localStorage.setItem('nikah_reg_draft', JSON.stringify({
                    role: this.role,
                    name: this.name,
                    email: this.email,
                    gender: this.gender,
                    dob: this.dob
                }));
                alert('Your registration progress has been saved locally. You can resume anytime.');
            } catch(e) {}
        }
     }">
    <div class="max-w-xl mx-auto px-4 sm:px-6">
        <div class="card-ledger p-6 sm:p-8 bg-[var(--bg-surface)] shadow-md">

            <!-- Header -->
            <div class="border-b border-[var(--border-subtle)] pb-4 mb-6 flex items-start justify-between">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-[var(--text-primary)]">
                        <span x-show="role === 'seeker'">Create Candidate Profile</span>
                        <span x-show="role === 'wali'" style="display: none;">Register as Guardian (Wali)</span>
                    </h1>
                    <p class="text-xs text-[var(--text-secondary)] mt-0.5">
                        <span x-show="role === 'seeker'">Begin your matrimonial journey with sincere intentions.</span>
                        <span x-show="role === 'wali'" style="display: none;">Register to supervise and chaperone your family member.</span>
                    </p>
                </div>
                <!-- Save Progress Button -->
                <button type="button" @click="saveDraft()"
                        class="text-[11px] font-semibold text-[var(--accent-text)] hover:underline flex items-center gap-1 shrink-0"
                        title="Save progress locally">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    <span>Save &amp; Continue Later</span>
                </button>
            </div>

            <!-- Multi-Step Progress Indicator -->
            <div class="mb-6">
                <div class="flex items-center justify-between text-xs font-semibold text-[var(--text-secondary)] mb-2">
                    <span :class="step >= 1 ? 'text-[var(--accent-text)] font-bold' : ''">1. Account Role</span>
                    <span :class="step >= 2 ? 'text-[var(--accent-text)] font-bold' : ''">2. Personal Details</span>
                    <span :class="step >= 3 ? 'text-[var(--accent-text)] font-bold' : ''">3. Security</span>
                </div>
                <div class="h-1.5 w-full bg-[var(--bg-muted)] rounded-full overflow-hidden">
                    <div class="h-full bg-[var(--accent-primary)] transition-all duration-200"
                         :style="'width: ' + (step === 1 ? '33.33%' : (step === 2 ? '66.66%' : '100%'))"></div>
                </div>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-[var(--radius-sm)] bg-[var(--color-danger-bg)] border border-[var(--color-danger-border)] text-[var(--color-danger)] text-xs">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="role" :value="role">

                <!-- ===================================================== -->
                <!-- STEP 1: ROLE SELECTION                                -->
                <!-- ===================================================== -->
                <div x-show="step === 1" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-[var(--text-secondary)] uppercase tracking-wider mb-2">I am registering as:</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <button type="button" @click="role = 'seeker'"
                                    :class="role === 'seeker' ? 'border-[var(--accent-primary)] bg-[var(--accent-subtle)] ring-1 ring-[var(--accent-primary)]' : 'border-[var(--border-default)] bg-[var(--bg-surface)] hover:bg-[var(--bg-subtle)]'"
                                    class="p-4 rounded-[var(--radius-sm)] border text-left transition flex flex-col justify-between cursor-pointer">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <svg class="w-4 h-4 text-[var(--accent-primary)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                        <span class="font-bold text-sm text-[var(--text-primary)]">Candidate / Seeker</span>
                                    </div>
                                    <p class="text-[11px] text-[var(--text-secondary)] leading-relaxed">I am searching for a spouse for myself (Groom or Bride).</p>
                                </div>
                            </button>

                            <button type="button" @click="role = 'wali'"
                                    :class="role === 'wali' ? 'border-[var(--accent-primary)] bg-[var(--accent-subtle)] ring-1 ring-[var(--accent-primary)]' : 'border-[var(--border-default)] bg-[var(--bg-surface)] hover:bg-[var(--bg-subtle)]'"
                                    class="p-4 rounded-[var(--radius-sm)] border text-left transition flex flex-col justify-between cursor-pointer">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <svg class="w-4 h-4 text-[var(--accent-primary)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                        <span class="font-bold text-sm text-[var(--text-primary)]">Guardian / Wali</span>
                                    </div>
                                    <p class="text-[11px] text-[var(--text-secondary)] leading-relaxed">I am registering to represent and chaperone my daughter, sister, or ward.</p>
                                </div>
                            </button>
                        </div>
                    </div>

                    <div class="pt-3 flex justify-end">
                        <button type="button" @click="step = 2" class="btn-primary text-xs font-bold px-6">
                            <span>Continue to Step 2</span>
                            <span>&rarr;</span>
                        </button>
                    </div>
                </div>

                <!-- ===================================================== -->
                <!-- STEP 2: PERSONAL IDENTITY & CONTACT                   -->
                <!-- ===================================================== -->
                <div x-show="step === 2" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">
                            <span x-show="role === 'seeker'">Full Legal Name</span>
                            <span x-show="role === 'wali'" style="display: none;">Guardian's Legal Name</span>
                        </label>
                        <input type="text" name="name" x-model="name" required
                               class="w-full h-10 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-sm focus:ring-2 focus:ring-[var(--accent-primary)] outline-none"
                               :placeholder="role === 'seeker' ? 'Full legal name' : 'Guardian legal name'">
                        <span x-show="name.length > 0 && name.length < 2" class="text-[11px] text-[var(--color-danger)] block mt-0.5">Please provide your full legal name.</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">Email Address</label>
                            <input type="email" name="email" x-model="email" required
                                   class="w-full h-10 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-sm focus:ring-2 focus:ring-[var(--accent-primary)] outline-none"
                                   placeholder="your.email@example.com">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">Phone Number</label>
                            <div class="flex gap-2"
                                 x-data="{
                                    open: false,
                                    search: '',
                                    selected: '{{ old('phone_country', '') }}',
                                    countries: @js($countries),
                                    get filtered() {
                                        const q = this.search.trim().toLowerCase();
                                        return Object.entries(this.countries).filter(([code, c]) => {
                                            return !q || c.name.toLowerCase().includes(q) || c.dial_code.includes(q) || code.toLowerCase().includes(q);
                                        });
                                    },
                                    get selectedCountry() {
                                        return this.selected ? this.countries[this.selected] : null;
                                    },
                                    choose(code) {
                                        this.selected = code;
                                        this.open = false;
                                        this.search = '';
                                    }
                                 }"
                                 @click.outside="open = false">
                                <div class="relative w-24 shrink-0">
                                    <input type="hidden" name="phone_country" :value="selected">
                                    <button type="button" @click="open = !open"
                                            class="w-full h-10 px-2 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-xs text-[var(--text-primary)] flex items-center justify-between gap-1">
                                        <span x-text="selectedCountry ? ('+' + selectedCountry.dial_code) : 'Code'" class="truncate"></span>
                                        <svg class="w-3 h-3 text-[var(--text-muted)] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <div x-show="open" x-cloak
                                         class="absolute z-30 mt-1 w-60 max-h-60 overflow-hidden rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] shadow-lg flex flex-col">
                                        <div class="p-2 border-b border-[var(--border-subtle)]">
                                            <input type="text" x-model="search" @click.stop placeholder="Search country..."
                                                   class="w-full px-2 py-1 text-xs border border-[var(--border-default)] rounded-[var(--radius-xs)] outline-none">
                                        </div>
                                        <div class="overflow-y-auto">
                                            <template x-for="[code, c] in filtered" :key="code">
                                                <button type="button" @click="choose(code)"
                                                        class="w-full text-left px-2.5 py-1.5 text-xs hover:bg-[var(--bg-subtle)] flex items-center justify-between">
                                                    <span class="truncate" x-text="c.name"></span>
                                                    <span class="text-[var(--text-muted)]" x-text="'+' + c.dial_code"></span>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                                <input type="tel" name="phone" x-model="phone"
                                       class="flex-1 min-w-0 h-10 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-sm focus:ring-2 focus:ring-[var(--accent-primary)] outline-none"
                                       placeholder="123456789">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">Gender</label>
                            <select name="gender" x-model="gender" required
                                    class="w-full h-10 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs outline-none">
                                <option value="">Select Gender</option>
                                <option value="male">Brother (Male)</option>
                                <option value="female">Sister (Female)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">Date of Birth (18+ Enforced)</label>
                            <input type="text" name="dob" x-model="dob" required
                                   inputmode="numeric" placeholder="dd/mm/yyyy" maxlength="10"
                                   pattern="\d{2}/\d{2}/\d{4}"
                                   x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '').replace(/^(\d{2})(\d)/, '$1/$2').replace(/^(\d{2})\/(\d{2})(\d)/, '$1/$2/$3').slice(0, 10); dob = $el.value"
                                   class="w-full h-10 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs outline-none">
                            <span class="text-[10px] text-[var(--text-muted)] block mt-0.5">Format: dd/mm/yyyy</span>
                        </div>
                    </div>

                    <!-- Seeker Marital Status -->
                    <div x-show="role === 'seeker'">
                        <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">Marital Status</label>
                        <select name="marital_status" :required="role === 'seeker'"
                                class="w-full h-10 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs outline-none">
                            <option value="never_married">Single / Never Married</option>
                            <option value="divorced">Divorced</option>
                            <option value="widowed">Widowed</option>
                            <option value="annulled">Annulled</option>
                        </select>
                    </div>

                    <!-- Wali Relationship -->
                    <div x-show="role === 'wali'" style="display: none;">
                        <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">Relationship to Candidate / Ward</label>
                        <select name="relationship_type"
                                class="w-full h-10 px-3 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-[var(--text-primary)] text-xs outline-none">
                            <option value="father">Father</option>
                            <option value="brother">Brother</option>
                            <option value="uncle">Uncle (Paternal / Maternal)</option>
                            <option value="grandfather">Grandfather</option>
                            <option value="other_mahram">Other Mahram / Legal Guardian</option>
                        </select>
                    </div>

                    <div class="pt-3 flex justify-between gap-3">
                        <button type="button" @click="step = 1" class="btn-secondary text-xs">
                            <span>&larr; Back</span>
                        </button>
                        <button type="button" @click="step = 3" class="btn-primary text-xs font-bold px-6">
                            <span>Continue to Step 3</span>
                            <span>&rarr;</span>
                        </button>
                    </div>
                </div>

                <!-- ===================================================== -->
                <!-- STEP 3: SECURITY & AFFIRMATION                        -->
                <!-- ===================================================== -->
                <div x-show="step === 3" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">Password</label>
                            <div class="relative" x-data="{ showPass: false }">
                                <input :type="showPass ? 'text' : 'password'" name="password" x-model="password" required
                                       class="w-full h-10 px-3 pr-10 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-xs text-[var(--text-primary)] outline-none focus:ring-2 focus:ring-[var(--accent-primary)]"
                                       placeholder="••••••••">
                                <button type="button" @click="showPass = !showPass"
                                        class="absolute right-2.5 top-1/2 -translate-y-1/2 text-[var(--text-muted)] hover:text-[var(--text-primary)] p-1 cursor-pointer"
                                        aria-label="Toggle password visibility">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">Confirm Password</label>
                            <div class="relative" x-data="{ showConfirm: false }">
                                <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" x-model="password_confirmation" required
                                       class="w-full h-10 px-3 pr-10 rounded-[var(--radius-sm)] border border-[var(--border-default)] bg-[var(--bg-surface)] text-xs text-[var(--text-primary)] outline-none focus:ring-2 focus:ring-[var(--accent-primary)]"
                                       placeholder="••••••••">
                                <button type="button" @click="showConfirm = !showConfirm"
                                        class="absolute right-2.5 top-1/2 -translate-y-1/2 text-[var(--text-muted)] hover:text-[var(--text-primary)] p-1 cursor-pointer"
                                        aria-label="Toggle password visibility">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Inline Password Match Validation -->
                    <div x-show="password.length > 0 && password_confirmation.length > 0" class="text-xs">
                        <span x-show="password === password_confirmation" class="text-[var(--color-success)] flex items-center gap-1 font-medium">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                            <span>Passwords match.</span>
                        </span>
                        <span x-show="password !== password_confirmation" class="text-[var(--color-danger)] flex items-center gap-1 font-medium">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                            <span>Passwords do not match yet.</span>
                        </span>
                    </div>

                    @if($turnstileEnabled)
                        <div class="flex justify-center pt-2">
                            <div class="cf-turnstile" data-sitekey="{{ $turnstileSiteKey }}"></div>
                        </div>
                    @endif

                    <div class="pt-2">
                        <label class="flex items-start gap-2.5 text-xs text-[var(--text-secondary)] cursor-pointer">
                            <input type="checkbox" name="terms" required class="mt-0.5 rounded-[var(--radius-xs)] text-[var(--accent-primary)] focus:ring-[var(--accent-primary)]">
                            <span>
                                I affirm that I am using this service strictly for marriage (Nikah), I agree to conduct communications with dignity and Islamic etiquette, and accept the <a href="{{ route('terms') }}" class="underline text-[var(--accent-text)] font-semibold">Terms of Service</a> and <a href="{{ route('privacy') }}" class="underline text-[var(--accent-text)] font-semibold">Privacy Policy</a>.
                            </span>
                        </label>
                    </div>

                    <div class="pt-3 flex justify-between gap-3">
                        <button type="button" @click="step = 2" class="btn-secondary text-xs">
                            <span>&larr; Back</span>
                        </button>
                        <button type="submit" class="btn-primary text-xs font-bold px-6">
                            <span x-show="role === 'seeker'">Create Candidate Profile</span>
                            <span x-show="role === 'wali'" style="display: none;">Create Guardian Account</span>
                        </button>
                    </div>
                </div>

            </form>

            <div class="text-center mt-6 pt-4 text-xs text-[var(--text-muted)] border-t border-[var(--border-subtle)]">
                Already registered? <a href="{{ route('login') }}" class="font-bold text-[var(--accent-text)] hover:underline">Sign in to your account</a>
            </div>
        </div>
    </div>
</div>

@if($turnstileEnabled)
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
@endif
@endsection

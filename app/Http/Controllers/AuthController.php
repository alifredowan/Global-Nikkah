<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Profile;
use App\Models\Subscription;
use App\Models\User;
use App\Rules\Turnstile;
use App\Services\TurnstileService;
use App\Services\WaliWorkflowService;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password as PasswordBroker;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Propaganistas\LaravelPhone\Rules\Phone;

class AuthController extends Controller
{
    public function showRegister(TurnstileService $turnstileService): View
    {
        return view('auth.register', [
            'countries' => config('countries'),
            'turnstileEnabled' => $turnstileService->isEnabled(),
            'turnstileSiteKey' => $turnstileService->getSiteKey(),
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        $maxBirthDate = Carbon::now()->subYears(18)->format('d/m/Y');

        $role = $request->input('role', 'seeker');
        if (! in_array($role, ['seeker', 'wali'], true)) {
            $role = 'seeker';
        }

        $normalizedEmail = strtolower(trim((string) $request->input('email')));
        $normalizedPhone = $request->filled('phone') ? trim((string) $request->input('phone')) : null;

        $request->merge([
            'role' => $role,
            'email' => $normalizedEmail,
            'phone' => $normalizedPhone,
        ]);

        $rules = [
            'role' => ['required', 'in:seeker,wali'],
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->where(function ($query) use ($normalizedEmail) {
                    return $query->whereRaw('LOWER(email) = ?', [$normalizedEmail]);
                }),
            ],
            'phone_country' => ['required_with:phone', 'string', 'size:2'],
            'phone' => ['nullable', 'string', new Phone, 'unique:users,phone'],
            'gender' => ['required', 'in:male,female'],
            'dob' => ['required', 'date_format:d/m/Y', "before_or_equal:{$maxBirthDate}"],
            'password' => ['required', 'confirmed', Password::defaults()],
            'terms' => ['accepted'],
            'cf-turnstile-response' => [new Turnstile],
        ];

        if ($role === 'seeker') {
            $rules['marital_status'] = ['required', 'in:never_married,divorced,widowed,annulled'];
        } else {
            $rules['marital_status'] = ['nullable', 'in:never_married,divorced,widowed,annulled'];
            $rules['relationship_type'] = ['nullable', 'string', 'in:father,brother,uncle,grandfather,other_mahram'];
        }

        $validated = $request->validate($rules, [
            'email.unique' => 'An account with this email address already exists. Please sign in or use forgot password.',
            'phone.phone' => 'Please enter a valid phone number for the selected country.',
            'dob.date_format' => 'Please enter the date of birth in dd/mm/yyyy format.',
            'dob.before_or_equal' => 'You must be at least 18 years old to register for Global Nikkah.',
        ]);

        $normalizedPhoneNumber = $validated['phone']
            ? phone($validated['phone'], $validated['phone_country'])->formatE164()
            : null;

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $normalizedPhoneNumber,
            'gender' => $validated['gender'],
            'dob' => Carbon::createFromFormat('d/m/Y', $validated['dob']),
            'marital_status' => $validated['marital_status'] ?? 'never_married',
            'password' => Hash::make($validated['password']),
            'role' => $role,
            'is_active' => true,
            'email_verified_at' => null,
        ]);

        try {
            $user->sendEmailVerificationNotification();
        } catch (\Throwable $e) {
            Log::warning('Email verification dispatch failed: '.$e->getMessage());
        }

        if ($role === 'seeker') {
            // Initialize user profile
            Profile::create([
                'user_id' => $user->id,
                'wali_required' => ($validated['gender'] === 'female'),
                'completeness_percentage' => 15,
            ]);

            // Initialize free subscription tier
            Subscription::create([
                'user_id' => $user->id,
                'plan' => 'free',
                'status' => 'active',
                'starts_at' => now(),
            ]);

            AuditLog::record($user->id, 'user_registered', 'User', $user->id, ['role' => 'seeker', 'email' => $user->email]);

            // User must approve email verification first before accessing the platform
            return redirect()->route('verification.notice')
                ->with('registered_email', $user->email)
                ->with('success', 'Assalamu Alaikum! Your candidate account has been registered. Please check your email inbox and click the verification link to activate your account.');
        }

        // Wali Registration
        AuditLog::record($user->id, 'wali_registered', 'User', $user->id, ['role' => 'wali', 'email' => $user->email]);

        app(WaliWorkflowService::class)->autoLinkWaliUser($user);

        // Wali must approve email verification first before accessing the platform
        return redirect()->route('verification.notice')
            ->with('registered_email', $user->email)
            ->with('success', 'Assalamu Alaikum! Your Guardian account has been registered. Please check your email inbox and click the verification link to activate your account.');
    }

    public function showLogin(TurnstileService $turnstileService): View
    {
        return view('auth.login', [
            'turnstileEnabled' => $turnstileService->isEnabled(),
            'turnstileSiteKey' => $turnstileService->getSiteKey(),
        ]);
    }

    public function login(Request $request): RedirectResponse
    {
        $normalizedEmail = strtolower(trim((string) $request->input('email')));
        $request->merge(['email' => $normalizedEmail]);

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'cf-turnstile-response' => [new Turnstile],
        ]);
        unset($credentials['cf-turnstile-response']);
        $credentials['email'] = $normalizedEmail;

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if (! $user->hasVerifiedEmail()) {
                Auth::logout();

                return redirect()->route('verification.notice')
                    ->with('unverified_email', $user->email)
                    ->withErrors([
                        'email' => 'Your email address has not been approved yet. Please check your inbox and click the verification link to activate your account.',
                    ]);
            }

            if (! $user->is_active) {
                Auth::logout();

                return back()->withErrors(['email' => 'This account has been deactivated or suspended.']);
            }

            $request->session()->regenerate();

            AuditLog::record($user->id, 'user_login');

            // Auto-link any pending guardian links for this user
            app(WaliWorkflowService::class)->autoLinkWaliUser($user);

            if ($user->isSuperAdmin() || $user->isModerator()) {
                return redirect()->route('admin.dashboard');
            }

            if ($user->isWali()) {
                return redirect()->route('wali.dashboard');
            }

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        $userId = Auth::id();
        AuditLog::record($userId, 'user_logout');

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }

    public function deactivate(Request $request): RedirectResponse
    {
        $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $user = Auth::user();
        $user->update([
            'is_active' => false,
            'deactivation_reason' => $request->reason,
        ]);

        AuditLog::record($user->id, 'account_deactivated', 'User', $user->id, ['reason' => $request->reason]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing')->with('success', 'Your account has been deactivated.');
    }

    public function showForgotPassword(): View
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        $normalizedEmail = strtolower(trim((string) $request->input('email')));
        $request->merge(['email' => $normalizedEmail]);

        $request->validate([
            'email' => ['required', 'email'],
        ]);

        try {
            $status = PasswordBroker::sendResetLink(
                $request->only('email')
            );
        } catch (\Throwable $e) {
            Log::error('Password reset email sending failed: '.$e->getMessage(), [
                'email' => $normalizedEmail,
                'exception' => $e,
            ]);

            return back()->withInput($request->only('email'))->withErrors([
                'email' => 'Unable to send password reset email due to a mail delivery error. Please check your mail settings or contact support.',
            ]);
        }

        if ($status === PasswordBroker::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        return back()->withErrors(['email' => __($status)]);
    }

    public function showResetPassword(Request $request, string $token): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $normalizedEmail = strtolower(trim((string) $request->input('email')));
        $request->merge(['email' => $normalizedEmail]);

        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $status = PasswordBroker::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
                AuditLog::record($user->id, 'password_reset', 'User', $user->id);
            }
        );

        if ($status === PasswordBroker::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', __($status));
        }

        return back()->withErrors(['email' => __($status)]);
    }

    /**
     * Show email verification notice page.
     */
    public function showVerificationNotice(): View
    {
        return view('auth.verify-email');
    }

    /**
     * Handle email verification link.
     */
    public function verifyEmail(Request $request, int $id, string $hash): RedirectResponse
    {
        if (! $request->hasValidSignature()) {
            return redirect()->route('verification.notice')->with('error', 'This email verification link has expired or is invalid. Please request a new link below.');
        }

        $user = User::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return redirect()->route('verification.notice')->with('error', 'Invalid verification link parameters.');
        }

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            $user->update(['is_verified' => true, 'is_active' => true]);
            AuditLog::record($user->id, 'email_verified', 'User', $user->id, ['email' => $user->email]);
        }

        if (! Auth::check()) {
            Auth::login($user);
        }

        if ($user->isWali()) {
            return redirect()->route('wali.link')->with('success', 'Alhamdulillah! Your email address has been approved and verified. Welcome to Global Nikkah!');
        }

        return redirect()->route('profile.edit')->with('success', 'Alhamdulillah! Your email address has been approved and verified. Welcome to Global Nikkah! You can now proceed to set up your profile.');
    }

    /**
     * Resend verification notification email.
     */
    public function resendVerification(Request $request): RedirectResponse
    {
        $email = $request->input('email') ?: session('registered_email') ?: session('unverified_email');
        $user = Auth::user();

        if (! $user && $email) {
            $user = User::where('email', strtolower(trim((string) $email)))->first();
        }

        if (! $user) {
            return back()->withErrors(['email' => 'Please enter the email address you used during registration.']);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('info', 'Your email address is already verified. Please sign in to your account.');
        }

        try {
            $user->sendEmailVerificationNotification();
        } catch (\Throwable $e) {
            Log::warning('Resend email verification failed: '.$e->getMessage());
        }

        return back()
            ->with('registered_email', $user->email)
            ->with('success', 'A fresh verification email has been dispatched to '.$user->email.'. Please check your inbox and spam folder.');
    }
}

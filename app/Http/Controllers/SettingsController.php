<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\PlatformSetting;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class SettingsController extends Controller
{
    /**
     * Display the settings management view for Super Admins and Normal Users.
     */
    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $profile = null;

        if ($user->isSeeker() || $user->isWali()) {
            $profile = $user->profile ?? Profile::firstOrCreate(['user_id' => $user->id]);
        }

        $platformSettings = null;
        if ($user->isSuperAdmin() || $user->hasPermission('manage_settings')) {
            $platformSettings = PlatformSetting::all();
        }

        return view('settings.index', compact('user', 'profile', 'platformSettings'));
    }

    /**
     * Update account and profile data (Name, Email, Phone, Demographics).
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email:rfc',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone' => ['nullable', 'string', 'max:25'],
        ];

        if ($user->isSeeker()) {
            $rules['gender'] = ['nullable', 'in:male,female'];
            $rules['dob'] = ['nullable', 'date', 'before:-18 years'];
            $rules['marital_status'] = ['nullable', 'string', 'in:never_married,divorced,widowed,annulled'];
            $rules['bio'] = ['nullable', 'string', 'max:1000'];
            $rules['city'] = ['nullable', 'string', 'max:100'];
            $rules['country'] = ['nullable', 'string', 'max:100'];
        }

        $validated = $request->validate($rules, [
            'dob.before' => 'You must be at least 18 years old to use this platform.',
            'email.unique' => 'This email address is already in use by another account.',
        ]);

        $userData = [
            'name' => $validated['name'],
            'email' => strtolower(trim($validated['email'])),
            'phone' => $validated['phone'] ?? null,
        ];
        $emailChanged = $user->email !== $userData['email'];

        if ($emailChanged) {
            $userData['email_verified_at'] = null;
        }

        if ($user->isSeeker()) {
            if (isset($validated['gender'])) {
                $userData['gender'] = $validated['gender'];
            }
            if (isset($validated['dob'])) {
                $userData['dob'] = $validated['dob'];
            }
            if (isset($validated['marital_status'])) {
                $userData['marital_status'] = $validated['marital_status'];
            }

            // Update seeker profile fields
            $profile = $user->profile ?? Profile::create(['user_id' => $user->id]);
            $profile->update([
                'bio' => $validated['bio'] ?? $profile->bio,
                'city' => $validated['city'] ?? $profile->city,
                'country' => $validated['country'] ?? $profile->country,
            ]);
            $profile->updateCompleteness();
        }

        $user->update($userData);

        if ($emailChanged) {
            $user->tokens()->delete();
            $user->sendEmailVerificationNotification();
        }

        AuditLog::record($user->id, 'account_data_updated', 'User', $user->id, [
            'name' => $user->name,
            'email' => $user->email,
        ]);

        $message = $emailChanged
            ? 'Your account details were updated. Please verify your new email address to continue using the platform.'
            : 'Your personal account information has been updated successfully.';

        return back()->with('success', $message);
    }

    /**
     * Update user password with current password verification.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.current_password' => 'The provided current password does not match our records.',
            'password.confirmed' => 'The new password confirmation does not match.',
            'password.min' => 'The new password must be at least 8 characters long.',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);
        $user->tokens()->delete();

        AuditLog::record($user->id, 'account_password_updated', 'User', $user->id);

        return back()->with('success', 'Your password has been changed successfully.');
    }

    public function revokeApiSessions(): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $user->tokens()->delete();

        AuditLog::record($user->id, 'api_sessions_revoked');

        return back()->with('success', 'All API sessions have been revoked.');
    }

    /**
     * Update user privacy and matrimonial preferences.
     */
    public function updatePreferences(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        // Incognito browsing
        $isIncognito = $request->boolean('is_incognito');
        $user->update(['is_incognito' => $isIncognito]);

        // Wali requirement for seekers
        if ($user->isSeeker() && $user->profile) {
            $user->profile->update([
                'wali_required' => $request->boolean('wali_required'),
            ]);
        }

        AuditLog::record($user->id, 'account_preferences_updated', 'User', $user->id, [
            'is_incognito' => $isIncognito,
        ]);

        return back()->with('success', 'Your preferences have been saved successfully.');
    }
}

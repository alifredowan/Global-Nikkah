<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Requests\Profile\UploadPhotoRequest;
use App\Models\AuditLog;
use App\Models\Interest;
use App\Models\PhotoAccessGrant;
use App\Models\Profile;
use App\Models\User;
use App\Models\VerificationRequest;
use App\Services\ImageOptimizationService;
use App\Services\SavedSearchAlertService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        protected ImageOptimizationService $imageOptimizationService
    ) {}

    public function show(): View|RedirectResponse
    {
        $user = Auth::user();

        if ($user->isSuperAdmin() || $user->isModerator()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isWali()) {
            return redirect()->route('wali.dashboard');
        }

        $profile = $user->profile ?? Profile::create(['user_id' => $user->id]);
        $profile->load(['photos', 'photoAccessGrants.grantedToUser']);
        $profile->updateCompleteness();

        return view('profile.show', compact('user', 'profile'));
    }

    public function edit(): View|RedirectResponse
    {
        $user = Auth::user();

        if ($user->isSuperAdmin() || $user->isModerator()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isWali()) {
            return redirect()->route('wali.dashboard');
        }

        $profile = $user->profile ?? Profile::create(['user_id' => $user->id]);

        return view('profile.edit', compact('user', 'profile'));
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user->isSuperAdmin() || $user->isModerator()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isWali()) {
            return redirect()->route('wali.dashboard');
        }

        $profile = $user->profile ?? Profile::create(['user_id' => $user->id]);
        $validated = $request->validated();

        $partnerPreferences = [
            'age_min' => $validated['pref_age_min'] ?? null,
            'age_max' => $validated['pref_age_max'] ?? null,
            'preferred_sect' => $validated['pref_sect'] ?? null,
            'preferred_location' => $validated['pref_location'] ?? null,
            'preferred_education' => $validated['pref_education'] ?? null,
        ];

        $profile->fill($validated);
        $profile->partner_preferences = $partnerPreferences;
        $profile->wali_required = $request->boolean('wali_required');
        $profile->save();

        $profile->updateCompleteness();
        app(SavedSearchAlertService::class)->notifyFor($user);

        AuditLog::record($user->id, 'profile_updated', 'Profile', $profile->id);

        return redirect()->route('profile.show')->with('success', 'Your profile details have been updated successfully!');
    }

    public function uploadPhoto(UploadPhotoRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $profile = $user->profile;

        if ($profile->photos()->count() >= 6) {
            return back()->withErrors(['photo' => 'You can upload a maximum of 6 photos (FR-2.2).']);
        }

        $path = $this->imageOptimizationService->store($request->file('photo'), 'profile-photos');

        $isFirstPhoto = ! $profile->photos()->exists();

        $profile->photos()->create([
            'file_path' => $path,
            'is_primary' => $isFirstPhoto,
            'is_private' => $request->boolean('is_private'),
            'is_blurred' => true, // default blurred per FR-2.2
            'moderation_status' => 'approved', // auto-approved for dev, flagged in production
        ]);

        $profile->updateCompleteness();

        return back()->with('success', 'Photo uploaded! By default, photos remain blurred until reciprocal permission is granted (FR-2.2).');
    }

    public function setPrimaryPhoto(int $photoId): RedirectResponse
    {
        $profile = Auth::user()->profile;
        $photo = $profile->photos()->findOrFail($photoId);

        $profile->photos()->update(['is_primary' => false]);
        $photo->update(['is_primary' => true]);

        return back()->with('success', 'Primary profile photo updated.');
    }

    public function deletePhoto(int $photoId): RedirectResponse
    {
        $profile = Auth::user()->profile;
        $photo = $profile->photos()->findOrFail($photoId);

        foreach (['private', 'public'] as $disk) {
            if (Storage::disk($disk)->exists($photo->file_path)) {
                Storage::disk($disk)->delete($photo->file_path);
            }
        }

        $cacheFile = storage_path('app/blurred-photos/blurred_'.$photo->id.'.jpg');
        if (file_exists($cacheFile)) {
            @unlink($cacheFile);
        }

        $photo->delete();
        $profile->updateCompleteness();

        return back()->with('success', 'Photo removed.');
    }

    public function submitVerification(Request $request): RedirectResponse
    {
        $request->validate([
            'document_type' => ['required', 'in:national_id,passport,driving_license'],
            'document' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'selfie' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $user = Auth::user();

        if (VerificationRequest::where('user_id', $user->id)->where('status', 'pending')->exists()) {
            return back()->withErrors([
                'document' => 'You already have a verification request awaiting review.',
            ]);
        }

        $docPath = $request->file('document')->store('verifications', 'local');
        $selfiePath = $request->hasFile('selfie') ? $request->file('selfie')->store('verifications', 'local') : null;

        VerificationRequest::create([
            'user_id' => $user->id,
            'document_type' => $request->document_type,
            'document_path' => $docPath,
            'selfie_path' => $selfiePath,
            'status' => 'pending',
        ]);

        AuditLog::record($user->id, 'verification_submitted', 'User', $user->id);

        return back()->with('success', 'Verification documents submitted! Our moderation team will review them to grant your Verified badge (FR-1.4).');
    }

    public function grantPhotoAccess(int $userId): RedirectResponse
    {
        $user = Auth::user();
        $profile = $user->profile;
        $recipient = User::query()
            ->whereKey($userId)
            ->where('role', 'seeker')
            ->where('is_active', true)
            ->first();

        if (! $profile || ! $recipient || $recipient->id === $user->id) {
            return back()->withErrors(['photo' => 'Photo access can only be granted to an active candidate.']);
        }

        if ($user->hasBlockedRelationshipWith($recipient)) {
            return back()->withErrors(['photo' => 'Photo access cannot be granted because this profile is blocked.']);
        }

        PhotoAccessGrant::updateOrCreate([
            'profile_id' => $profile->id,
            'granted_to_user_id' => $userId,
        ], [
            'status' => 'active',
        ]);
        AuditLog::record($user->id, 'photo_access_granted', 'User', $recipient->id);

        return back()->with('success', 'Photo access granted! The recipient can now view your unblurred photos.');
    }

    public function revokePhotoAccess(int $userId): RedirectResponse
    {
        $user = Auth::user();
        $profile = $user->profile;
        $grant = $profile?->photoAccessGrants()
            ->where('granted_to_user_id', $userId)
            ->where('status', 'active')
            ->first();

        if (! $grant) {
            return back()->withErrors(['photo' => 'No active photo access grant was found for this user.']);
        }

        $grant->update(['status' => 'revoked']);
        AuditLog::record($user->id, 'photo_access_revoked', 'User', $userId);

        return back()->with('success', 'Photo access revoked.');
    }

    public function biodata(?int $userId = null): View|RedirectResponse
    {
        $currentUser = Auth::user();

        if ($userId === null || $userId === $currentUser->id) {
            $targetUser = $currentUser;
            $profile = $currentUser->profile ?? Profile::create(['user_id' => $currentUser->id]);
            $isOwner = true;
            $isPhotoVisible = true;
        } else {
            $targetUser = User::with(['profile.photos', 'profile.primaryPhoto'])->findOrFail($userId);
            $profile = $targetUser->profile;

            if (! $profile || ! $targetUser->is_active || $targetUser->role !== 'seeker') {
                return redirect()->route('discovery.index')->with('error', 'Profile not available.');
            }

            // Gating: If not owner, user must be Pro OR have mutual accepted interest
            $hasAcceptedInterest = Interest::where(function ($q) use ($currentUser, $targetUser) {
                $q->where('sender_id', $currentUser->id)->where('recipient_id', $targetUser->id);
            })->orWhere(function ($q) use ($currentUser, $targetUser) {
                $q->where('sender_id', $targetUser->id)->where('recipient_id', $currentUser->id);
            })->where('status', 'accepted')->exists();

            if (! $currentUser->isPro() && ! $hasAcceptedInterest) {
                return redirect()->route('subscription.pricing')
                    ->with('warning', 'Printing printable Islamic matrimonial biodatas for candidate suitors is an exclusive Pro feature. Upgrade to print candidate biodatas for your family consultation.');
            }

            $isOwner = false;
            $isPhotoVisible = $profile->isPhotoVisibleTo($currentUser);
        }

        $activeWaliLink = $targetUser->waliLinksAsSeeker()
            ->where('status', 'active')
            ->first();

        return view('profile.biodata', compact('targetUser', 'profile', 'isOwner', 'isPhotoVisible', 'activeWaliLink', 'currentUser'));
    }
}

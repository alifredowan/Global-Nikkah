<?php

namespace Tests\Feature;

use App\Models\Photo;
use App\Models\Profile;
use App\Models\User;
use App\Models\VerificationRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AccountDeletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_account_deletion_requires_confirmation_and_removes_personal_data(): void
    {
        Storage::fake('private');
        Storage::fake('public');

        $user = User::create([
            'name' => 'Delete Me',
            'email' => 'delete-me@example.com',
            'password' => 'Password123!',
            'role' => 'seeker',
            'gender' => 'male',
            'dob' => now()->subYears(30)->toDateString(),
            'marital_status' => 'never_married',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $profile = Profile::create(['user_id' => $user->id]);
        $photo = Photo::create([
            'profile_id' => $profile->id,
            'file_path' => 'profile-photos/delete-me.jpg',
            'is_primary' => true,
            'moderation_status' => 'approved',
        ]);
        VerificationRequest::create([
            'user_id' => $user->id,
            'document_type' => 'passport',
            'document_path' => 'verifications/delete-me.pdf',
            'selfie_path' => 'verifications/delete-me.jpg',
            'status' => 'pending',
        ]);
        Storage::disk('private')->put($photo->file_path, 'photo');
        Storage::disk('private')->put('verifications/delete-me.pdf', 'document');
        Storage::disk('private')->put('verifications/delete-me.jpg', 'selfie');

        $response = $this->actingAs($user)->post(route('account.delete'), [
            'password' => 'Password123!',
            'reason' => 'I no longer need this account.',
            'confirmation' => '1',
        ]);

        $response->assertRedirect(route('landing'));
        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('profiles', ['id' => $profile->id]);
        $this->assertDatabaseMissing('photos', ['id' => $photo->id]);
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'account_deleted',
            'target_id' => $user->id,
        ]);
        Storage::disk('private')->assertMissing($photo->file_path);
        Storage::disk('private')->assertMissing('verifications/delete-me.pdf');
        Storage::disk('private')->assertMissing('verifications/delete-me.jpg');
    }

    public function test_profile_uploads_are_stored_on_private_disk(): void
    {
        Storage::fake('private');
        Storage::fake('public');

        $user = User::create([
            'name' => 'Private Photo User',
            'email' => 'private-photo@example.com',
            'password' => 'Password123!',
            'role' => 'seeker',
            'gender' => 'male',
            'dob' => now()->subYears(30)->toDateString(),
            'marital_status' => 'never_married',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        Profile::create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post(route('profile.photos.upload'), [
            'photo' => UploadedFile::fake()->image('profile.jpg'),
        ]);

        $response->assertRedirect();
        $photo = Photo::query()->where('profile_id', $user->profile->id)->firstOrFail();
        Storage::disk('private')->assertExists($photo->file_path);
        Storage::disk('public')->assertMissing($photo->file_path);
    }
}

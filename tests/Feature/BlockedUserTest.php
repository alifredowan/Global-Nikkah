<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Interest;
use App\Models\Photo;
use App\Models\PhotoAccessGrant;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlockedUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_blocking_a_candidate_removes_them_from_discovery_and_can_be_reversed(): void
    {
        $seeker = $this->createSeeker('male', 'blocker@example.com');
        $candidate = $this->createSeeker('female', 'blocked@example.com');
        Profile::create(['user_id' => $seeker->id]);
        Profile::create(['user_id' => $candidate->id, 'country' => 'Italy']);

        $blockResponse = $this->actingAs($seeker)->post(route('blocked-users.store', $candidate->id));

        $blockResponse->assertRedirect(route('discovery.index'));
        $this->assertTrue($seeker->blockedUsers()->whereKey($candidate->id)->exists());
        $profileResponse = $this->actingAs($seeker)->get(route('discovery.show', $candidate->id));
        $profileResponse->assertRedirect(route('discovery.index'));
        $profileResponse->assertSessionHas('error', 'This profile is blocked from your discovery experience.');

        $unblockResponse = $this->actingAs($seeker)->delete(route('blocked-users.destroy', $candidate->id));

        $unblockResponse->assertRedirect();
        $this->assertFalse($seeker->blockedUsers()->whereKey($candidate->id)->exists());
    }

    public function test_blocking_prevents_interest_exchange_in_both_directions(): void
    {
        $blocker = $this->createSeeker('male', 'interest-blocker@example.com');
        $blocked = $this->createSeeker('female', 'interest-blocked@example.com');

        $blocker->blockedUsers()->attach($blocked->id);

        $sendResponse = $this->actingAs($blocker)->post(route('interests.send', $blocked->id));
        $sendResponse->assertSessionHas('error');
        $this->assertDatabaseMissing('interests', [
            'sender_id' => $blocker->id,
            'recipient_id' => $blocked->id,
        ]);

        $interest = Interest::create([
            'sender_id' => $blocked->id,
            'recipient_id' => $blocker->id,
            'status' => 'pending',
            'wali_approval_status' => 'not_required',
        ]);

        $respondResponse = $this->actingAs($blocker)->post(route('interests.respond', $interest->id), [
            'action' => 'decline',
        ]);

        $respondResponse->assertSessionHas('error');
        $this->assertDatabaseHas('interests', [
            'id' => $interest->id,
            'status' => 'pending',
        ]);
    }

    public function test_blocking_prevents_existing_conversation_access_and_messages(): void
    {
        $blocker = $this->createSeeker('male', 'chat-blocker@example.com');
        $blocked = $this->createSeeker('female', 'chat-blocked@example.com');
        $blocker->blockedUsers()->attach($blocked->id);

        $interest = Interest::create([
            'sender_id' => $blocker->id,
            'recipient_id' => $blocked->id,
            'status' => 'accepted',
            'wali_approval_status' => 'not_required',
        ]);
        $conversation = Conversation::create([
            'interest_id' => $interest->id,
            'status' => 'active',
        ]);
        ConversationParticipant::create([
            'conversation_id' => $conversation->id,
            'user_id' => $blocker->id,
            'role' => 'seeker',
        ]);
        ConversationParticipant::create([
            'conversation_id' => $conversation->id,
            'user_id' => $blocked->id,
            'role' => 'seeker',
        ]);

        $pageResponse = $this->actingAs($blocker)->get(route('chat.show', $conversation->id));
        $pageResponse->assertRedirect(route('chat.index'));
        $pageResponse->assertSessionHas('error');

        $messageResponse = $this->actingAs($blocker)->postJson(route('chat.send', $conversation->id), [
            'body' => 'This message must not be sent.',
        ]);
        $messageResponse->assertForbidden();
        $this->assertDatabaseMissing('messages', [
            'conversation_id' => $conversation->id,
            'sender_id' => $blocker->id,
        ]);
    }

    public function test_blocking_revokes_existing_private_photo_access(): void
    {
        $blocker = $this->createSeeker('male', 'photo-blocker@example.com');
        $blocked = $this->createSeeker('female', 'photo-blocked@example.com');
        $blockerProfile = Profile::create(['user_id' => $blocker->id]);
        $photo = Photo::create([
            'profile_id' => $blockerProfile->id,
            'file_path' => 'profile-photos/private.jpg',
            'is_primary' => true,
            'is_blurred' => false,
            'moderation_status' => 'approved',
        ]);

        $grant = PhotoAccessGrant::create([
            'profile_id' => $blockerProfile->id,
            'granted_to_user_id' => $blocked->id,
            'status' => 'active',
        ]);

        $this->actingAs($blocker)->post(route('blocked-users.store', $blocked->id));

        $this->assertDatabaseHas('photo_access_grants', [
            'id' => $grant->id,
            'status' => 'revoked',
        ]);
        $this->actingAs($blocked)->get(route('photos.view', $photo->id))
            ->assertHeader('Content-Type', 'image/svg+xml');

        $this->actingAs($blocker)->delete(route('blocked-users.destroy', $blocked->id));

        $this->assertDatabaseHas('photo_access_grants', [
            'id' => $grant->id,
            'status' => 'revoked',
        ]);
    }

    public function test_photo_access_can_be_granted_and_revoked_by_the_profile_owner(): void
    {
        $owner = $this->createSeeker('male', 'photo-owner@example.com');
        $recipient = $this->createSeeker('female', 'photo-recipient@example.com');
        Profile::create(['user_id' => $owner->id]);
        Profile::create(['user_id' => $recipient->id]);

        $grantResponse = $this->actingAs($owner)->post(route('profile.photos.grant', $recipient->id));
        $grantResponse->assertSessionHas('success');

        $grant = PhotoAccessGrant::where('profile_id', $owner->profile->id)
            ->where('granted_to_user_id', $recipient->id)
            ->firstOrFail();
        $this->assertSame('active', $grant->status);
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $owner->id,
            'action' => 'photo_access_granted',
            'target_id' => $recipient->id,
        ]);

        $revokeResponse = $this->actingAs($owner)->delete(route('profile.photos.revoke', $recipient->id));
        $revokeResponse->assertSessionHas('success');
        $this->assertDatabaseHas('photo_access_grants', [
            'id' => $grant->id,
            'status' => 'revoked',
        ]);
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $owner->id,
            'action' => 'photo_access_revoked',
            'target_id' => $recipient->id,
        ]);
    }

    private function createSeeker(string $gender, string $email): User
    {
        return User::create([
            'name' => 'Safety Test User',
            'email' => $email,
            'password' => bcrypt('Password123!'),
            'role' => 'seeker',
            'gender' => $gender,
            'dob' => now()->subYears(28)->toDateString(),
            'marital_status' => 'never_married',
            'is_active' => true,
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);
    }
}

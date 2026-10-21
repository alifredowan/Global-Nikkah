<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Interest;
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

<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Interest;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileMessagingTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_shows_send_message_button_when_mutual_interest_is_accepted(): void
    {
        $userA = $this->createSeeker('male', 'user.a@example.com');
        $userB = $this->createSeeker('female', 'user.b@example.com');

        Profile::create(['user_id' => $userA->id, 'country' => 'United Kingdom']);
        Profile::create(['user_id' => $userB->id, 'country' => 'United Kingdom']);

        $interest = Interest::create([
            'sender_id' => $userA->id,
            'recipient_id' => $userB->id,
            'status' => 'accepted',
            'wali_approval_status' => 'not_required',
            'responded_at' => now(),
        ]);

        $conversation = Conversation::create([
            'interest_id' => $interest->id,
            'status' => 'active',
        ]);

        ConversationParticipant::create([
            'conversation_id' => $conversation->id,
            'user_id' => $userA->id,
            'role' => 'seeker',
        ]);

        ConversationParticipant::create([
            'conversation_id' => $conversation->id,
            'user_id' => $userB->id,
            'role' => 'seeker',
        ]);

        $response = $this->actingAs($userA)->get(route('discovery.show', $userB->id));

        $response->assertOk();
        $response->assertSee('Send Message');
        $response->assertSee(route('chat.show', $conversation->id));
        $response->assertSee('Mutual Halal Interest Confirmed!');
    }

    public function test_chat_user_route_redirects_to_active_conversation(): void
    {
        $userA = $this->createSeeker('male', 'usera.direct@example.com');
        $userB = $this->createSeeker('female', 'userb.direct@example.com');

        Profile::create(['user_id' => $userA->id]);
        Profile::create(['user_id' => $userB->id]);

        $interest = Interest::create([
            'sender_id' => $userA->id,
            'recipient_id' => $userB->id,
            'status' => 'accepted',
            'wali_approval_status' => 'not_required',
            'responded_at' => now(),
        ]);

        $conversation = Conversation::create([
            'interest_id' => $interest->id,
            'status' => 'active',
        ]);

        ConversationParticipant::create([
            'conversation_id' => $conversation->id,
            'user_id' => $userA->id,
            'role' => 'seeker',
        ]);

        ConversationParticipant::create([
            'conversation_id' => $conversation->id,
            'user_id' => $userB->id,
            'role' => 'seeker',
        ]);

        $response = $this->actingAs($userA)->get(route('chat.user', $userB->id));

        $response->assertRedirect(route('chat.show', $conversation->id));
    }

    public function test_chat_user_route_redirects_with_info_if_no_accepted_interest(): void
    {
        $userA = $this->createSeeker('male', 'usera.noint@example.com');
        $userB = $this->createSeeker('female', 'userb.noint@example.com');

        Profile::create(['user_id' => $userA->id]);
        Profile::create(['user_id' => $userB->id]);

        $response = $this->actingAs($userA)->get(route('chat.user', $userB->id));

        $response->assertRedirect(route('discovery.show', $userB->id));
        $response->assertSessionHas('info');
    }

    private function createSeeker(string $gender, string $email): User
    {
        return User::create([
            'name' => 'Test Seeker',
            'email' => $email,
            'password' => bcrypt('Password123!'),
            'role' => 'seeker',
            'gender' => $gender,
            'dob' => now()->subYears(26)->toDateString(),
            'marital_status' => 'never_married',
            'is_active' => true,
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiPrivacyTest extends TestCase
{
    use RefreshDatabase;

    public function test_candidate_api_response_hides_private_identity_fields(): void
    {
        $viewer = User::factory()->create([
            'gender' => 'male',
            'role' => 'seeker',
        ]);
        $candidate = User::factory()->create([
            'gender' => 'female',
            'role' => 'seeker',
            'email' => 'candidate@example.com',
            'phone' => '+15550001111',
            'dob' => '1995-04-12',
        ]);
        Profile::create(['user_id' => $viewer->id]);
        Profile::create(['user_id' => $candidate->id]);
        $token = $viewer->createToken('privacy-test-token');

        $response = $this->withToken($token->plainTextToken)
            ->getJson('/api/v1/profiles/'.$candidate->id);

        $response->assertOk()
            ->assertJsonMissingPath('user.email')
            ->assertJsonMissingPath('user.phone')
            ->assertJsonMissingPath('user.dob')
            ->assertJsonPath('user.name', $candidate->name);
    }

    public function test_user_can_revoke_all_api_sessions(): void
    {
        $user = User::factory()->create();
        $currentToken = $user->createToken('current-session');
        $otherToken = $user->createToken('other-session');

        $response = $this->withToken($currentToken->plainTextToken)
            ->postJson('/api/v1/auth/logout-all');

        $response->assertOk()
            ->assertJsonPath('message', 'All API sessions have been revoked');
        $this->assertDatabaseMissing('personal_access_tokens', ['id' => $currentToken->accessToken->id]);
        $this->assertDatabaseMissing('personal_access_tokens', ['id' => $otherToken->accessToken->id]);
    }
}

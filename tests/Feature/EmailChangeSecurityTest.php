<?php

namespace Tests\Feature;

use App\Models\PhotoAccessGrant;
use App\Models\Profile;
use App\Models\User;
use App\Notifications\WelcomeVerificationNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class EmailChangeSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_changing_email_requires_reverification_and_revokes_api_tokens(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'old@example.com',
            'email_verified_at' => now(),
        ]);
        $token = $user->createToken('test-token');

        $response = $this->actingAs($user)->post(route('settings.profile.update'), [
            'name' => $user->name,
            'email' => 'new@example.com',
            'phone' => $user->phone,
        ]);

        $response->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'new@example.com',
            'email_verified_at' => null,
        ]);
        $this->assertDatabaseMissing('personal_access_tokens', ['id' => $token->accessToken->id]);
        Notification::assertSentTo($user, WelcomeVerificationNotification::class);
    }

    public function test_deactivation_revokes_tokens_and_private_photo_access(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $recipient = User::factory()->create(['gender' => 'female']);
        $profile = Profile::create(['user_id' => $user->id]);
        $grant = PhotoAccessGrant::create([
            'profile_id' => $profile->id,
            'granted_to_user_id' => $recipient->id,
            'status' => 'active',
        ]);
        $token = $user->createToken('test-token');

        $response = $this->actingAs($user)->post(route('account.deactivate'), [
            'reason' => 'I no longer wish to use the service.',
        ]);

        $response->assertRedirect(route('landing'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_active' => false,
        ]);
        $this->assertDatabaseMissing('personal_access_tokens', ['id' => $token->accessToken->id]);
        $this->assertDatabaseHas('photo_access_grants', [
            'id' => $grant->id,
            'status' => 'revoked',
        ]);
    }

    public function test_settings_can_revoke_all_api_sessions(): void
    {
        $user = User::factory()->create();
        $currentToken = $user->createToken('current-session');
        $otherToken = $user->createToken('other-session');

        $response = $this->actingAs($user)->post(route('settings.api-sessions.revoke'));

        $response->assertRedirect()
            ->assertSessionHas('success', 'All API sessions have been revoked.');
        $this->assertDatabaseMissing('personal_access_tokens', ['id' => $currentToken->accessToken->id]);
        $this->assertDatabaseMissing('personal_access_tokens', ['id' => $otherToken->accessToken->id]);
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'action' => 'api_sessions_revoked',
        ]);
    }
}

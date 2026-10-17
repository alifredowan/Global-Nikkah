<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WaliLink;
use App\Notifications\WaliConnectionApprovedNotification;
use App\Notifications\WaliConnectionRequestNotification;
use App\Notifications\WelcomeVerificationNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class WaliEmailApprovalAndVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_registration_dispatches_welcome_verification_email(): void
    {
        Notification::fake();

        $response = $this->post('/register', [
            'name' => 'Fatima Zahra',
            'email' => 'fatima@example.com',
            'phone' => '+15551239999',
            'gender' => 'female',
            'dob' => '1998-05-15',
            'marital_status' => 'never_married',
            'password' => 'SecurePass123!',
            'password_confirmation' => 'SecurePass123!',
            'terms' => '1',
            'role' => 'seeker',
        ]);

        $response->assertRedirect(route('profile.edit'));

        $user = User::where('email', 'fatima@example.com')->first();
        $this->assertNotNull($user);
        $this->assertNull($user->email_verified_at);

        Notification::assertSentTo($user, WelcomeVerificationNotification::class);
    }

    public function test_user_can_verify_email_via_signed_url(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
            'is_verified' => false,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addHours(24),
            [
                'id' => $user->id,
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );

        $response = $this->get($verificationUrl);

        $response->assertRedirect(route('profile.show'));
        $user->refresh();
        $this->assertNotNull($user->email_verified_at);
        $this->assertTrue($user->is_verified);
    }

    public function test_seeker_linking_wali_dispatches_wali_connection_request_email(): void
    {
        Notification::fake();

        $seeker = User::factory()->create(['gender' => 'female']);
        $waliUser = User::factory()->create(['gender' => 'male', 'email' => 'brother.wali@example.com']);

        $response = $this->actingAs($seeker)->post('/wali/link', [
            'wali_name' => 'Brother Tariq',
            'relationship_type' => 'brother',
            'permission_level' => 'approve_required',
            'wali_email' => 'brother.wali@example.com',
            'wali_phone' => '+15550001111',
        ]);

        $response->assertSessionHas('success');

        $link = WaliLink::where('seeker_user_id', $seeker->id)->first();
        $this->assertNotNull($link);
        $this->assertEquals('pending', $link->status);
        $this->assertNotNull($link->invite_token);

        Notification::assertSentTo($waliUser, WaliConnectionRequestNotification::class);
    }

    public function test_wali_can_view_invitation_page_via_token(): void
    {
        $seeker = User::factory()->create(['gender' => 'female', 'name' => 'Amina Seeker']);
        $link = WaliLink::create([
            'seeker_user_id' => $seeker->id,
            'wali_name' => 'Father Farooq',
            'wali_email' => 'farooq@example.com',
            'relationship_type' => 'father',
            'permission_level' => 'approve_required',
            'status' => 'pending',
            'invite_token' => 'secure-invite-token-456',
        ]);

        $response = $this->get(route('wali.invite.show', 'secure-invite-token-456'));

        $response->assertStatus(200);
        $response->assertSee('Amina Seeker');
        $response->assertSee('Father');
        $response->assertSee('Global Nikkah');
        $response->assertSeeText('Accept & Confirm Guardian Connection');
    }

    public function test_wali_accepting_invitation_activates_link_and_notifies_seeker(): void
    {
        Notification::fake();

        $seeker = User::factory()->create(['gender' => 'female', 'name' => 'Amina Seeker']);
        $wali = User::factory()->create(['gender' => 'male', 'name' => 'Father Farooq', 'email' => 'farooq@example.com']);

        $link = WaliLink::create([
            'seeker_user_id' => $seeker->id,
            'wali_name' => 'Father Farooq',
            'wali_email' => 'farooq@example.com',
            'relationship_type' => 'father',
            'permission_level' => 'approve_required',
            'status' => 'pending',
            'invite_token' => 'secure-invite-token-789',
        ]);

        $response = $this->actingAs($wali)->post(route('wali.invite.accept', 'secure-invite-token-789'));

        $response->assertRedirect(route('wali.dashboard'));
        $link->refresh();
        $this->assertEquals('active', $link->status);
        $this->assertEquals($wali->id, $link->wali_user_id);

        Notification::assertSentTo($seeker, WaliConnectionApprovedNotification::class);
    }

    public function test_wali_can_decline_invitation(): void
    {
        $seeker = User::factory()->create(['gender' => 'female']);
        $link = WaliLink::create([
            'seeker_user_id' => $seeker->id,
            'wali_name' => 'Uncle Zaid',
            'wali_email' => 'zaid@example.com',
            'relationship_type' => 'uncle',
            'permission_level' => 'approve_required',
            'status' => 'pending',
            'invite_token' => 'secure-decline-token-999',
        ]);

        $response = $this->post(route('wali.invite.decline', 'secure-decline-token-999'));

        $response->assertRedirect(route('landing'));
        $link->refresh();
        $this->assertEquals('declined', $link->status);
    }

    public function test_landing_page_renders_global_nikkah_branding(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Global');
        $response->assertSee('Nikkah');
        $response->assertDontSee('Nikah Connect');
    }
}

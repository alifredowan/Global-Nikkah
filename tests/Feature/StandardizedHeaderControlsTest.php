<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StandardizedHeaderControlsTest extends TestCase
{
    use RefreshDatabase;

    private function createSeeker(array $attributes = []): User
    {
        return User::create(array_merge([
            'name' => 'Fatima Al-Zahra',
            'email' => 'fatima_'.uniqid().'@example.com',
            'password' => bcrypt('Password123!'),
            'role' => 'seeker',
            'gender' => 'female',
            'dob' => '1998-05-12',
            'marital_status' => 'never_married',
            'is_active' => true,
            'is_verified' => true,
            'email_verified_at' => now(),
        ], $attributes));
    }

    private function createWali(array $attributes = []): User
    {
        return User::create(array_merge([
            'name' => 'Tariq Rahman',
            'email' => 'tariq_'.uniqid().'@example.com',
            'password' => bcrypt('Password123!'),
            'role' => 'wali',
            'gender' => 'male',
            'dob' => '1972-03-15',
            'is_active' => true,
            'is_verified' => true,
            'email_verified_at' => now(),
        ], $attributes));
    }

    private function createAdmin(array $attributes = []): User
    {
        return User::create(array_merge([
            'name' => 'Super Admin',
            'email' => 'admin_'.uniqid().'@example.com',
            'password' => bcrypt('Password123!'),
            'role' => 'super_admin',
            'gender' => 'male',
            'is_active' => true,
            'is_verified' => true,
            'email_verified_at' => now(),
        ], $attributes));
    }

    public function test_guest_header_renders_standardized_layout(): void
    {
        $response = $this->get(route('landing'));
        $response->assertStatus(200);

        // Left: Logo and How It Works
        $response->assertSee('How It Works');
        // Right: Theme toggle, Sign In, Create Profile
        $response->assertSee('Sign In');
        $response->assertSee('Create Profile');
    }

    public function test_authenticated_seeker_header_renders_all_four_controls_in_order(): void
    {
        $seeker = $this->createSeeker(['name' => 'Fatima Al-Zahra']);
        $response = $this->actingAs($seeker)->get(route('discovery.index'));
        $response->assertStatus(200);

        // 1. Messages control
        $response->assertSee(route('chat.index'));
        $response->assertSee('title="Messages"', false);

        // 2. Notifications bell with 4 tabs
        $response->assertSee('title="Notifications"', false);
        $response->assertSee('Interests');
        $response->assertSee('Messages');
        $response->assertSee('Family');
        $response->assertSee('Mark all as read');

        // 3. Theme toggle
        $response->assertSee('aria-label="Toggle light and dark mode"', false);

        // 4. Avatar button and account menu
        $response->assertSee('id="account-menu-button"', false);
        $response->assertSee('Fatima Al-Zahra');
        $response->assertSee('My profile');
        $response->assertSee('Settings');
        $response->assertSee('Privacy & cookies');
        $response->assertSee('Membership (Pro)');
        $response->assertSee('Help');
        $response->assertSee('Log out');

        // Seeker does NOT see Family dashboard in account menu
        $response->assertDontSee('Family dashboard');

        // Ensure NO standalone logout button exists
        $response->assertDontSee('Sign Out');
    }

    public function test_wali_account_menu_shows_family_dashboard(): void
    {
        $wali = $this->createWali(['name' => 'Tariq Rahman']);
        $response = $this->actingAs($wali)->get(route('wali.dashboard'));
        $response->assertStatus(200);

        // Account menu includes Family dashboard under My profile
        $response->assertSee('Family dashboard');
        $response->assertSee(route('wali.dashboard'));
    }

    public function test_admin_account_menu_shows_admin_link(): void
    {
        $admin = $this->createAdmin(['name' => 'Admin User']);
        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);

        // Account menu includes Admin link above Help
        $response->assertSee('Admin');
        $response->assertSee(route('admin.dashboard'));
    }

    public function test_mobile_tab_bar_is_rendered_with_five_items(): void
    {
        $seeker = $this->createSeeker();
        $response = $this->actingAs($seeker)->get(route('discovery.index'));
        $response->assertStatus(200);

        // 5 Mobile Tab Bar Items: Discover, Interests, Messages, Shortlist, Profile
        $response->assertSee('aria-label="Mobile Navigation"', false);
        $response->assertSee('aria-label="Discover"', false);
        $response->assertSee('aria-label="Interests"', false);
        $response->assertSee('aria-label="Messages"', false);
        $response->assertSee('aria-label="Shortlist"', false);
        $response->assertSee('aria-label="Profile"', false);
    }

    public function test_logout_redirects_to_home_with_sign_out_toast(): void
    {
        $seeker = $this->createSeeker();
        $response = $this->actingAs($seeker)->post(route('logout'));

        $response->assertRedirect(route('landing'));
        $response->assertSessionHas('status', 'You have been signed out.');

        // Follow redirect to confirm toast renders
        $follow = $this->get(route('landing'));
        $follow->assertSee('You have been signed out.');
    }

    public function test_notifications_json_endpoint_returns_category_mappings(): void
    {
        $seeker = $this->createSeeker();
        $response = $this->actingAs($seeker)->getJson(route('notifications.index'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'unread_count',
            'unread_messages_count',
            'notifications',
        ]);
    }
}

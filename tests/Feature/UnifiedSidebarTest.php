<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UnifiedSidebarTest extends TestCase
{
    use DatabaseTransactions;

    protected function createSuperAdmin(): User
    {
        return User::create([
            'name' => 'Test Super Admin',
            'email' => 'super_sidebar_test@nikahconnect.com',
            'password' => bcrypt('Password123!'),
            'role' => 'super_admin',
            'gender' => 'male',
            'is_active' => true,
            'is_verified' => true,
            'permissions' => ['manage_verifications', 'manage_reports', 'view_audit_logs', 'manage_settings'],
        ]);
    }

    protected function createSeeker(): User
    {
        return User::create([
            'name' => 'Test Seeker',
            'email' => 'seeker_sidebar_test@nikahconnect.com',
            'password' => bcrypt('Password123!'),
            'role' => 'seeker',
            'gender' => 'female',
            'dob' => '2000-01-01',
            'marital_status' => 'never_married',
            'is_active' => true,
            'is_verified' => true,
        ]);
    }

    protected function createWali(): User
    {
        return User::create([
            'name' => 'Test Guardian Wali',
            'email' => 'wali_sidebar_test@nikahconnect.com',
            'password' => bcrypt('Password123!'),
            'role' => 'wali',
            'gender' => 'male',
            'dob' => '1975-01-01',
            'is_active' => true,
            'is_verified' => true,
        ]);
    }

    public function test_super_admin_sidebar_has_all_administrative_and_platform_links(): void
    {
        $admin = $this->createSuperAdmin();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        // Admin operations
        $response->assertSee('Dashboard Overview');
        $response->assertSee('KYC Queue');
        $response->assertSee('User Reports');
        // Plans and discounts
        $response->assertSee('Dynamic Packages');
        $response->assertSee('Promo Codes');
        // Governance
        $response->assertSee('Staff & Permissions', false);
        $response->assertSee('Audit Logs');
        $response->assertSee('Platform Policies');
        // Matchmaking & Portal for Super Admin
        $response->assertSee('Find Matches');
        $response->assertSee('Interests & Requests', false);
        $response->assertSee('Messages');
        $response->assertSee('Shortlist');
        // Guardian Console for Super Admin
        $response->assertSee('Wali Dashboard');
        $response->assertSee('Link Ward');
        // Settings & Public
        $response->assertSee('Account Settings');
        $response->assertSee('Public Home');

        // Topbar must NOT have the old portal breadcrumb
        $response->assertDontSee('👑 Super Administrator Console');
        $response->assertDontSee('🛡️ Moderator Console');
        // Topbar must have 3-dots toggle and clickable logo
        $response->assertSee('Open / Close Sidebar (3 Dots)');
        $response->assertSee('Open / Close Sidebar (Global Nikkah Logo)');
    }

    public function test_seeker_sidebar_shows_only_seeker_links_and_no_admin_links(): void
    {
        $seeker = $this->createSeeker();

        $response = $this->actingAs($seeker)->get(route('discovery.index'));

        $response->assertStatus(200);
        // Seeker links MUST be present
        $response->assertSee('Find Matches');
        $response->assertSee('Interests & Requests', false);
        $response->assertSee('Messages');
        $response->assertSee('Shortlist');
        $response->assertSee('View Profile & Photos', false);
        $response->assertSee('Edit Deen & Preferences', false);
        $response->assertSee('Printable Biodata');
        $response->assertSee('Plans & Pricing', false);
        $response->assertSee('Account Settings');

        // Admin links MUST NOT be present
        $response->assertDontSee('Dashboard Overview');
        $response->assertDontSee('KYC Queue');
        $response->assertDontSee('User Reports');
        $response->assertDontSee('Dynamic Packages');
        $response->assertDontSee('Promo Codes');
        $response->assertDontSee('Staff & Permissions', false);
        $response->assertDontSee('Platform Policies');
        $response->assertDontSee('Platform Immutable Audit Logs');
    }

    public function test_wali_sidebar_shows_only_guardian_and_settings_links(): void
    {
        $wali = $this->createWali();

        $response = $this->actingAs($wali)->get(route('wali.dashboard'));

        $response->assertStatus(200);
        // Wali links
        $response->assertSee('Guardian Console');
        $response->assertSee('Wali Dashboard');
        $response->assertSee('Link Ward');
        $response->assertSee('Account Settings');

        // Admin links MUST NOT be present
        $response->assertDontSee('Dashboard Overview');
        $response->assertDontSee('KYC Queue');
        $response->assertDontSee('User Reports');
        $response->assertDontSee('Staff &amp; Permissions', false);
        $response->assertDontSee('Platform Policies');

        // Seeker biodata links MUST NOT be present
        $response->assertDontSee('View Profile &amp; Photos', false);
        $response->assertDontSee('Edit Deen &amp; Preferences', false);
    }

    public function test_settings_page_accessible_and_renders_for_both_super_admin_and_normal_user(): void
    {
        $admin = $this->createSuperAdmin();
        $responseAdmin = $this->actingAs($admin)->get(route('settings.index'));
        $responseAdmin->assertStatus(200);
        $responseAdmin->assertSee('Account Settings');
        $responseAdmin->assertSee('Personal & Account Information', false);
        $responseAdmin->assertSee('Password & Security', false);

        $seeker = $this->createSeeker();
        $responseSeeker = $this->actingAs($seeker)->get(route('settings.index'));
        $responseSeeker->assertStatus(200);
        $responseSeeker->assertSee('Account Settings');
        $responseSeeker->assertSee('Personal & Account Information', false);
        $responseSeeker->assertSee('Password & Security', false);
        $responseSeeker->assertSee('Privacy & Haya Preferences', false);
    }
}

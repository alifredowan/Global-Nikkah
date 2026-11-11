<?php

namespace Tests\Feature;

use App\Models\PlatformSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminComplianceTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_compliance_management(): void
    {
        $response = $this->get(route('admin.compliance'));

        $response->assertRedirect(route('login'));
    }

    public function test_seeker_user_is_forbidden_from_compliance_management(): void
    {
        $seeker = User::factory()->create([
            'role' => 'seeker',
            'is_active' => true,
        ]);

        $response = $this->actingAs($seeker)->get(route('admin.compliance'));

        $response->assertStatus(403);
    }

    public function test_wali_user_is_forbidden_from_compliance_management(): void
    {
        $wali = User::factory()->create([
            'role' => 'wali',
            'is_active' => true,
        ]);

        $response = $this->actingAs($wali)->get(route('admin.compliance'));

        $response->assertStatus(403);
    }

    public function test_moderator_user_is_forbidden_from_compliance_management(): void
    {
        $moderator = User::factory()->create([
            'role' => 'moderator',
            'is_active' => true,
            'permissions' => ['manage_reports', 'manage_verifications'],
        ]);

        $response = $this->actingAs($moderator)->get(route('admin.compliance'));

        $response->assertStatus(403);
    }

    public function test_super_admin_can_view_compliance_management_page(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($superAdmin)->get(route('admin.compliance'));

        $response->assertStatus(200);
        $response->assertSee('Compliance &amp; Cookie Consent Configuration', false);
        $response->assertSee('Banner Heading Title');
        $response->assertSee('Essential Cookies');
        $response->assertSee('Analytics &amp; Performance', false);
        $response->assertSee('Consent Validity Period (Months)');
    }

    public function test_super_admin_can_update_compliance_settings(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        $payload = [
            'compliance_banner_title' => 'Your Halal Privacy Choices',
            'compliance_banner_description' => 'We use minimal cookies for security and anonymous performance insights.',
            'compliance_essential_label' => 'Mandatory Session Cookies',
            'compliance_essential_description' => 'Required for session integrity, csrf tokens, and language.',
            'compliance_analytics_label' => 'Visitor Insights Cookies',
            'compliance_analytics_description' => 'Counts page views and latency anonymously.',
            'compliance_marketing_label' => 'Referral Attribution Cookies',
            'compliance_marketing_description' => 'Measures campaign performance. No third-party data broker sales.',
            'compliance_cookie_policy_url' => '/cookie-policy',
            'compliance_privacy_policy_url' => '/privacy',
            'compliance_consent_duration_months' => 12,
        ];

        $response = $this->actingAs($superAdmin)
            ->post(route('admin.compliance.update'), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('Your Halal Privacy Choices', PlatformSetting::get('compliance_banner_title'));
        $this->assertEquals(12, PlatformSetting::get('compliance_consent_duration_months'));

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $superAdmin->id,
            'action' => 'compliance_settings_updated',
        ]);
    }

    public function test_public_pages_render_dynamic_compliance_settings_in_banner(): void
    {
        PlatformSetting::set('compliance_banner_title', 'Custom Privacy Governance Notice', 'string', 'compliance');

        $response = $this->get(route('landing'));

        $response->assertStatus(200);
        $response->assertSee('Custom Privacy Governance Notice');
    }
}

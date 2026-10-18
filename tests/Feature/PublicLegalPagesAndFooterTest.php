<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicLegalPagesAndFooterTest extends TestCase
{
    use RefreshDatabase;

    public function test_privacy_policy_page_loads_successfully(): void
    {
        $response = $this->get(route('privacy'));

        $response->assertStatus(200);
        $response->assertSee('Privacy Policy');
        $response->assertSee('Default Photo Blur');
        $response->assertSee('Guardian Transparency');
        $response->assertSee('Right to Erasure');
    }

    public function test_terms_of_service_page_loads_successfully(): void
    {
        $response = $this->get(route('terms'));

        $response->assertStatus(200);
        $response->assertSee('Terms of Service');
        $response->assertSee('Nikah Only');
        $response->assertSee('Wali Respect');
        $response->assertSee('Single Pass Model');
    }

    public function test_about_us_page_loads_successfully(): void
    {
        $response = $this->get(route('about'));

        $response->assertStatus(200);
        $response->assertSee('Our Sacred Mission');
        $response->assertSee('Built for');
        $response->assertSee('Surah Ar-Rum');
        $response->assertSee('Wali Chaperoning');
    }

    public function test_unified_footer_is_visible_on_landing_page_with_dedicated_three_links(): void
    {
        $response = $this->get(route('landing'));

        $response->assertStatus(200);
        $response->assertSee('Global Nikkah');
        $response->assertSee(route('privacy'));
        $response->assertSee(route('terms'));
        $response->assertSee(route('about'));
    }

    public function test_unified_footer_is_visible_for_authenticated_users(): void
    {
        $user = User::factory()->create([
            'role' => 'seeker',
            'is_active' => true,
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);

        Profile::create([
            'user_id' => $user->id,
            'city' => 'Dhaka',
            'country' => 'Bangladesh',
            'sect_madhhab' => 'Sunni - Hanafi',
            'prayer_frequency' => '5x_daily',
            'completeness_percentage' => 85,
        ]);

        $response = $this->actingAs($user)->get(route('settings.index'));

        $response->assertStatus(200);
        $response->assertSee('Global Nikkah');
        $response->assertSee(route('privacy'));
        $response->assertSee(route('terms'));
        $response->assertSee(route('about'));
    }
}

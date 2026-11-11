<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->assertHeader('Content-Security-Policy');
    }

    public function test_landing_page_renders_with_featured_profiles(): void
    {
        $user = User::create([
            'name' => 'Khadija Testing',
            'email' => 'khadija_landing@test.com',
            'password' => bcrypt('Password123!'),
            'role' => 'seeker',
            'gender' => 'female',
            'dob' => '1999-01-01',
            'is_active' => true,
            'is_verified' => true,
        ]);

        Profile::create([
            'user_id' => $user->id,
            'gender' => 'female',
            'profession' => 'Engineer',
            'city' => 'Toronto',
            'country' => 'Canada',
            'marital_status' => 'never_married',
            'is_profile_completed' => true,
            'wali_required' => true,
        ]);

        $response = $this->get('/');
        $response->assertOk();
        $response->assertSee('Khadija Testing');
        $response->assertSee('Wali Chaperoned');

        // Test with wali_required = false
        $user2 = User::create([
            'name' => 'Yusuf Testing',
            'email' => 'yusuf_landing@test.com',
            'password' => bcrypt('Password123!'),
            'role' => 'seeker',
            'gender' => 'male',
            'dob' => '1995-01-01',
            'is_active' => true,
            'is_verified' => true,
        ]);

        Profile::create([
            'user_id' => $user2->id,
            'gender' => 'male',
            'profession' => 'Physician',
            'city' => 'London',
            'country' => 'United Kingdom',
            'marital_status' => 'never_married',
            'is_profile_completed' => true,
            'wali_required' => false,
        ]);

        $response2 = $this->get('/');
        $response2->assertOk();
        $response2->assertSee('Yusuf Testing');
        $response2->assertSee('Deen Verified');
    }
}

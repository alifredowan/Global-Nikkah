<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_verified_seekers_can_open_their_personalized_dashboard(): void
    {
        $user = User::create([
            'name' => 'Dashboard Seeker',
            'email' => 'dashboard.seeker@example.com',
            'password' => bcrypt('Password123!'),
            'role' => 'seeker',
            'gender' => 'female',
            'dob' => now()->subYears(27)->toDateString(),
            'marital_status' => 'never_married',
            'is_active' => true,
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);
        Profile::create([
            'user_id' => $user->id,
            'completeness_percentage' => 60,
            'wali_required' => true,
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk()
            ->assertSee('Your path to a blessed union, in one place.')
            ->assertSee('Complete your matrimonial profile')
            ->assertSeeText('Trust & family')
            ->assertSee('Profile setup')
            ->assertSee('Add a primary photo');
    }
}

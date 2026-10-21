<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiscoverySortingTest extends TestCase
{
    use RefreshDatabase;

    public function test_verified_sort_places_verified_candidates_first(): void
    {
        $seeker = $this->createSeeker('male', 'sorting-seeker@example.com', true);
        $unverifiedCandidate = $this->createSeeker('female', 'sorting-unverified@example.com', false);
        $verifiedCandidate = $this->createSeeker('female', 'sorting-verified@example.com', true);

        Profile::create(['user_id' => $seeker->id]);
        Profile::create(['user_id' => $unverifiedCandidate->id, 'completeness_percentage' => 90]);
        Profile::create(['user_id' => $verifiedCandidate->id, 'completeness_percentage' => 20]);

        $response = $this->actingAs($seeker)->get(route('discovery.index', ['sort' => 'verified']));

        $response->assertOk();
        $profiles = $response->viewData('profiles')->getCollection();

        $this->assertSame($verifiedCandidate->id, $profiles->first()->user_id);
    }

    private function createSeeker(string $gender, string $email, bool $isVerified): User
    {
        return User::create([
            'name' => 'Sorting Seeker',
            'email' => $email,
            'password' => bcrypt('Password123!'),
            'role' => 'seeker',
            'gender' => $gender,
            'dob' => now()->subYears(28)->toDateString(),
            'marital_status' => 'never_married',
            'is_active' => true,
            'is_verified' => $isVerified,
            'email_verified_at' => now(),
        ]);
    }
}

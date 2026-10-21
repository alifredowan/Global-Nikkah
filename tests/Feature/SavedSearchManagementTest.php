<?php

namespace Tests\Feature;

use App\Models\SavedSearch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SavedSearchManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_saved_search_keeps_only_supported_discovery_filters(): void
    {
        $user = $this->createVerifiedSeeker();

        $response = $this->actingAs($user)->post(route('discovery.save-search'), [
            'title' => 'Family shortlist',
            'country' => 'Italy',
            'prayer_frequency' => '5x_daily',
            'wali_required' => '1',
            'unexpected_value' => 'should not be stored',
        ]);

        $response->assertRedirect();
        $savedSearch = SavedSearch::where('user_id', $user->id)->firstOrFail();

        $this->assertSame([
            'country' => 'Italy',
            'prayer_frequency' => '5x_daily',
            'wali_required' => '1',
        ], $savedSearch->filters);
        $this->assertArrayNotHasKey('unexpected_value', $savedSearch->filters);
    }

    public function test_a_user_can_pause_and_resume_saved_search_alerts(): void
    {
        $user = $this->createVerifiedSeeker();
        $savedSearch = SavedSearch::create([
            'user_id' => $user->id,
            'title' => 'Hanafi candidates nearby',
            'filters' => ['country' => 'Italy'],
            'alerts_enabled' => true,
        ]);

        $response = $this->actingAs($user)->post(route('discovery.saved-searches.toggle-alerts', $savedSearch));

        $response->assertSessionHas('success', 'Alerts paused for this saved search.');
        $this->assertDatabaseHas('saved_searches', [
            'id' => $savedSearch->id,
            'alerts_enabled' => false,
        ]);
    }

    public function test_a_user_can_remove_only_their_own_saved_search(): void
    {
        $user = $this->createVerifiedSeeker();
        $otherUser = $this->createVerifiedSeeker('other@example.com');
        $savedSearch = SavedSearch::create([
            'user_id' => $otherUser->id,
            'title' => 'Private search',
            'filters' => [],
        ]);

        $response = $this->actingAs($user)->delete(route('discovery.saved-searches.destroy', $savedSearch));

        $response->assertNotFound();
        $this->assertDatabaseHas('saved_searches', ['id' => $savedSearch->id]);
    }

    private function createVerifiedSeeker(string $email = 'seeker@example.com'): User
    {
        return User::create([
            'name' => 'Verified Seeker',
            'email' => $email,
            'password' => bcrypt('Password123!'),
            'role' => 'seeker',
            'gender' => 'male',
            'dob' => now()->subYears(25)->toDateString(),
            'marital_status' => 'never_married',
            'is_active' => true,
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);
    }
}

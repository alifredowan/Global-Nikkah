<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\SavedSearch;
use App\Models\User;
use App\Notifications\SavedSearchMatchNotification;
use App\Services\SavedSearchAlertService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SavedSearchAlertTest extends TestCase
{
    use RefreshDatabase;

    public function test_matching_profile_updates_create_one_saved_search_notification(): void
    {
        $recipient = $this->createSeeker('male', 'alert-recipient@example.com');
        SavedSearch::create([
            'user_id' => $recipient->id,
            'title' => 'Practicing candidates in Italy',
            'filters' => [
                'country' => 'Italy',
                'prayer_frequency' => '5x_daily',
            ],
            'alerts_enabled' => true,
        ]);

        $candidate = $this->createSeeker('female', 'alert-candidate@example.com');
        Profile::create([
            'user_id' => $candidate->id,
            'country' => 'Northern Italy',
            'prayer_frequency' => '5x_daily',
        ]);

        $service = app(SavedSearchAlertService::class);
        $service->notifyFor($candidate);
        $service->notifyFor($candidate);

        $this->assertSame(1, $recipient->notifications()->where('type', SavedSearchMatchNotification::class)->count());
        $this->assertSame('saved_search_match', $recipient->notifications()->first()->data['type']);
    }

    public function test_paused_saved_searches_do_not_create_notifications(): void
    {
        $recipient = $this->createSeeker('male', 'paused-recipient@example.com');
        SavedSearch::create([
            'user_id' => $recipient->id,
            'title' => 'Paused search',
            'filters' => ['country' => 'Italy'],
            'alerts_enabled' => false,
        ]);

        $candidate = $this->createSeeker('female', 'paused-candidate@example.com');
        Profile::create(['user_id' => $candidate->id, 'country' => 'Italy']);

        app(SavedSearchAlertService::class)->notifyFor($candidate);

        $this->assertSame(0, $recipient->notifications()->count());
    }

    private function createSeeker(string $gender, string $email): User
    {
        return User::create([
            'name' => 'Saved Search Seeker',
            'email' => $email,
            'password' => bcrypt('Password123!'),
            'role' => 'seeker',
            'gender' => $gender,
            'dob' => now()->subYears(28)->toDateString(),
            'marital_status' => 'never_married',
            'is_active' => true,
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);
    }
}

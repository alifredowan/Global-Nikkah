<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\DiscountCode;
use App\Models\Interest;
use App\Models\Marriage;
use App\Models\Profile;
use App\Models\Subscription;
use App\Models\User;
use App\Services\MarriageCompletionService;
use App\Services\SubscriptionService;
use Database\Seeders\SubscriptionPlanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class SingleMarriagePassTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(SubscriptionPlanSeeder::class);
    }

    private function createSeeker(string $gender = 'male'): User
    {
        $user = User::factory()->create([
            'name' => fake()->name(),
            'email' => 'seeker.'.Str::random(8).'@example.com',
            'role' => 'seeker',
            'gender' => $gender,
            'is_verified' => true,
            'is_active' => true,
        ]);

        Profile::create([
            'user_id' => $user->id,
            'city' => 'Dhaka',
            'country' => 'Bangladesh',
            'sect_madhhab' => 'Sunni - Hanafi',
            'prayer_frequency' => '5x_daily',
            'completeness_percentage' => 85,
        ]);

        return $user;
    }

    public function test_pricing_page_displays_single_marriage_passes_with_one_time_messaging(): void
    {
        $response = $this->get(route('subscription.pricing'));

        $response->assertStatus(200);
        $response->assertSee('Single Marriage Pass: Pay Once Until Nikah');
        $response->assertSee('Standard Nikah Pass');
        $response->assertSee('VIP Barakah Pass');
        $response->assertSee('Free Seeker');
        $response->assertSee('Free Forever');
        $response->assertSee('$49.00');
        $response->assertSee('$99.00');
        $response->assertSee('Pay 1-Time');
        $response->assertSee('Active Until Confirmed Nikah');
        $response->assertDontSee('/ month');
    }

    public function test_user_can_purchase_single_marriage_pass_without_calendar_expiration(): void
    {
        $seeker = $this->createSeeker('male');

        $response = $this->actingAs($seeker)->post(route('subscription.process'), [
            'plan' => 'premium',
            'billing_cycle' => 'one_time_marriage',
            'payment_method' => 'simulated',
        ]);

        $response->assertRedirect(route('subscription.pricing'));
        $response->assertSessionHas('success');

        $subscription = Subscription::where('user_id', $seeker->id)
            ->where('status', 'active')
            ->first();

        $this->assertNotNull($subscription);
        $this->assertEquals('premium', $subscription->plan);
        $this->assertEquals('one_time_marriage', $subscription->billing_cycle);
        $this->assertEquals(49.00, (float) $subscription->amount_paid);
        $this->assertNull($subscription->ends_at, 'Single Marriage Pass must have no calendar ends_at date');
        $this->assertNull($subscription->renews_at, 'Single Marriage Pass must have no auto-renewal date');
        $this->assertTrue($subscription->isActive(), 'Single Marriage Pass without ends_at must remain active indefinitely until Nikah');
    }

    public function test_promo_discount_applies_correctly_to_single_marriage_pass_fee(): void
    {
        $seeker = $this->createSeeker('female');

        $discount = DiscountCode::create([
            'code' => 'NIKAH20',
            'discount_percentage' => 20,
            'max_uses' => 10,
            'times_used' => 0,
            'is_active' => true,
        ]);

        $response = $this->actingAs($seeker)->postJson(route('subscription.validate-promo'), [
            'promo_code' => 'NIKAH20',
            'plan' => 'premium',
            'billing_cycle' => 'one_time_marriage',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'valid' => true,
                'code' => 'NIKAH20',
                'discount_percentage' => 20,
                'original_price' => 49.00,
                'discount_amount' => 9.80,
                'final_price' => 39.20,
            ]);
    }

    public function test_single_marriage_pass_transitions_to_completed_nikah_upon_marriage_confirmation(): void
    {
        $groom = $this->createSeeker('male');
        $bride = $this->createSeeker('female');

        $subscriptionService = app(SubscriptionService::class);

        // Both groom and bride purchase the 1-time marriage pass
        $groomSub = $subscriptionService->activateSubscription(
            $groom,
            'premium',
            'one_time_marriage',
            49.00,
            'simulated'
        );

        $brideSub = $subscriptionService->activateSubscription(
            $bride,
            'premium_plus',
            'one_time_marriage',
            99.00,
            'simulated'
        );

        $this->assertTrue($groomSub->isActive());
        $this->assertTrue($brideSub->isActive());
        $this->assertNull($groomSub->ends_at);
        $this->assertNull($brideSub->ends_at);

        // Couple accepts interest & initiates marriage
        $interest = Interest::create([
            'sender_id' => $groom->id,
            'recipient_id' => $bride->id,
            'status' => 'accepted',
        ]);

        $conversation = Conversation::create([
            'interest_id' => $interest->id,
            'status' => 'active',
        ]);

        $marriage = Marriage::create([
            'groom_id' => $groom->id,
            'bride_id' => $bride->id,
            'initiated_by_user_id' => $groom->id,
            'marriage_date' => now()->toDateString(),
            'status' => 'pending_confirmation',
        ]);

        // Marriage milestone is confirmed
        $marriageService = app(MarriageCompletionService::class);
        $marriageService->confirm($marriage, $bride);

        // Assert subscriptions transitioned to 'completed_nikah'
        $groomSub->refresh();
        $brideSub->refresh();

        $this->assertEquals('completed_nikah', $groomSub->status);
        $this->assertEquals('completed_nikah', $brideSub->status);

        $this->assertFalse($groomSub->isActive(), 'Pass should no longer be active once Nikah is completed');
        $this->assertFalse($brideSub->isActive(), 'Pass should no longer be active once Nikah is completed');
    }
}

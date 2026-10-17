<?php

namespace Tests\Feature;

use Database\Seeders\SubscriptionPlanSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SubscriptionPricingTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(SubscriptionPlanSeeder::class);
    }

    public function test_guests_can_view_pricing_and_register_without_an_active_plan(): void
    {
        $response = $this->get(route('subscription.pricing'));

        $response->assertSee('Membership Plans & Tiers', false)
            ->assertSee('Free Seeker')
            ->assertSee('Standard Nikah Pass')
            ->assertSee('VIP Barakah Pass')
            ->assertSee('href="'.route('register').'"', false)
            ->assertDontSee('Current Active Plan')
            ->assertDontSee('Your Current Plan')
            ->assertDontSee('Downgrade to Free');
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;

class HowItWorksPageTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_public_how_it_works_page_explains_the_three_party_journey(): void
    {
        $response = $this->get(route('how-it-works'));

        $response->assertOk()
            ->assertSee('Three hearts.')
            ->assertSee('Seeker A')
            ->assertSee('Seeker B')
            ->assertSee('Wali / Family')
            ->assertSee('From first profile to family conversation')
            ->assertSee(route('how-it-works'));
    }
}

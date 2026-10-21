<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserUnreadMessagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_unread_messages_count_is_exposed_for_navigation_badges(): void
    {
        $user = User::create([
            'name' => 'Unread Count User',
            'email' => 'unread-count@example.com',
            'password' => bcrypt('Password123!'),
            'role' => 'seeker',
            'gender' => 'male',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $this->assertSame(0, $user->unreadMessagesCount());
    }
}

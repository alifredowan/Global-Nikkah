<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free Seeker',
                'slug' => 'free',
                'description' => 'Core Islamic matching features, guardian supervision, and halal messaging are always accessible.',
                'monthly_price' => 0.00,
                'annual_price' => 0.00,
                'daily_profile_views' => 10,
                'daily_interests' => 5,
                'advanced_filters' => false,
                'profile_boost' => false,
                'see_who_viewed' => false,
                'dedicated_advisor' => false,
                'badge_text' => 'Free Forever',
                'is_popular' => false,
                'sort_order' => 1,
                'features' => [
                    '10 daily profile views (FR-3.4)',
                    '5 daily interest requests (FR-5.1)',
                    'Full Wali guardian supervision (FR-4.3)',
                    'Default blurred photo privacy (FR-2.2)',
                    'Halal messaging after mutual accept',
                    'Zero expiration, always free',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Standard Nikah Pass (Seeker Premium)',
                'slug' => 'premium',
                'description' => 'Pay once and enjoy unlimited discovery, visitor analytics, and Shariah biodata until your confirmed Nikah.',
                'monthly_price' => 49.00,
                'annual_price' => 49.00,
                'daily_profile_views' => -1,
                'daily_interests' => -1,
                'advanced_filters' => true,
                'profile_boost' => false,
                'see_who_viewed' => true,
                'dedicated_advisor' => false,
                'badge_text' => 'Most Popular',
                'is_popular' => true,
                'sort_order' => 2,
                'features' => [
                    'Single Marriage Guarantee: Active until confirmed Nikah',
                    'No monthly or annual recurring fees',
                    'Unlimited daily profile views & interest requests',
                    'See who viewed your profile (Visitor Analytics)',
                    'Shortlist candidates for family & Wali review',
                    'Private family consultation notes',
                    'Discreet Incognito browsing mode',
                    'Printable Shariah Matrimonial Biodata',
                    'Priority ID verification queue',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'VIP Barakah Pass (Seeker VIP (Premium+))',
                'slug' => 'premium_plus',
                'description' => 'Maximum visibility with profile boost and personalized marriage advisor assistance until your wedding day.',
                'monthly_price' => 99.00,
                'annual_price' => 99.00,
                'daily_profile_views' => -1,
                'daily_interests' => -1,
                'advanced_filters' => true,
                'profile_boost' => true,
                'see_who_viewed' => true,
                'dedicated_advisor' => true,
                'badge_text' => 'VIP Tier',
                'is_popular' => false,
                'sort_order' => 3,
                'features' => [
                    'Single Marriage Guarantee: Active until confirmed Nikah',
                    'Top profile boost in search discovery',
                    'Crown VIP badge & Spotlight ranking',
                    'Priority proposal highlight on suitors',
                    'All Standard Nikah Pass features included',
                    'Dedicated matrimonial advisor consultation',
                    'Priority Wali & suitor assistance',
                ],
                'is_active' => true,
            ],
        ];

        foreach ($plans as $planData) {
            SubscriptionPlan::updateOrCreate(
                ['slug' => $planData['slug']],
                $planData
            );
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan',
        'billing_cycle',
        'status',
        'starts_at',
        'ends_at',
        'renews_at',
        'payment_method',
        'payment_id',
        'currency',
        'payment_details',
        'amount_paid',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'renews_at' => 'datetime',
            'amount_paid' => 'decimal:2',
            'payment_details' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && (! $this->ends_at || $this->ends_at->isFuture());
    }

    public function planModel(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan', 'slug');
    }

    public static function getPlanDetails(string $plan): array
    {
        $dbPlan = SubscriptionPlan::where('slug', $plan)->first();
        if ($dbPlan) {
            return $dbPlan->toLegacyPlanDetails();
        }

        return match ($plan) {
            'premium_plus' => [
                'name' => 'VIP Barakah Pass',
                'slug' => 'premium_plus',
                'description' => 'Maximum visibility with profile boost and personalized marriage advisor assistance until Nikah.',
                'daily_profile_views' => PHP_INT_MAX,
                'daily_interests' => PHP_INT_MAX,
                'advanced_filters' => true,
                'profile_boost' => true,
                'see_who_viewed' => true,
                'dedicated_advisor' => true,
                'badge_text' => 'VIP Tier',
                'is_popular' => false,
                'price' => 99.00,
                'one_time_price' => 99.00,
                'monthly_price' => 99.00,
                'annual_price' => 99.00,
                'validity' => 'Active Until Confirmed Nikah',
            ],
            'premium' => [
                'name' => 'Standard Nikah Pass',
                'slug' => 'premium',
                'description' => 'Unlimited discovery, visitor analytics, Shariah biodata, and verified queue until successful marriage.',
                'daily_profile_views' => PHP_INT_MAX,
                'daily_interests' => PHP_INT_MAX,
                'advanced_filters' => true,
                'profile_boost' => false,
                'see_who_viewed' => true,
                'dedicated_advisor' => false,
                'badge_text' => 'Most Popular',
                'is_popular' => true,
                'price' => 49.00,
                'one_time_price' => 49.00,
                'monthly_price' => 49.00,
                'annual_price' => 49.00,
                'validity' => 'Active Until Confirmed Nikah',
            ],
            default => [
                'name' => 'Free Explorer',
                'slug' => 'free',
                'description' => 'Core Islamic matching features, guardian supervision, and halal messaging are always accessible.',
                'daily_profile_views' => 10,
                'daily_interests' => 5,
                'advanced_filters' => false,
                'profile_boost' => false,
                'see_who_viewed' => false,
                'dedicated_advisor' => false,
                'badge_text' => 'Free Forever',
                'is_popular' => false,
                'price' => 0.00,
                'one_time_price' => 0.00,
                'monthly_price' => 0.00,
                'annual_price' => 0.00,
                'validity' => 'Lifetime Access',
            ],
        };
    }
}

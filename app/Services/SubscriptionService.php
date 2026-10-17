<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\DiscountCode;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SubscriptionService
{
    /**
     * Resolve plan details and calculate final price with any discount promo applied.
     *
     * @return array{
     *     base_price: float,
     *     final_price: float,
     *     discount_amount: float,
     *     discount_percentage: int,
     *     applied_promo: ?DiscountCode,
     *     plan_name: string,
     *     plan_details: array
     * }
     */
    public function calculatePricing(
        string $planSlug,
        string $billingCycle = 'one_time_marriage',
        ?string $promoCode = null,
        ?User $user = null
    ): array {
        $planModel = SubscriptionPlan::where('slug', $planSlug)
            ->where('is_active', true)
            ->first();

        $planDetails = $planModel ? $planModel->toLegacyPlanDetails() : Subscription::getPlanDetails($planSlug);

        // Standard 1-Time Marriage Pass uses the plan's price (stored in monthly_price column)
        $basePrice = $billingCycle === 'annual' && isset($planDetails['annual_price']) && $planDetails['annual_price'] > 0
            ? (float) $planDetails['annual_price']
            : (float) ($planDetails['one_time_price'] ?? $planDetails['monthly_price'] ?? 0.0);

        $finalPrice = $basePrice;
        $discountAmount = 0.0;
        $discountPercentage = 0;
        $appliedPromo = null;

        if (! empty($promoCode)) {
            $discount = DiscountCode::where('code', strtoupper(trim($promoCode)))->first();

            if ($discount) {
                $validity = $discount->checkValidity($user, $planSlug);
                if ($validity['valid']) {
                    $discountPercentage = (int) $discount->discount_percentage;
                    $discountAmount = round(($basePrice * $discountPercentage) / 100, 2);
                    $finalPrice = max(0, round($basePrice - $discountAmount, 2));
                    $appliedPromo = $discount;
                }
            }
        }

        return [
            'base_price' => $basePrice,
            'final_price' => $finalPrice,
            'discount_amount' => $discountAmount,
            'discount_percentage' => $discountPercentage,
            'applied_promo' => $appliedPromo,
            'plan_name' => $planDetails['name'] ?? ucfirst($planSlug),
            'plan_details' => $planDetails,
        ];
    }

    /**
     * Activate a membership subscription for the user with transaction details.
     */
    public function activateSubscription(
        User $user,
        string $planSlug,
        string $billingCycle = 'one_time_marriage',
        float $amountPaid = 0.0,
        string $paymentMethod = 'simulated',
        ?string $paymentId = null,
        ?string $promoCode = null,
        ?array $paymentDetails = null
    ): Subscription {
        return DB::transaction(function () use (
            $user,
            $planSlug,
            $billingCycle,
            $amountPaid,
            $paymentMethod,
            $paymentId,
            $promoCode,
            $paymentDetails
        ) {
            // Cancel any current active subscriptions
            Subscription::where('user_id', $user->id)
                ->where('status', 'active')
                ->update([
                    'status' => 'cancelled',
                    'ends_at' => now(),
                ]);

            // Single Marriage Pass has no calendar expiry: ends_at = null, renews_at = null.
            // It remains active until marriage milestone confirmation completes the Nikah.
            $isOneTime = in_array($billingCycle, ['one_time_marriage', 'one_time', 'lifetime_marriage', 'until_nikah'], true) || empty($billingCycle);
            $endsAt = $isOneTime ? null : ($billingCycle === 'annual' ? now()->addMonths(12) : now()->addMonths(1));
            $renewsAt = $isOneTime ? null : $endsAt;

            $subscription = Subscription::create([
                'user_id' => $user->id,
                'plan' => $planSlug,
                'billing_cycle' => $billingCycle,
                'status' => 'active',
                'amount_paid' => $amountPaid,
                'payment_method' => $paymentMethod,
                'payment_id' => $paymentId,
                'currency' => 'USD',
                'payment_details' => $paymentDetails,
                'starts_at' => now(),
                'ends_at' => $endsAt,
                'renews_at' => $renewsAt,
            ]);

            // Increment promo code usage if applied
            $appliedPromo = null;
            if (! empty($promoCode)) {
                $appliedPromo = DiscountCode::where('code', strtoupper(trim($promoCode)))->first();
                if ($appliedPromo) {
                    $appliedPromo->increment('times_used');
                }
            }

            // Record audit log
            $logData = [
                'plan' => $planSlug,
                'billing_cycle' => $billingCycle,
                'amount' => $amountPaid,
                'payment_method' => $paymentMethod,
                'payment_id' => $paymentId,
            ];

            if ($appliedPromo) {
                $logData['promo_code'] = $appliedPromo->code;
                $logData['discount_percentage'] = $appliedPromo->discount_percentage;
            }

            AuditLog::record(
                $user->id,
                'subscription_upgraded',
                'Subscription',
                $subscription->id,
                $logData
            );

            return $subscription;
        });
    }

    /**
     * Cancel an active subscription and downgrade user to free tier.
     */
    public function cancelSubscription(User $user): Subscription
    {
        return DB::transaction(function () use ($user) {
            Subscription::where('user_id', $user->id)
                ->where('status', 'active')
                ->update([
                    'status' => 'cancelled',
                    'ends_at' => now(),
                ]);

            $freeSub = Subscription::create([
                'user_id' => $user->id,
                'plan' => 'free',
                'status' => 'active',
                'starts_at' => now(),
            ]);

            AuditLog::record($user->id, 'subscription_cancelled', 'User', $user->id);

            return $freeSub;
        });
    }
}

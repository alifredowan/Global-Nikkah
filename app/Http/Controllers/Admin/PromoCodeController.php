<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePromoCodeRequest;
use App\Models\AuditLog;
use App\Models\DiscountCode;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PromoCodeController extends Controller
{
    public function index(): View
    {
        $discountCodes = DiscountCode::with('plan')->latest('created_at')->get();
        $packages = SubscriptionPlan::where('monthly_price', '>', 0)->orderBy('name')->get();
        $allUsers = User::where('role', 'seeker')->orderBy('name')->select('id', 'name', 'email')->get();

        return view('admin.promo-codes', compact('discountCodes', 'packages', 'allUsers'));
    }

    public function store(StorePromoCodeRequest $request): RedirectResponse
    {
        $planSlug = $request->plan_slug === 'all' || empty($request->plan_slug) ? null : $request->plan_slug;

        $expiresAt = null;
        if ($request->expires_at) {
            $expiresAt = str_contains((string) $request->expires_at, '/')
                ? Carbon::createFromFormat('d/m/Y', (string) $request->expires_at)->endOfDay()
                : Carbon::parse((string) $request->expires_at)->endOfDay();
        }

        $promo = DiscountCode::create([
            'code' => strtoupper(trim((string) $request->code)),
            'discount_percentage' => (int) $request->discount_percentage,
            'plan_slug' => $planSlug,
            'allowed_emails' => $request->allowed_emails ? trim((string) $request->allowed_emails) : null,
            'description' => $request->description,
            'max_uses' => (int) $request->max_uses,
            'times_used' => 0,
            'is_active' => true,
            'expires_at' => $expiresAt,
        ]);

        AuditLog::record(Auth::id(), 'promo_code_created', 'DiscountCode', $promo->id, [
            'code' => $promo->code,
            'discount_percentage' => $promo->discount_percentage,
            'plan_slug' => $promo->plan_slug,
            'restricted' => $promo->isRestrictedToUsers(),
        ]);

        return back()->with('success', "Promo code '{$promo->code}' created successfully!");
    }

    public function toggleStatus(int $id): RedirectResponse
    {
        $code = DiscountCode::findOrFail($id);
        $code->update(['is_active' => ! $code->is_active]);

        $status = $code->is_active ? 'activated' : 'deactivated';
        AuditLog::record(Auth::id(), "promo_code_{$status}", 'DiscountCode', $code->id);

        return back()->with('success', "Promo code '{$code->code}' {$status} successfully.");
    }

    public function destroy(int $id): RedirectResponse
    {
        $code = DiscountCode::findOrFail($id);
        $name = $code->code;
        $code->delete();

        AuditLog::record(Auth::id(), 'promo_code_deleted', 'DiscountCode', $id, ['code' => $name]);

        return back()->with('success', "Promo code '{$name}' deleted successfully.");
    }
}


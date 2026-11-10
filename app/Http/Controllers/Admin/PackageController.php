<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SavePackageRequest;
use App\Models\AuditLog;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PackageController extends Controller
{
    public function index(): View
    {
        $packages = SubscriptionPlan::orderBy('sort_order')->orderBy('monthly_price')->get();

        return view('admin.packages', compact('packages'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:50', 'alpha_dash', 'unique:subscription_plans,slug'],
            'description' => ['nullable', 'string', 'max:500'],
            'monthly_price' => ['required', 'numeric', 'min:0'],
            'annual_price' => ['nullable', 'numeric', 'min:0'],
            'daily_profile_views' => ['required', 'integer'],
            'daily_interests' => ['required', 'integer'],
            'advanced_filters' => ['nullable', 'boolean'],
            'profile_boost' => ['nullable', 'boolean'],
            'see_who_viewed' => ['nullable', 'boolean'],
            'dedicated_advisor' => ['nullable', 'boolean'],
            'badge_text' => ['nullable', 'string', 'max:50'],
            'features' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'is_popular' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $slug = ! empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);
        $originalSlug = $slug;
        $counter = 1;
        while (SubscriptionPlan::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        $features = [];
        if (! empty($validated['features'])) {
            $features = array_values(array_filter(array_map('trim', preg_split('/[\r\n]+/', (string) $validated['features']))));
        }

        $price = $validated['monthly_price'];
        $annualPrice = isset($validated['annual_price']) && $validated['annual_price'] !== '' ? $validated['annual_price'] : $price;

        $package = SubscriptionPlan::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'monthly_price' => $price,
            'annual_price' => $annualPrice,
            'daily_profile_views' => $validated['daily_profile_views'],
            'daily_interests' => $validated['daily_interests'],
            'advanced_filters' => $request->boolean('advanced_filters'),
            'profile_boost' => $request->boolean('profile_boost'),
            'see_who_viewed' => $request->boolean('see_who_viewed'),
            'dedicated_advisor' => $request->boolean('dedicated_advisor'),
            'badge_text' => $validated['badge_text'] ?? null,
            'features' => $features,
            'is_active' => $request->boolean('is_active', true),
            'is_popular' => $request->boolean('is_popular'),
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
        ]);

        AuditLog::record(Auth::id(), 'subscription_package_created', 'SubscriptionPlan', $package->id, [
            'name' => $package->name,
            'slug' => $package->slug,
            'monthly_price' => $package->monthly_price,
        ]);

        return back()->with('success', "Subscription package '{$package->name}' created successfully!");
    }

    public function update(SavePackageRequest $request, int $id): RedirectResponse
    {
        $package = SubscriptionPlan::findOrFail($id);
        $validated = $request->validated();

        $features = [];
        if (! empty($validated['features'])) {
            $features = array_values(array_filter(array_map('trim', preg_split('/[\r\n]+/', (string) $validated['features']))));
        }

        $price = $validated['monthly_price'];
        $annualPrice = isset($validated['annual_price']) && $validated['annual_price'] !== '' ? $validated['annual_price'] : $price;

        $package->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['slug']),
            'description' => $validated['description'] ?? null,
            'monthly_price' => $price,
            'annual_price' => $annualPrice,
            'daily_profile_views' => $validated['daily_profile_views'],
            'daily_interests' => $validated['daily_interests'],
            'advanced_filters' => $request->boolean('advanced_filters'),
            'profile_boost' => $request->boolean('profile_boost'),
            'see_who_viewed' => $request->boolean('see_who_viewed'),
            'dedicated_advisor' => $request->boolean('dedicated_advisor'),
            'badge_text' => $validated['badge_text'] ?? null,
            'features' => $features,
            'is_active' => $request->boolean('is_active'),
            'is_popular' => $request->boolean('is_popular'),
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
        ]);

        AuditLog::record(Auth::id(), 'subscription_package_updated', 'SubscriptionPlan', $package->id, [
            'name' => $package->name,
            'monthly_price' => $package->monthly_price,
        ]);

        return back()->with('success', "Subscription package '{$package->name}' updated successfully!");
    }

    public function toggleStatus(int $id): RedirectResponse
    {
        $package = SubscriptionPlan::findOrFail($id);
        $package->update(['is_active' => ! $package->is_active]);

        $status = $package->is_active ? 'activated' : 'deactivated';
        AuditLog::record(Auth::id(), "subscription_package_{$status}", 'SubscriptionPlan', $package->id);

        return back()->with('success', "Package '{$package->name}' {$status} successfully.");
    }

    public function destroy(int $id): RedirectResponse
    {
        $package = SubscriptionPlan::findOrFail($id);

        if ($package->slug === 'free') {
            return back()->with('error', 'The default Free tier cannot be deleted.');
        }

        $activeSubscribersCount = Subscription::where('plan', $package->slug)->where('status', 'active')->count();
        if ($activeSubscribersCount > 0) {
            return back()->with('error', "Cannot delete package '{$package->name}' because it has {$activeSubscribersCount} active subscribers. Deactivate it instead.");
        }

        $name = $package->name;
        $package->delete();

        AuditLog::record(Auth::id(), 'subscription_package_deleted', 'SubscriptionPlan', $id, ['name' => $name]);

        return back()->with('success', "Package '{$name}' deleted successfully.");
    }
}


<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\ComplianceController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\PlatformSettingController;
use App\Http\Controllers\Admin\PromoCodeController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\VerificationController;
use App\Http\Requests\Admin\SavePackageRequest;
use App\Http\Requests\Admin\StorePromoCodeRequest;
use App\Http\Requests\Admin\StoreStaffRequest;
use App\Models\AuditLog;
use App\Models\Conversation;
use App\Models\DiscountCode;
use App\Models\Report;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\VerificationRequest;
use App\Services\Payment\PayPalPaymentService;
use App\Services\Payment\StripePaymentService;
use App\Services\TurnstileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $metrics = [
            'total_users' => User::count(),
            'seekers' => User::where('role', 'seeker')->count(),
            'walis' => User::where('role', 'wali')->count(),
            'verified_users' => User::where('is_verified', true)->count(),
            'pending_verifications' => VerificationRequest::where('status', 'pending')->count(),
            'active_conversations' => Conversation::where('status', 'active')->count(),
            'pending_reports' => Report::where('status', 'pending')->count(),
            'total_revenue' => Subscription::where('status', 'active')->sum('amount_paid'),
            'paying_subscribers' => Subscription::where('status', 'active')->where('plan', '!=', 'free')->count(),
            'active_packages' => SubscriptionPlan::where('is_active', true)->count(),
            'active_promo_codes' => DiscountCode::where('is_active', true)->count(),
        ];

        $recentVerifications = VerificationRequest::with('user.profile')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $recentReports = Report::with(['reporter', 'reported'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $recentAuditLogs = AuditLog::with('user')->latest('created_at')->take(8)->get();

        return view('admin.dashboard', compact('metrics', 'recentVerifications', 'recentReports', 'recentAuditLogs'));
    }

    public function auditLogs(): View
    {
        $logs = AuditLog::with('user')->latest('created_at')->paginate(25);

        return view('admin.audit-logs', compact('logs'));
    }

    // Delegations to specialized domain controllers
    public function verifications(): View
    {
        return app(VerificationController::class)->index();
    }

    public function downloadVerificationDocument(int $id, string $type): Response
    {
        return app(VerificationController::class)->downloadDocument($id, $type);
    }

    public function approveVerification(int $id): RedirectResponse
    {
        return app(VerificationController::class)->approve($id);
    }

    public function rejectVerification(Request $request, int $id): RedirectResponse
    {
        return app(VerificationController::class)->reject($request, $id);
    }

    public function reports(): View
    {
        return app(ReportController::class)->index();
    }

    public function actionReport(Request $request, int $id): RedirectResponse
    {
        return app(ReportController::class)->action($request, $id);
    }

    public function packages(): View
    {
        return app(PackageController::class)->index();
    }

    public function storePackage(Request $request): RedirectResponse
    {
        return app(PackageController::class)->store($request);
    }

    public function updatePackage(SavePackageRequest $request, int $id): RedirectResponse
    {
        return app(PackageController::class)->update($request, $id);
    }

    public function togglePackageStatus(int $id): RedirectResponse
    {
        return app(PackageController::class)->toggleStatus($id);
    }

    public function destroyPackage(int $id): RedirectResponse
    {
        return app(PackageController::class)->destroy($id);
    }

    public function promoCodes(): View
    {
        return app(PromoCodeController::class)->index();
    }

    public function storePromoCode(StorePromoCodeRequest $request): RedirectResponse
    {
        return app(PromoCodeController::class)->store($request);
    }

    public function togglePromoCodeStatus(int $id): RedirectResponse
    {
        return app(PromoCodeController::class)->toggleStatus($id);
    }

    public function destroyPromoCode(int $id): RedirectResponse
    {
        return app(PromoCodeController::class)->destroy($id);
    }

    public function createDiscount(StorePromoCodeRequest $request): RedirectResponse
    {
        return app(PromoCodeController::class)->store($request);
    }

    public function staff(): View
    {
        return app(StaffController::class)->index();
    }

    public function storeStaff(StoreStaffRequest $request): RedirectResponse
    {
        return app(StaffController::class)->store($request);
    }

    public function updateStaffPermissions(Request $request, int $id): RedirectResponse
    {
        return app(StaffController::class)->updatePermissions($request, $id);
    }

    public function toggleStaffStatus(int $id): RedirectResponse
    {
        return app(StaffController::class)->toggleStatus($id);
    }

    public function settings(TurnstileService $turnstileService): View
    {
        return app(PlatformSettingController::class)->index($turnstileService);
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        return app(PlatformSettingController::class)->update($request);
    }

    public function updateCaptchaSettings(Request $request): RedirectResponse
    {
        return app(PlatformSettingController::class)->updateCaptcha($request);
    }

    public function paymentSettings(
        StripePaymentService $stripeService,
        PayPalPaymentService $paypalService
    ): View {
        return app(PlatformSettingController::class)->paymentSettings($stripeService, $paypalService);
    }

    public function updatePaymentSettings(Request $request): RedirectResponse
    {
        return app(PlatformSettingController::class)->updatePaymentSettings($request);
    }

    public function togglePaymentGateway(Request $request): RedirectResponse
    {
        return app(PlatformSettingController::class)->togglePaymentGateway($request);
    }

    public function testPaymentGateway(Request $request): RedirectResponse
    {
        return app(PlatformSettingController::class)->testPaymentGateway($request);
    }

    public function compliance(): View
    {
        return app(ComplianceController::class)->index();
    }

    public function updateCompliance(Request $request): RedirectResponse
    {
        return app(ComplianceController::class)->update($request);
    }
}

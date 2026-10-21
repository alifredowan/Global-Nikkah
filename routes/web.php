<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlockedUserController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiscoveryController;
use App\Http\Controllers\InterestController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\MarriageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentWebhookController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfileVisitorController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\WaliController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/pricing', [SubscriptionController::class, 'pricing'])->name('subscription.pricing');
Route::get('/privacy', [LandingController::class, 'privacy'])->name('privacy');
Route::get('/terms', [LandingController::class, 'terms'])->name('terms');
Route::get('/about', [LandingController::class, 'about'])->name('about');
Route::get('/contact', [LandingController::class, 'contact'])->name('contact');
Route::get('/how-it-works', [LandingController::class, 'howItWorks'])->name('how-it-works');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots.txt');
Route::get('/sitemap.xml', [SitemapController::class, 'sitemap'])->name('sitemap');
Route::get('/photos/{photo}/view', [PhotoController::class, 'show'])
    ->middleware('throttle:180,1')
    ->name('photos.view');

// Email Verification (Public Signed & Notice)
Route::get('/email/verify', [AuthController::class, 'showVerificationNotice'])->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify');
Route::post('/email/resend', [AuthController::class, 'resendVerification'])
    ->middleware('throttle:5,10')
    ->name('verification.send');

// Wali (Guardian) Email Invitation Approval (Public Token)
Route::get('/wali/invite/{token}', [WaliController::class, 'showInvitation'])->name('wali.invite.show');
Route::post('/wali/invite/{token}/accept', [WaliController::class, 'acceptInvitation'])
    ->middleware('throttle:10,10')
    ->name('wali.invite.accept');
Route::post('/wali/invite/{token}/decline', [WaliController::class, 'declineInvitation'])
    ->middleware('throttle:10,10')
    ->name('wali.invite.decline');

// Payment Gateway Webhooks (Public)
Route::post('/webhook/stripe', [PaymentWebhookController::class, 'handleStripe'])
    ->middleware('throttle:120,1')
    ->name('webhook.stripe');
Route::post('/webhook/paypal', [PaymentWebhookController::class, 'handlePayPal'])
    ->middleware('throttle:60,1')
    ->name('webhook.paypal');

// Guest Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,60');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');

    // Password Reset
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])
        ->middleware('throttle:5,10')
        ->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])
        ->middleware('throttle:10,10')
        ->name('password.update');
});

// Authenticated routes (Logout accessible to all authenticated users)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Authenticated & Email Verified Protected Routes (FR-4.1)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/account/deactivate', [AuthController::class, 'deactivate'])
        ->middleware('throttle:3,60')
        ->name('account.deactivate');
    Route::post('/account/delete', [AuthController::class, 'deleteAccount'])
        ->middleware('throttle:3,60')
        ->name('account.delete');
    Route::get('/blocked-users', [BlockedUserController::class, 'index'])->name('blocked-users.index');
    Route::post('/blocked-users/{userId}', [BlockedUserController::class, 'store'])
        ->middleware('throttle:30,1')
        ->name('blocked-users.store');
    Route::delete('/blocked-users/{userId}', [BlockedUserController::class, 'destroy'])
        ->middleware('throttle:30,1')
        ->name('blocked-users.destroy');

    // Authenticated seeker home
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Account & Security Settings (Super Admin, Moderator, and Normal Users)
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])
        ->middleware('throttle:10,10')
        ->name('settings.profile.update');
    Route::post('/settings/password', [SettingsController::class, 'updatePassword'])
        ->middleware('throttle:5,10')
        ->name('settings.password.update');
    Route::post('/settings/api-sessions/revoke', [SettingsController::class, 'revokeApiSessions'])
        ->middleware('throttle:5,10')
        ->name('settings.api-sessions.revoke');
    Route::post('/settings/preferences', [SettingsController::class, 'updatePreferences'])
        ->middleware('throttle:30,1')
        ->name('settings.preferences.update');

    // Profile Management & Pro Features
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])
        ->middleware('throttle:20,1')
        ->name('profile.update');
    Route::post('/profile/photos', [ProfileController::class, 'uploadPhoto'])
        ->middleware('throttle:20,60')
        ->name('profile.photos.upload');
    Route::post('/profile/photos/{id}/primary', [ProfileController::class, 'setPrimaryPhoto'])
        ->middleware('throttle:30,1')
        ->name('profile.photos.primary');
    Route::delete('/profile/photos/{id}', [ProfileController::class, 'deletePhoto'])
        ->middleware('throttle:30,1')
        ->name('profile.photos.delete');
    Route::post('/profile/photos/grant/{userId}', [ProfileController::class, 'grantPhotoAccess'])
        ->middleware('throttle:30,1')
        ->name('profile.photos.grant');
    Route::delete('/profile/photos/grant/{userId}', [ProfileController::class, 'revokePhotoAccess'])
        ->middleware('throttle:30,1')
        ->name('profile.photos.revoke');
    Route::post('/profile/verification', [ProfileController::class, 'submitVerification'])
        ->middleware('throttle:5,60')
        ->name('profile.verification.submit');
    Route::get('/profile/visitors', [ProfileVisitorController::class, 'index'])->name('profile.visitors');
    Route::post('/profile/incognito', [ProfileVisitorController::class, 'toggleIncognito'])
        ->middleware('throttle:30,1')
        ->name('profile.incognito.toggle');
    Route::get('/profile/biodata', [ProfileController::class, 'biodata'])->name('profile.biodata');
    Route::get('/profile/{id}/biodata', [ProfileController::class, 'biodata'])->name('profile.biodata.show');

    // Pro Shortlisted Profiles (Bookmarks)
    Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');
    Route::post('/bookmarks/{userId}/toggle', [BookmarkController::class, 'toggle'])
        ->middleware('throttle:60,1')
        ->name('bookmarks.toggle');
    Route::post('/bookmarks/{bookmarkId}/notes', [BookmarkController::class, 'updateNotes'])
        ->middleware('throttle:30,1')
        ->name('bookmarks.notes');

    // Discovery & Matching
    Route::get('/discover', [DiscoveryController::class, 'index'])->name('discovery.index');
    Route::get('/profile/{id}', [DiscoveryController::class, 'show'])->name('discovery.show');
    Route::get('/discover/{id}', [DiscoveryController::class, 'show']);
    Route::post('/saved-searches', [DiscoveryController::class, 'saveSearch'])
        ->middleware('throttle:30,1')
        ->name('discovery.save-search');
    Route::post('/saved-searches/{id}/toggle-alerts', [DiscoveryController::class, 'toggleSavedSearchAlerts'])
        ->middleware('throttle:30,1')
        ->name('discovery.saved-searches.toggle-alerts');
    Route::delete('/saved-searches/{id}', [DiscoveryController::class, 'destroySavedSearch'])
        ->middleware('throttle:30,1')
        ->name('discovery.saved-searches.destroy');

    // Interests & Requests
    Route::get('/interests', [InterestController::class, 'index'])->name('interests.index');
    Route::post('/interests/{userId}', [InterestController::class, 'send'])
        ->middleware('throttle:20,1')
        ->name('interests.send');
    Route::post('/interests/{id}/respond', [InterestController::class, 'respond'])
        ->middleware('throttle:20,1')
        ->name('interests.respond');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

    // Wali (Guardian) workflow
    Route::get('/wali/link', [WaliController::class, 'linkForm'])->name('wali.link');
    Route::post('/wali/link', [WaliController::class, 'linkStore'])
        ->middleware('throttle:5,10')
        ->name('wali.link.store');
    Route::get('/wali/dashboard', [WaliController::class, 'dashboard'])->name('wali.dashboard');
    Route::post('/wali/interests/{id}/approve', [WaliController::class, 'approveInterest'])
        ->middleware('throttle:20,1')
        ->name('wali.interests.approve');
    Route::post('/wali/interests/{id}/reject', [WaliController::class, 'rejectInterest'])
        ->middleware('throttle:20,1')
        ->name('wali.interests.reject');

    // Halal Chaperoned Messaging
    Route::get('/messages', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/messages/{id}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/messages/{id}', [ChatController::class, 'sendMessage'])
        ->middleware('throttle:30,1')
        ->name('chat.send');
    Route::post('/reports', [ChatController::class, 'reportUser'])
        ->middleware('throttle:5,10')
        ->name('reports.store');

    // Nikah Mubarak & Marriage Completion Milestone
    Route::get('/nikah-mubarak', [MarriageController::class, 'celebration'])->name('marriages.celebration');
    Route::post('/marriages/initiate', [MarriageController::class, 'initiate'])
        ->middleware('throttle:10,10')
        ->name('marriages.initiate');
    Route::post('/marriages/{id}/confirm', [MarriageController::class, 'confirm'])
        ->middleware('throttle:10,10')
        ->name('marriages.confirm');
    Route::post('/marriages/{id}/decline', [MarriageController::class, 'decline'])
        ->middleware('throttle:10,10')
        ->name('marriages.decline');
    Route::post('/marriages/{id}/story', [MarriageController::class, 'submitStory'])
        ->middleware('throttle:5,10')
        ->name('marriages.story');

    // Subscription & Checkout
    Route::get('/checkout/{plan}', [SubscriptionController::class, 'checkout'])->name('subscription.checkout');
    Route::post('/checkout/validate-promo', [SubscriptionController::class, 'validatePromo'])
        ->middleware('throttle:20,1')
        ->name('subscription.validate-promo');
    Route::post('/checkout/process', [SubscriptionController::class, 'process'])
        ->middleware('throttle:10,10')
        ->name('subscription.process');
    Route::get('/checkout/stripe/success', [SubscriptionController::class, 'stripeSuccess'])->name('subscription.stripe.success');
    Route::get('/checkout/paypal/success', [SubscriptionController::class, 'paypalSuccess'])->name('subscription.paypal.success');
    Route::post('/subscription/cancel', [SubscriptionController::class, 'cancel'])
        ->middleware('throttle:5,10')
        ->name('subscription.cancel');

    // Admin & Moderation Console
    Route::middleware('moderator')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

        // KYC & Verification Queue (manage_verifications permission)
        Route::middleware('permission:manage_verifications')->group(function () {
            Route::get('/verifications', [AdminController::class, 'verifications'])->name('verifications');
            Route::get('/verifications/{id}/documents/{type}', [AdminController::class, 'downloadVerificationDocument'])
                ->middleware('throttle:30,1')
                ->name('verifications.documents');
            Route::post('/verifications/{id}/approve', [AdminController::class, 'approveVerification'])
                ->middleware('throttle:30,1')
                ->name('verifications.approve');
            Route::post('/verifications/{id}/reject', [AdminController::class, 'rejectVerification'])
                ->middleware('throttle:30,1')
                ->name('verifications.reject');
        });

        // User Abuse Reports & Moderation (manage_reports permission)
        Route::middleware('permission:manage_reports')->group(function () {
            Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
            Route::post('/reports/{id}/action', [AdminController::class, 'actionReport'])
                ->middleware('throttle:30,1')
                ->name('reports.action');
        });

        // Audit Logs (view_audit_logs permission)
        Route::middleware('permission:view_audit_logs')->group(function () {
            Route::get('/audit-logs', [AdminController::class, 'auditLogs'])->name('audit-logs');
        });

        // Super Admin Only: Staff Management & Permissions Control
        Route::middleware('super_admin')->group(function () {
            Route::get('/staff', [AdminController::class, 'staff'])->name('staff');
            Route::post('/staff', [AdminController::class, 'storeStaff'])
                ->middleware('throttle:10,10')
                ->name('staff.store');
            Route::post('/staff/{id}/permissions', [AdminController::class, 'updateStaffPermissions'])
                ->middleware('throttle:30,1')
                ->name('staff.permissions');
            Route::post('/staff/{id}/toggle-status', [AdminController::class, 'toggleStaffStatus'])
                ->middleware('throttle:30,1')
                ->name('staff.toggle-status');
        });

        // Platform Policies, Dynamic Packages & Promo Codes (manage_settings or Super Admin)
        Route::middleware('permission:manage_settings')->group(function () {
            // Dynamic Subscription Packages
            Route::get('/packages', [AdminController::class, 'packages'])->name('packages.index');
            Route::post('/packages', [AdminController::class, 'storePackage'])
                ->middleware('throttle:30,1')
                ->name('packages.store');
            Route::put('/packages/{id}', [AdminController::class, 'updatePackage'])
                ->middleware('throttle:30,1')
                ->name('packages.update');
            Route::post('/packages/{id}/toggle-status', [AdminController::class, 'togglePackageStatus'])
                ->middleware('throttle:30,1')
                ->name('packages.toggle-status');
            Route::delete('/packages/{id}', [AdminController::class, 'destroyPackage'])
                ->middleware('throttle:30,1')
                ->name('packages.destroy');

            // Promo Codes & Special User Discounts
            Route::get('/promo-codes', [AdminController::class, 'promoCodes'])->name('promo-codes.index');
            Route::post('/promo-codes', [AdminController::class, 'storePromoCode'])
                ->middleware('throttle:30,1')
                ->name('promo-codes.store');
            Route::post('/promo-codes/{id}/toggle-status', [AdminController::class, 'togglePromoCodeStatus'])
                ->middleware('throttle:30,1')
                ->name('promo-codes.toggle-status');
            Route::delete('/promo-codes/{id}', [AdminController::class, 'destroyPromoCode'])
                ->middleware('throttle:30,1')
                ->name('promo-codes.destroy');

            // Platform Policy Parameters
            Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
            Route::post('/settings', [AdminController::class, 'updateSettings'])
                ->middleware('throttle:30,1')
                ->name('settings.update');
            Route::post('/discounts', [AdminController::class, 'createDiscount'])
                ->middleware('throttle:30,1')
                ->name('discounts.store');

            // Payment Gateways & Transaction Configuration (PayPal & Stripe)
            Route::get('/payment-settings', [AdminController::class, 'paymentSettings'])->name('payment-settings');
            Route::post('/payment-settings', [AdminController::class, 'updatePaymentSettings'])
                ->middleware('throttle:30,1')
                ->name('payment-settings.update');
            Route::post('/payment-settings/toggle', [AdminController::class, 'togglePaymentGateway'])
                ->middleware('throttle:30,1')
                ->name('payment-settings.toggle');
            Route::post('/payment-settings/test', [AdminController::class, 'testPaymentGateway'])
                ->middleware('throttle:5,10')
                ->name('payment-settings.test');

            // Cloudflare Turnstile CAPTCHA Configuration
            Route::post('/settings/captcha', [AdminController::class, 'updateCaptchaSettings'])
                ->middleware('throttle:30,1')
                ->name('settings.captcha.update');
        });
    });
});

# 💍 Global Nikkah (Nikah Connect)

<p align="center">
  <img src="public/images/nikah-connect-banner.svg" width="100%" alt="Nikah Connect Banner">
</p>

<p align="center">
  <strong>An International, Shariah-Compliant Muslim Matrimonial Web Application</strong><br>
  Designed with adherence to Islamic Shariah, Modesty (<em>Haya</em>), Family Involvement (<em>Wali</em> Oversight), and Deen-Centric Compatibility.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 13">
  <img src="https://img.shields.io/badge/PHP-8.4-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.4">
  <img src="https://img.shields.io/badge/Tailwind_CSS-4.x-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="Tailwind CSS 4">
  <img src="https://img.shields.io/badge/WebSocket-Laravel_Reverb-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel Reverb">
  <img src="https://img.shields.io/badge/Database-PostgreSQL_%7C_MySQL_%7C_SQLite-336791?style=for-the-badge&logo=postgresql&logoColor=white" alt="Database Support">
  <img src="https://img.shields.io/badge/Security-Cloudflare_Turnstile-F38020?style=for-the-badge&logo=cloudflare&logoColor=white" alt="Cloudflare Turnstile">
</p>

---

## 📖 Table of Contents
1. [Platform Vision & Core Principles](#-platform-vision--core-principles)
2. [Key Architecture & Features](#-key-architecture--features)
3. [Seeded Demo Credentials](#-seeded-demo-credentials)
4. [Comprehensive User Manual & Roles](#-comprehensive-user-manual--roles)
   - [Seeker Journey (Grooms & Brides)](#1-seeker-journey-grooms--brides)
   - [Guardian (Wali) Chaperoned Workflow](#2-guardian-wali-chaperoned-workflow)
   - [Super Administrator Suite](#3-super-administrator-suite)
   - [Moderation & Safety Center](#4-moderation--safety-center)
5. [Tech Stack](#-tech-stack)
6. [Installation & Quick Start](#-installation--quick-start)
7. [Cloudflare Turnstile CAPTCHA Setup](#-cloudflare-turnstile-captcha-setup)
8. [Email & SMTP Configuration Guide](#-email--smtp-configuration-guide)
9. [Payment Gateways (Stripe & PayPal)](#-payment-gateways-stripe--paypal)
10. [Database Switching (PostgreSQL / MySQL / SQLite)](#-database-switching-postgresql--mysql--sqlite)
11. [Running Real-Time WebSockets (Laravel Reverb)](#-running-real-time-websockets-laravel-reverb)
12. [Testing & Code Quality](#-testing--code-quality)

---

## 🕊️ Platform Vision & Core Principles

Unlike conventional dating applications focused on superficial swiping, **Global Nikkah (Nikah Connect)** is engineered for practicing Muslims worldwide seeking marriage (*Nikah*) with dignity:

* **Family-First Inclusivity (*Wali* System)**: Supports the role of a father or mahram guardian in reviewing prospective suitors and overseeing introductions.
* **Server-Side Modesty Protection (*Haya*)**: Candidate photos remain protected server-side until mutual permission and guardian clearance are granted.
* **Deen Compatibility Above Vanity**: Search and compatibility scoring weight religious practice (prayer frequency, Quran recitation, halal diet, Sunnah adherence) alongside family and lifestyle alignment.
* **Chaperoned Communication**: Messaging preserves Islamic decorum with guardian oversight notifications and automated scanning for premature sharing of off-platform contact details.
* **International Ready**: English-first interface built for Muslim communities worldwide (UK, USA, Canada, Gulf, South Asia, Europe), featuring international E.164 phone validation (`propaganistas/laravel-phone`) and USD billing support.

---

## ✨ Key Architecture & Features

### 🛡️ 1. Islamic Guardian (*Wali*) Workflow
* Sisters can link a father or mahram guardian during or after registration, or invite one via secure email token (`/wali/invite/{token}`).
* When a brother sends an interest to a guardian-assisted sister, the request routes to the **Wali Dashboard** (`/wali/dashboard`) for review and approval.
* Both the candidate and her guardian receive real-time notifications on proposal activity.

### ⚡ 2. Real-Time Push Notifications & Live Chat (Laravel Reverb)
* Instant WebSocket notifications powered by **Laravel Reverb** and Laravel Echo.
* Categorized notification bell (`All`, `Interests`, `Messages`, `Family`), unread message badges, and standardized authenticated header controls.
* Real-time chaperoned messaging across private channels with keyboard-accessible UI and quick command palette (`Ctrl+K` / `Cmd+K`).

### 🤖 3. Bot & Login Security (Cloudflare Turnstile CAPTCHA)
* Protected Login (`/login`) and Registration (`/register`) flows powered by [`TurnstileService`](app/Services/TurnstileService.php) and the implicit [`Turnstile`](app/Rules/Turnstile.php) validation rule.
* Configurable via both `.env` variables and the Admin Settings UI (`/admin/settings`), including support for Cloudflare's always-passing local development keys and an offline dev fallback widget (`<x-turnstile-captcha />`).

### 🍪 4. GDPR Cookie Consent & Compliance Center
* Compact, non-intrusive cookie consent banner (`Accept all`, `Reject non-essential`, `Customize`) storing choices for 12 months and blocking non-essential scripts prior to consent.
* Dedicated legal pages (`/privacy`, `/cookie-policy`, `/terms`) and a footer `"Cookie settings"` trigger.
* Super Admin Compliance & Consent Management console at `/admin/compliance`.

### 💎 5. Dynamic Subscription Packages, Coupons & Payments
* **Custom Plan Builder** (`/admin/packages`): Create, edit, toggle, and target subscription tiers (`Free`, `Monthly`, `Quarterly`, `Annual`, `Lifetime`) by audience.
* **Promo Code Engine** (`/admin/promo-codes`): Create percentage/fixed discount codes (such as `HALAL20` and `BARAKAH50`) with redemption limits, expiry dates, and user/gender restrictions.
* **Payment Gateways** (`/admin/payment-settings`): Dynamic **Stripe** and **PayPal** configuration alongside a toggleable **Simulated / Demo Payment Mode**.

### 🔒 6. Server-Side Modesty Protection
* Private candidate photos are served via protected routes (`/photos/{photo}/view`) and blurred server-side until mutual consent and photo access grants are verified.

### 🛡️ 7. Account Security & Password Reset
* **Zero Duplicate Accounts**: Database-level unique index on `LOWER(email)` and normalized request preparation prevent duplicate accounts across email casing variations.
* **Account Lifecycle**: Throttled password reset, account deactivation, and permanent GDPR-compliant account deletion.
* **4 Theme Moods**: Built-in Light, Dark, Emerald, and Sand theme presets with no-FOUC initialization.

### 📱 8. REST API V1 (Mobile-Ready)
* Sanctum-authenticated endpoints under `/api/v1/` covering auth (`/auth/register`, `/auth/login`, `/auth/me`, `/auth/logout`), discovery (`/discover`, `/discover/daily-matches`, `/profiles/{userId}`), interests (`/interests`), and messaging (`/conversations`).

---

## 🔑 Seeded Demo Credentials

After running `php artisan migrate:fresh --seed`, the following accounts are created by [`SuperAdminSeeder`](database/seeders/SuperAdminSeeder.php) and [`DemoDataSeeder`](database/seeders/DemoDataSeeder.php):

| Role | Email Address | Default Password | Primary Capabilities |
|---|---|---|---|
| **👑 Super Admin** | `admin@nikahconnect.com` | `Password123!` | Packages, promo codes, staff roles, CAPTCHA/payment settings, compliance & audit logs |
| **🧔 Groom (Seeker)** | `seeker.groom@nikahconnect.test` | `password` | Search candidate brides, send halal interests, live chaperoned chat |
| **🧕 Bride (Seeker)** | `seeker.bride@nikahconnect.test` | `password` | Profile biodata, Wali-linked workflow, review incoming proposals |
| **🛡️ Guardian (Wali)** | `wali.father@nikahconnect.test` | `password` | Review suitors for wards, approve/decline proposals, chaperoned oversight |
| **⚖️ Moderator Staff** | `moderator@nikahconnect.test` | `password` | Review KYC verification submissions, resolve safety reports |

> **Note**: Super Admin credentials can be customized via `SUPER_ADMIN_EMAIL`, `SUPER_ADMIN_PASSWORD`, and `SUPER_ADMIN_NAME` in `.env`.

---

## 📚 Comprehensive User Manual & Roles

### 1. Seeker Journey (Grooms & Brides)
1. **Registration & 18+ Verification (`/register`)**:
   - Provide legal name, email, optional international phone number, gender, and date of birth (`dd/mm/yyyy`).
   - Enforces a strict **18+ age requirement** and Turnstile CAPTCHA verification.
2. **Deen & Family Profile Setup (`/profile/edit`)**:
   - Specify sect/madhhab, daily prayer consistency, hijab/beard practice, Quran literacy, education, employment, and family preferences.
3. **Candidate Discovery (`/discover`)**:
   - Filter and sort candidates by deen practice, location, age, education, and compatibility score; save searches and manage shortlist bookmarks.
4. **Sending Halal Interest (`/interests`)**:
   - Send an introductory proposal note. If the candidate has an active Wali, both the candidate and her guardian are notified.
5. **Chaperoned Live Messaging (`/messages`)**:
   - Unlocked upon mutual acceptance (and Wali clearance where required), with automatic contact-sharing moderation.

### 2. Guardian (Wali) Chaperoned Workflow
1. **Guardian Registration (`/register` → Guardian Role)** or **Email Invitation (`/wali/invite/{token}`)**:
   - Register as Father, Brother, Uncle, Grandfather, or Mahram guardian.
2. **Linking Ward (`/wali/link`)**:
   - Link to a daughter or sister's seeker account.
3. **Wali Dashboard (`/wali/dashboard`)**:
   - Review incoming suitors' profiles and KYC verification badges, then **Approve** or **Decline** introductions.

### 3. Super Administrator Suite (`/admin`)
* **Dashboard & Metrics (`/admin`)**: Overview of platform growth, verifications, and reports.
* **Dynamic Packages (`/admin/packages`) & Promo Codes (`/admin/promo-codes`)**: Manage monetization and discounts.
* **Platform, CAPTCHA & Payment Settings (`/admin/settings`, `/admin/payment-settings`)**: Toggle Turnstile CAPTCHA, update API keys, and configure Stripe/PayPal.
* **Compliance & Cookie Consent (`/admin/compliance`)**: Manage consent records and legal disclosures.
* **Staff Management (`/admin/staff`) & Audit Logs (`/admin/audit-logs`)**: Provision moderators with granular permissions and inspect security logs.

### 4. Moderation & Safety Center (`/admin/verifications` & `/admin/reports`)
* **KYC Verification Queue**: Inspect uploaded ID documents and grant the `"Verified"` status.
* **Safety Reports**: Review flagged messages or user reports and take moderation actions.

---

## 🛠️ Tech Stack

* **Backend Framework**: [Laravel 13](https://laravel.com) (`^13.17`) running on **PHP 8.4**
* **Database**: PostgreSQL (default), MySQL, and SQLite
* **Frontend**: Blade Components, [Tailwind CSS v4](https://tailwindcss.com) (`@tailwindcss/vite`), Alpine.js (`^3.17`), Vite (`^8.0`)
* **Real-time Broadcasting**: [Laravel Reverb](https://reverb.laravel.com) (`^1.12`) + Laravel Echo (`^2.5`) & Pusher JS
* **API Authentication**: Laravel Sanctum (`^4.0`)
* **Phone Validation**: `propaganistas/laravel-phone` (`^6.1`)
* **Security**: Cloudflare Turnstile CAPTCHA + strict CSP / security headers middleware
* **Code Quality & Testing**: Laravel Pint (`^1.27`), PHPUnit 12 (`^12.5`)

---

## 🚀 Installation & Quick Start

### Prerequisites
* **PHP >= 8.3** (tested on **PHP 8.4**) with extensions: `pdo`, `pdo_pgsql` (or `pdo_mysql`/`pdo_sqlite`), `mbstring`, `openssl`, `gd`, `bcmath`, `curl`
* **Composer >= 2.x**
* **Node.js >= 20.x** & **npm**
* **PostgreSQL** (or MySQL / SQLite)

### 1. Clone the Repository
```bash
git clone https://github.com/alifredowan/Nikah-Connect.git
cd Nikah-Connect
```

### 2. Install PHP & Node Dependencies
```bash
composer install
npm install
```

### 3. Setup Environment File
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure Database in `.env`
For PostgreSQL (default):
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=nikah_connect
DB_USERNAME=postgres
DB_PASSWORD=your_postgres_password
```
*(Or switch to MySQL/SQLite as described below).*

### 5. Run Migrations & Seed Data
```bash
php artisan migrate:fresh --seed
```
*Creates all tables, default platform settings, subscription plans, and demo accounts.*

### 6. Build Frontend Assets
```bash
npm run build
```

### 7. Start the Local Server
```bash
php artisan serve
```
Visit **`http://localhost:8000`** (or `http://127.0.0.1:8000`) in your browser.

---

## 🤖 Cloudflare Turnstile CAPTCHA Setup

Login and Registration bot protection is enabled via `.env` and can also be managed dynamically from `/admin/settings`.

For local development, `.env.example` ships with Cloudflare's official always-passing testing keys:

```env
TURNSTILE_ENABLED=true
TURNSTILE_SITE_KEY=1x00000000000000000000AA
TURNSTILE_SECRET_KEY=1x0000000000000000000000000000000AA
```

For production:
1. Create a widget in the [Cloudflare Turnstile Dashboard](https://dash.cloudflare.com/?to=/:account/turnstile).
2. Replace `TURNSTILE_SITE_KEY` and `TURNSTILE_SECRET_KEY` in `.env` (or update them in `/admin/settings`).
3. If database settings are overridden in `/admin/settings`, run `php artisan cache:clear` after modifying `.env` directly.

---

## 📧 Email & SMTP Configuration Guide

Verify mail connectivity anytime with the built-in Artisan command:

```bash
php artisan mail:test your_email@example.com
```

### Option A: Gmail SMTP
1. Enable **2-Step Verification** in your Google Account and generate an **App Password**.
2. Configure `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_gmail@gmail.com
MAIL_PASSWORD=your_16_digit_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your_gmail@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Option B: Transactional Mail Provider (Brevo / Resend / Mailtrap)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=your_brevo_account_email
MAIL_PASSWORD=your_brevo_smtp_api_key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="info@globalnikkah.com"
MAIL_FROM_NAME="${APP_NAME}"
```

---

## 💳 Payment Gateways (Stripe & PayPal)

Configure payment credentials in `.env` or manage them directly in `/admin/payment-settings`:

```env
# Stripe Payment Gateway
STRIPE_KEY=
STRIPE_SECRET=
STRIPE_WEBHOOK_SECRET=

# PayPal Payment Gateway
PAYPAL_CLIENT_ID=
PAYPAL_CLIENT_SECRET=
PAYPAL_MODE=sandbox
PAYPAL_WEBHOOK_ID=
```

---

## 🔄 Database Switching (PostgreSQL / MySQL / SQLite)

Nikah Connect supports PostgreSQL, MySQL, and SQLite via `.env`:

### Switching to MySQL
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nikah_connect
DB_USERNAME=root
DB_PASSWORD=
```
Then run:
```bash
php artisan migrate:fresh --seed
```

### Switching to SQLite (Zero-Setup Local Dev)
```env
DB_CONNECTION=sqlite
```
Create the database file and run migrations:
```bash
touch database/database.sqlite
php artisan migrate:fresh --seed
```

---

## 📡 Running Real-Time WebSockets (Laravel Reverb)

For instant notifications and live chat without page reloads:

1. Verify Reverb settings in `.env`:
```env
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=100101
REVERB_APP_KEY=nikahconnectreverbkey
REVERB_APP_SECRET=nikahconnectreverbsecret
REVERB_HOST="127.0.0.1"
REVERB_PORT=8085
REVERB_SERVER_HOST="127.0.0.1"
REVERB_SERVER_PORT=8085
REVERB_SCHEME="http"
```

2. Start the Reverb server in a separate terminal:
```bash
php artisan reverb:start
```

---

## 🧪 Testing & Code Quality

### Run the Test Suite
Tests use an isolated in-memory SQLite database configured in `phpunit.xml`:

```bash
php artisan test
```

Run targeted feature suites, for example:
```bash
php artisan test --filter=TurnstileCaptchaTest
php artisan test --filter=StandardizedHeaderControlsTest
php artisan test --filter=MatrimonialFeatureTest
php artisan test --filter=WaliEmailApprovalAndVerificationTest
php artisan test --filter=AdminComplianceTest
```

### Key Covered Test Suites
* **Authentication & Bot Protection**: `TurnstileCaptchaTest`, `PasswordResetTest`, `EmailChangeSecurityTest`, `AccountDeletionTest`
* **Matrimonial & Wali Workflows**: `MatrimonialFeatureTest`, `WaliEmailApprovalAndVerificationTest`, `MarriageMilestoneTest`, `SingleMarriagePassTest`
* **Modesty, Safety & Privacy**: `BlockedUserTest`, `ApiPrivacyTest`, `ProfileMessagingTest`, `MessageModerationTest`
* **Subscriptions, Admin & Compliance**: `DynamicSubscriptionPackageTest`, `SubscriptionPricingTest`, `PaymentProcessTest`, `AdminComplianceTest`, `StaffManagementTest`
* **UI, Header & Legal Pages**: `StandardizedHeaderControlsTest`, `UnifiedSidebarTest`, `PublicLegalPagesAndFooterTest`

### Run Code Formatting (Laravel Pint)
```bash
vendor/bin/pint --dirty --format agent
```

---

## 📄 License & Attribution

This project is licensed under the [MIT License](LICENSE).  
Built for the global Muslim community with care and dignity. May Allah (*SWT*) bless every marriage initiated through this platform with *Barakah*, *Mawaddah*, and *Rahmah*.

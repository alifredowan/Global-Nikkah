# AI Coding Agent Implementation Guide

> **Audience:** This document is written specifically for an AI coding agent tasked with redesigning or implementing the UI of a matrimonial or community platform in a separate repository.
>
> **Self-Containment:** This guide is 100% self-contained. You do not need any external files or prior conversation history to execute this design system with perfection.

---

## 1. Non-Negotiable Standing Rule

> [!CAUTION]
> **STRICT SCOPE BOUNDARY: VISUAL DESIGN AND UI CODE ONLY.**
> You must **NEVER** alter, rename, or delete:
> - Backend routes, route names, or URI endpoints.
> - Controllers, middleware, or form request validators.
> - Database migrations, schema columns, or model relationships.
> - API payloads, session keys, or existing test assertions.
>
> Any changes you make must be confined exclusively to Blade/HTML templates, CSS stylesheets, and client-side UI presentation scripts (Alpine.js / vanilla JS).

---

## 2. Core Design Philosophy

Matrimonial platforms for conservative, faith-based, or family-centric communities (e.g., Muslim, Hindu, Christian) are **not casual dating apps**.

1. **Dignified & Serious:** Tone must reflect marriage (Nikah, Vivah, Holy Matrimony), family respect, and long-term commitment.
2. **Hand-Crafted & Human:** The interface must look as though it was designed by a seasoned product designer with 10+ years of editorial typography and UI craftsmanship experience.
3. **Calm, Uncluttered Ledger Aesthetics:** Use structured definition-list rows (`<dl>`, `<dt>`, `<dd>`), hairline borders (`1px solid var(--border-default)`), and disciplined spacing rather than bloated, nested cards.
4. **Framed Hero Containers:** Hero banners must **never bleed edge-to-edge** across the entire screen. They must sit inside a framed container (`max-w-7xl mx-auto px-4 sm:px-6 lg:px-8`) with generous curved corners (`rounded-2xl` to `rounded-3xl`) and warm canvas breathing room around them.

---

## 3. Checklist of "AI Tells" to Strictly Avoid

When generating or refactoring UI code, check off every item on this list. If your proposed code contains any of these, **delete it immediately**:

- [ ] **NO purple/pink/magenta gradients:** Never use `from-purple-600 to-pink-500` or neon accents.
- [ ] **NO romantic AI filler copy:** Never write *"Find your soulmate"*, *"Your perfect match awaits"*, or *"Love is in the air"*. Use sincere, plain microcopy: *"A dignified matrimonial directory for practicing candidates and their families."*
- [ ] **NO emojis in UI buttons, icons, or badges:** Do not use `❤️`, `💍`, `✨`, `🔥`, or `👀` as interface icons. Use clean, monoline SVG icons (Lucide or Phosphor) with `stroke-width="2"`.
- [ ] **NO centered stock-couple heroes with two buttons:** Do not create a centered hero with a stock romantic couple photo and two centered buttons. Use a left-aligned, plain headline with an immediate first-screen search form on the right.
- [ ] **NO uniform `rounded-2xl` bubbles everywhere:** Do not apply `rounded-2xl` to buttons, inputs, badges, and small cards alike. Use `4px` (`var(--radius-sm)`) for buttons and inputs, `6px` (`var(--radius-md)`) for cards, and save `16px–24px` exclusively for framed hero containers.
- [ ] **NO identical heavy drop shadows:** Do not apply `shadow-2xl` to every card. Use subtle, grounded hairline borders (`1px solid var(--border-default)`) with `var(--shadow-xs)`.
- [ ] **NO slow floating animations:** Keep all transitions between `120ms` and `160ms`. Never use perpetual bounce, pulse, or floating keyframe effects on standard content.
- [ ] **NO heavy glassmorphism:** Avoid `backdrop-blur-xl bg-white/10` with white borders on dark backgrounds. Use clean, solid surface tokens (`var(--bg-surface)`).

---

## 4. Exact Design Tokens to Use

Always reference CSS custom properties. **Never hardcode hex values** or arbitrary Tailwind values (e.g., do NOT use `bg-[#0f4c42]`; use `bg-[var(--accent-primary)]` or class `.btn-primary`).

### CSS Variables Reference Sheet

```css
:root {
  /* Canvas & Surfaces */
  --bg-canvas: #faf9f5;          /* Warm unbleached off-white page canvas */
  --bg-surface: #ffffff;         /* Pure card/modal surface */
  --bg-subtle: #f3f1ea;          /* Subtle hover/table header background */
  --bg-muted: #e8e5dc;           /* Input background / disabled fill */

  /* Text & Ink */
  --text-primary: #18221e;        /* Dark charcoal ink (high contrast body/headers) */
  --text-secondary: #47544e;      /* Muted secondary labels & explanations */
  --text-muted: #6f7c75;          /* Micro-labels, placeholders, timestamps */
  --text-inverse: #ffffff;

  /* Hairline Borders */
  --border-subtle: #eeece4;       /* Inner row dividers */
  --border-default: #dfdcce;      /* Standard card & input borders */
  --border-strong: #bbb7a5;       /* Card hover border & focused dividers */

  /* Primary Accent (Faith/Community specific) */
  --accent-primary: #0f4c42;      /* Deep Forest Teal (Muslim preset) */
  --accent-hover: #093730;
  --accent-subtle: #edf5f3;       /* Verified badge background, active chips */
  --accent-border: #b8d9d2;       /* Active chip / badge border */
  --accent-text: #0b3d35;         /* Active state text */
  --accent-contrast: #ffffff;

  /* Secondary Accent */
  --secondary-primary: #85531b;   /* Dignified Brass / Amber */
  --secondary-subtle: #fbf5eb;
  --secondary-border: #ebd9be;
  --secondary-text: #61390d;

  /* Semantic Alerts */
  --color-success: #1b6848;       --color-success-bg: #edf7f2; --color-success-border: #b8e0ce;
  --color-warning: #945307;       --color-warning-bg: #fdf8ed; --color-warning-border: #f0dcad;
  --color-danger: #9f2424;        --color-danger-bg: #fdf2f2;  --color-danger-border: #f5c4c4;
  --color-info: #1b5375;          --color-info-bg: #edf5fa;    --color-info-border: #b9d7eb;

  /* Radii Scale */
  --radius-xs: 2px;
  --radius-sm: 4px;              /* Buttons, inputs, search chips */
  --radius-md: 6px;              /* Cards, modals, drawers */
  --radius-2xl: 16px;            /* Floating hero container (mobile) */
  --radius-3xl: 24px;            /* Floating hero container (desktop) */

  /* Accessibility & Transitions */
  --focus-ring: 0 0 0 2px var(--bg-surface), 0 0 0 4px var(--accent-primary);
  --duration-fast: 120ms;
  --duration-normal: 160ms;
  --touch-target-min: 44px;
}
```

---

## 5. Screen-by-Screen Implementation Blueprint

### Screen 1: Landing Page (`landing.blade.php` or `welcome.blade.php`)
1. **Curved Floating Hero:**
   - Wrapper: `<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 sm:pt-6">`.
   - Card: `<section class="card-ledger pattern-header rounded-2xl sm:rounded-3xl p-6 sm:p-10 lg:p-12 bg-[var(--bg-surface)] border border-[var(--border-default)]">`.
   - Layout: 2-column grid (`grid grid-cols-1 lg:grid-cols-12 gap-10 items-start`).
   - Left Column (7 cols): Sincere, left-aligned headline (`text-2xl sm:text-4xl lg:text-5xl font-bold tracking-tight text-[var(--text-primary)]`), dignified description, and a 3-point trust checklist with monoline checkmark SVGs.
   - Right Column (5 cols): Instant search form inside `.card-ledger` (Looking for, age range min/max, district/city, marital status) with a direct submit button to the discovery route.
2. **Trust & Verification Section:**
   - 4-column ledger grid: National ID/KYC verification, Guardian (Wali/Family) oversight, default photo privacy, zero dowry / non-commercial ethics.
3. **Recent Verified Candidates Showcase:**
   - Responsive grid of 3 to 4 candidate cards with modesty photo shields.
4. **Single-Pass Ethical Pricing Ledger:**
   - Transparent pricing display (e.g. *Pay 1-Time, Active Until Confirmed Marriage*) avoiding recurring subscription traps.

---

### Screen 2: Candidate Search & Discovery (`discovery/index.blade.php`)
1. **Desktop Left Filter Sidebar:**
   - Fixed width (`w-72`), sticky (`sticky top-20`).
   - Collapsible groups (Looking for, Age, District/Country, Marital Status, Trust Toggles) with `+` / `−` indicators.
2. **Mobile Bottom Sheet Filter Drawer:**
   - Sliding sheet (`fixed inset-x-0 bottom-0 z-50 rounded-t-2xl max-h-[85vh]`).
   - Toggle button prominently fixed above the candidate list on mobile.
3. **Active Filter Chips Bar:**
   - Horizontal wrap of active tags (e.g., `Bride (Female)`, `Age: 21-30`, `Dhaka`) with individual `&times;` remove triggers and a "Reset all filters" button.
4. **Grid / List View Switcher:**
   - Segmented button toggle switching between 3-column candidate cards and horizontal ledger rows.

---

### Screen 3: Candidate Profile Card (Grid & List)
1. **Photo Container with Modesty Shield:**
   - Default blur filter (`filter: blur(14px);` via class `.photo-shield`) until mutual interest is accepted.
   - Verified KYC badge anchored at top-left.
   - Compatibility percentage badge anchored at top-right.
2. **Metadata Matrix:**
   - Full name, age, height, current district/country, profession, education, and guardian participation status.
3. **Action Footer:**
   - Primary Action: `btn-primary` with label "Send Interest" (or "Express Halal/Sacred Interest").
   - Secondary Action: `btn-secondary` with bookmark icon for "Shortlist".

---

### Screen 4: Candidate Detail Biodata View (`discovery/show.blade.php`)
1. **Header Action Card:**
   - Candidate portrait, verification badge, basic vitals, and primary action buttons.
   - If mutual interest is confirmed, display the confirmation banner:
     ```html
     <div class="card-ledger p-4 bg-[var(--accent-subtle)] border-[var(--accent-border)] flex items-center justify-between gap-4">
       <div>
         <h2 class="text-base font-bold text-[var(--text-primary)]">Mutual Halal Interest Confirmed!</h2>
         <p class="text-xs text-[var(--text-secondary)] mt-0.5">You can now converse directly in chaperoned chat.</p>
       </div>
       <a href="..." class="btn-primary text-xs font-bold">Send Message &rarr;</a>
     </div>
     ```
2. **Sectioned Definition-List Rows (NOT nested cards):**
   - Use `<dl class="divide-y divide-[var(--border-subtle)]">` divided into 5 clear sections:
     1. Basic Candidate Information (Name, DOB, Age, Height, Marital status, Citizenship).
     2. Family Background & Heritage (Guardian status, Father/Mother details, Siblings, Family culture).
     3. Education & Professional Career (Degree, University, Job title, Industry, Relocation flexibility).
     4. Religious / Ethical Practice (Prayer frequency, Dietary adherence, Modesty dress code).
     5. Partner Preferences & Expectations (Age range, Height, Qualities sought).
   - Each row formatted as:
     ```html
     <div class="def-row">
       <dt class="def-term">Property Name</dt>
       <dd class="def-desc">Candidate Value</dd>
     </div>
     ```

---

### Screen 5: Multi-Step Registration Wizard (`auth/register.blade.php`)
1. **Progress Indicator:**
   - Step indicator: Step 1 of 3 (Intent & Role $\rightarrow$ Personal Biodata $\rightarrow$ Security & Affirmation).
   - Continuous progress track (`h-1.5 rounded-full bg-[var(--neutral-200)]`).
2. **Inline Validation:**
   - Real-time client-side checks for legal name length, email format, and password length.
   - Date of Birth must strictly enforce `dd/mm/yyyy` masking and 18+ validation.
3. **Save-and-Continue-Later:**
   - Secondary button in form footer allowing users to save partial biodata drafts.

---

### Screen 6: Interests Inbox (`interests/index.blade.php`)
1. **Tab Navigation:**
   - Clean tabs: Received (Pending), Sent, Accepted, Declined.
2. **Ledger Row Layout:**
   - Candidate avatar, verified badge, submission timestamp (`diffForHumans()`), candidate message note in quotation block, and action buttons:
     - For incoming: "Accept Interest" (`btn-primary`) and "Decline" (`btn-secondary`).
     - For accepted: "Open Conversation" link.

---

## 6. Execution Workflow for the AI Agent

When implementing this guide in a project:

1. **Phase 1: Ingest Tokens:** Add `design-tokens.css` and `theme.css` to the application's CSS entry point (e.g. `resources/css/app.css` via `@import './design-tokens.css';` and `@import './theme.css';`).
2. **Phase 2: Component Verification:** Ensure `.card-ledger`, `.btn-primary`, `.btn-secondary`, `.badge-status`, `.badge-verified`, and `.def-row` compile cleanly.
3. **Phase 3: Screen-by-Screen Refactoring:**
   - Start with Layout (`layouts/app.blade.php`), remove emojis and bubbly styles.
   - Refactor Landing (`landing.blade.php`), ensuring the framed curved hero container.
   - Refactor Discovery & Filters (`discovery/index.blade.php`).
   - Refactor Profile Detail (`discovery/show.blade.php` and `profile/show.blade.php`).
   - Refactor Registration & Interests (`auth/register.blade.php`, `interests/index.blade.php`).
4. **Phase 4: Verification Suite:**
   - Run the project test suite (`php artisan test`). Every single test must pass without failures.
   - Run the code formatter (`vendor/bin/pint --dirty --format agent`).
   - Run asset compilation (`npm run build`). Verify zero CSS or JS errors.

